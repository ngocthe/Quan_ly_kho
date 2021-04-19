<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Resources\ImportResource;
use App\Models\Import;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $customer = $request->query('customer_id');
        $supplier = $request->query('supplier_id');
        $etd = $request->query('etd');
        $eta = $request->query('eta');
        $ata = $request->query('ata');
        $term = $request->query('term');
        $source = $request->query('source_id');
        $product = $request->query('product_id');
        $paymentTerm = $request->query('payment_term_id');
        $paymentStatus = $request->query('payment_status');
        $deliveryStatus = $request->query('delivery_status');
        $query = Import::query();
        if ($search) {
            $query->whereLike(['invoice_number'], '%' . $search . '%');
        }
        if ($customer) {
            $query->whereIn('customer_id', $customer);
        }
        if ($supplier) {
            $query->whereIn('supplier_id', $supplier);
        }
        if ($etd) {
            $query->where('etd', '>=', $etd[0])->where('etd', '<=', $etd[1]);
        }
        if ($eta) {
            $query->where('eta', '>=', $eta[0])->where('eta', '<=', $eta[1]);
        }
        if ($ata) {
            $query->where('ata', '>=', $ata[0])->where('ata', '<=', $ata[1]);
        }
        if ($term) {
            $query->whereIn('term', $term);
        }
        if ($source) {
            $query->whereIn('source_id', $source);
        }
        if ($product) {
            $query->whereIn('product_id', $product);
        }
        if ($paymentTerm) {
            $query->whereIn('payment_term_id', $paymentTerm);
        }
        if (isset($paymentStatus)) {
            if ($paymentStatus) {
                $query->whereNotNull('payment_date');
            } else {
                $query->whereNull('payment_date');
            }

        }
        if (isset($deliveryStatus)) {
            if ($deliveryStatus) {
                $query->whereNotNull('ata');
            } else {
                $query->whereNull('ata');
            }

        }
        $data = $query->latest('etd')->orderBy('id')->with('source:id,name', 'product:id,name', 'paymentTerm:id,name', 'customer:id,code', 'supplier:id,code')->get();
        if ($request->query('export')) {
            return $this->export($data);
        }
        return ImportResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImportRequest $request)
    {
        $import = Import::create($request->all());
        $this->remind($import);
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function update(ImportRequest $request, Import $import)
    {
        $eta = $import->eta;
        $import->update($request->all());
        if ($eta != $request->eta) {
            $this->remind($import);
        }
        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function destroy(Import $import)
    {
        return Helper::delete($import);
    }

    public function export($data)
    {
        $file = public_path() . '/excel/import.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                $sheet->setCellValue('J2', $data->sum('quantity'));
                $sheet->setCellValue('L2', round($data->sum('amount'), 2));
                $sheet->setCellValue('O2', round($data->where('payment_date', null)->sum('amount'), 2));
                foreach ($data as $key => $value) {
                    $sheet->row($key + 4, [
                        $value['invoice_number'],
                        $value['etd'],
                        $value['eta'],
                        $value['ata'],
                        $value['term'],
                        $value['source']['name'],
                        $value['supplier']['code'],
                        $value['customer'] ? $value['customer']['code'] : null,
                        $value['product']['name'],
                        $value['quantity'],
                        $value['unit'] . round($value['price'], 3),
                        $value['amount'],
                        $value['paymentTerm']['name'],
                        $value['payment_date'],
                        $value['payment_date'] ? 'OK' : $value['amount'],
                    ]);
                    $sheet->cell('A' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('C' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('D' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('E' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('F' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('G' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('H' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('I' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('J' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('K' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('L' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('M' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('N' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('O' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }
            });
        })->download('xlsx');
    }
    protected function remind($import)
    {
        if ($import->job_id) {
            DB::table('jobs')->where('id', $import->job_id)->delete();
        }
        $job = (new \App\Jobs\SendReminderEmail($import))->delay(Carbon::now()->addSeconds(10));
        $import->update(['job_id' => app(Dispatcher::class)->dispatch($job)]);
    }

}
