<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use App\Helpers\Stock;
use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsDeliveryNoteRequest;
use App\Http\Resources\GoodsDeliveryNoteResource;
use App\Models\GoodsDeliveryNote;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GoodsDeliveryNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = $request->query('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $partner = $request->query('partner_id');
        $search = $request->query('search');
        $type = $request->query('type');
        $query = GoodsDeliveryNote::where('date', '>=', $date[0])->where('date', '<=', $date[1]);
        if ($search) {
            $query
                ->whereHas('products', function (Builder $query) use ($search) {
                    $query->where('name','ilike', '%' . $search . '%');
                    $query->orWhere('po_no','ilike', '%' . $search . '%');
                });
        }

        if (isset($partner)) {
            $query->wherePartnerId($partner);
        }
        if ($type && !$request->query('export')) {
            $query->whereType($type);
        }
        $query = $query->with('partner:id,code,address,tax_code,name', 'products:id,plastic_id,name');
        if ($request->query('export')) {
            $this->export($query->orderBy('date', 'asc')->get());
        }
        return GoodsDeliveryNoteResource::collection($query->orderBy('date', 'asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GoodsDeliveryNoteRequest $request)
    {
        DB::beginTransaction();
        try {
            $goodsDeliveryNote = GoodsDeliveryNote::create([
                'date' => $request->date,
                'document_number' => $request->document_number,
                'partner_id' => $request->partner_id,
                'unit' => $request->unit,
                'exchange_rate' => $request->exchange_rate,
                'type' => $request->type,
                'tax' => $request->tax,
            ]);
            Stock::updateQuantityOfProducts($goodsDeliveryNote, $request->products);
            Stock::updateDept($goodsDeliveryNote);
            DB::commit();
            return Response::created();
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoodsDeliveryNote  $goodsDeliveryNote
     * @return \Illuminate\Http\Response
     */
    public function update(GoodsDeliveryNoteRequest $request, GoodsDeliveryNote $goodsDeliveryNote)
    {
        DB::beginTransaction();
        try {
            $goodsDeliveryNote->update([
                'date' => $request->date,
                'document_number' => $request->document_number,
                'partner_id' => $request->partner_id,
                'unit' => $request->unit,
                'exchange_rate' => $request->exchange_rate,
                'type' => $request->type,
                'tax' => $request->tax,
            ]);
            Stock::restoreQuantityOfProducts($goodsDeliveryNote);
            $goodsDeliveryNote->products()->detach();
            Stock::updateQuantityOfProducts($goodsDeliveryNote, $request->products);
            Stock::updateDept($goodsDeliveryNote);
            DB::commit();
            return Response::updated();
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoodsDeliveryNote  $goodsDeliveryNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsDeliveryNote $goodsDeliveryNote)
    {
        DB::beginTransaction();
        try {
            Stock::restoreQuantityOfProducts($goodsDeliveryNote);
            $goodsDeliveryNote->products()->detach();
            Stock::updateDept($goodsDeliveryNote);
            $goodsDeliveryNote->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return Response::error();
        }
    }
    public function export($data)
    {
        foreach ($data as $note) {
            foreach ($note->products as $product) {
                foreach ($note->toArray() as $field => $value) {
                    if ($field != 'products' && $field != 'id') {
                        $product[$field] = $value;
                    }
                }
                $product['note_id'] = $note->id;
                $product['quantity'] = $product['pivot']['quantity'];
                $product['price_in_usd'] = $note->unit === '$' ? +$product['pivot']['price'] : 0;
                $product['price_in_vnd'] = round($product['pivot']['price'] * $note['exchange_rate'], 0);
                $product['amount_in_usd'] = $note->unit === '$' ? round($product['pivot']['price'] * $product['pivot']['quantity'], 2) : 0;
                $product['amount_in_vnd'] = round($product['pivot']['price'] * $product['pivot']['quantity'] * $note['exchange_rate'], 0);
                $product['total_amount'] = round($product['amount_in_vnd'] * (1 + $note->tax / 100), 0);
                $product['profit'] = $note->type === 1 ? $product['amount_in_vnd'] - $product['pivot']['cost_price'] * $product['quantity'] : 0;
            }
        }
        $data = $data->map(function ($item) {
            return $item->products;
        })->flatten();
        $file = public_path() . '/excel/gdn.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                $totalQuantity = 0;
                $totalAmountInVND = 0;
                $totalAmountInUSD = 0;
                $totalAmount = 0;
                $totalProfit = 0;
                foreach ($data as $key => $value) {
                    $totalQuantity += $value['quantity'];
                    $totalAmountInVND += $value['amount_in_vnd'];
                    $totalAmountInUSD += $value['amount_in_usd'];
                    $totalAmount += $value['total_amount'];
                    $totalProfit += $value['profit'];
                    $sheet->row($key + 8, [
                        $value['document_number'],
                        $value['date'],
                        $value['partner']['name'],
                        $value['name'],
                        $value['quantity'],
                        $value['price_in_usd'],
                        $value['price_in_vnd'],
                        $value['amount_in_usd'],
                        $value['amount_in_vnd'],
                        $value['tax'],
                        $value['total_amount'],
                        $value['profit'],
                        $value['type'] == 1 ? 'Xuất bán hàng' : ($value === 2 ? 'Xuất cho vay' : 'Xuất hàng mẫu'),
                    ]);
                    $sheet->cell('A' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('C' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('D' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('E' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('F' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('G' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('H' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('I' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('J' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('K' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('L' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('M' . ($key + 8), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }
                $sheet->mergeCells('A' . (count($data) + 8) . ':D' . (count($data) + 8));
                $sheet->cell('A' . (8 + count($data)), function ($cell) {
                    $cell->setValue('Tổng');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('E' . (8 + count($data)), function ($cell) use ($totalQuantity) {
                    $cell->setValue($totalQuantity);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('H' . (8 + count($data)), function ($cell) use ($totalAmountInUSD) {
                    $cell->setValue($totalAmountInUSD);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('I' . (8 + count($data)), function ($cell) use ($totalAmountInVND) {
                    $cell->setValue($totalAmountInVND);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('K' . (8 + count($data)), function ($cell) use ($totalAmount) {
                    $cell->setValue($totalAmount);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('L' . (8 + count($data)), function ($cell) use ($totalProfit) {
                    $cell->setValue($totalProfit);
                    $cell->setFontWeight('bold');
                });

                $sheet->cell('A' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('B' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('C' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('D' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('E' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('F' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('G' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('H' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('I' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('J' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('K' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('L' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('M' . (count($data) + 8), function ($cell) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
            });
        })->download('xlsx');
    }

}
