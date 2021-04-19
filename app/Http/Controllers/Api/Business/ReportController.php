<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\GoodsDeliveryNote;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getSalesReport(Request $request)
    {
        $date = $request->query->get('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $customer = $request->query->get('partner_id');
        $query = GoodsDeliveryNote::where('date', '>=', $date[0])->where('date', '<=', $date[1])->whereType(1);
        if ($customer) {
            $query = $query->whereIn('partner_id', $customer);
        }
        $data = $query->with('products:id')->orderBy('date')->get();
        $data->each(function ($gdn) {
            $products = $gdn->products;
            unset($gdn['products']);
            $products = $products->map(function ($item) use ($gdn) {
                return [
                    'cost' => round($item->pivot->cost_price * $item->pivot->quantity * $gdn->exchange_rate, 0),
                    'revenue' => round($item->pivot->price * $item->pivot->quantity, 0),
                ];
            });
            $gdn['cost'] = $products->sum('cost');
            $gdn['revenue'] = $products->sum('revenue');
            $gdn['profit'] = $gdn['revenue'] - $gdn['cost'];
        });
        $data = $data->groupBy('date')->mapWithKeys(function ($item, $key) {
            return [$key => [
                'date' => Carbon::parse($key)->format('d\\/\\t\\h\\g\\ m/Y'),
                'explain' => 'Doanh số ngày ' . sprintf("%02d", Carbon::parse($key)->day),
                'cost' => $item->sum('cost'),
                'revenue' => $item->sum('revenue'),
                'profit' => $item->sum('profit'),
            ]];
        });
        $result = [];
        $dates = collect(CarbonPeriod::create($date[0], $date[1]))->map(function ($date) {
            return $date->toDateString();
        });
        $dates->each(function ($date) use (&$result, $data) {
            $result[$date] = isset($data[$date]) ? $data[$date] : [
                'date' => Carbon::parse($date)->format('d\\/\\t\\h\\g\\ m/Y'),
                'explain' => 'Doanh số ngày ' . sprintf("%02d", Carbon::parse($date)->day),
                'cost' => 0,
                'revenue' => 0,
                'profit' => 0,
            ];
        });
        $result = collect($result)->values();
        if ($request->query('export')) {
            $this->exportSalesReport($result);
        } else {
            return ['data' => $result];
        }
    }
    protected function exportSalesReport($data)
    {
        $file = public_path() . '/excel/sales-report.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                $revenue = 0;
                $profit = 0;
                $cost = 0;
                foreach ($data as $key => $value) {
                    $revenue += $value['revenue'];
                    $profit += $value['profit'];
                    $cost += $value['cost'];
                    $sheet->row($key + 8, [
                        $value['date'],
                        $value['explain'],
                        $value['revenue'],
                        $value['profit'],
                        $value['cost'],
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
                }
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

                $sheet->cell('C' . (count($data) + 8), function ($cell) use ($revenue) {
                    $cell->setValue($revenue);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('D' . (count($data) + 8), function ($cell) use ($profit) {
                    $cell->setValue($profit);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('E' . (count($data) + 8), function ($cell) use ($cost) {
                    $cell->setValue($cost);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('B' . (count($data) + 10), function ($cell) {
                    $cell->setValue('Giám đốc');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('E' . (count($data) + 10), function ($cell) {
                    $cell->setValue('Kế Toán');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('B' . (count($data) + 16), function ($cell) {
                    $cell->setValue('JUNG SANG DEOK');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('E' . (count($data) + 16), function ($cell) {
                    $cell->setValue('Trần Thị Lan Thương');
                    $cell->setFontWeight('bold');
                });
            });
        })->download('xlsx');
    }

    public function getSalesByProductReport(Request $request)
    {
        $date = $request->query->get('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $product = $request->query('product_id');
        $query = Product::query();
        if ($product) {
            $query->whereIn('id', $product);
        }
        $query->with(['goodsDeliveryNotes' => function ($query) use ($date) {
            $query->whereType(1)->where('date', '>=', $date[0])->where('date', '<=', $date[1])->select('id', 'date', 'exchange_rate');
        }, 'goodsReceivedNotes' => function ($query) use ($date) {
            $query->whereType(1)->where('date', '>=', $date[0])->where('date', '<=', $date[1])->select('id', 'date', 'exchange_rate');
        }]);
        $data = $query->with('plastic:id,name')->select('*')->orderBy('symbol')->get();
        $data->each(function ($item) {
            $salesVolume = 0;
            $cost = 0;
            $revenue = 0;
            $purchaseVolume = 0;
            foreach ($item->goodsReceivedNotes as $grn) {
                $purchaseVolume += $grn->pivot->quantity;
            }
            foreach ($item->goodsDeliveryNotes as $gdn) {
                $salesVolume += $gdn->pivot->quantity;
                $cost += $gdn->pivot->quantity * $gdn->pivot->cost_price;
                $revenue += $gdn->pivot->quantity * $gdn->pivot->price * $gdn->exchange_rate;
            }
            $item['purchase_volume'] = $purchaseVolume;
            $item['sales_volume'] = $salesVolume;
            $item['cost'] = $cost;
            $item['revenue'] = round($revenue, 0);
            $item['profit'] = round($revenue - $cost, 0);
            unset($item['goodsDeliveryNotes']);
            unset($item['goodsReceivedNotes']);
        });
        if ($request->query('export')) {
            $this->exportSalesByProductReport($data);
        } else {
            return ['data' => $data];
        }
    }
    protected function exportSalesByProductReport($data)
    {
        $file = public_path() . '/excel/sales-by-product-report.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                $salesVolume = 0;
                $purchaseVolume = 0;
                $profit = 0;

                foreach ($data as $key => $value) {
                    $salesVolume += $value['sales_volume'];
                    $purchaseVolume += $value['purchase_volume'];
                    $profit += $value['profit'];

                    $sheet->row($key + 3, [
                        $value['plastic']['name'],
                        $value['name'],
                        $value['unit'],
                        $value['purchase_volume'],
                        $value['price'],
                        $value['sales_volume'],
                        $value['quantity'],
                        $value['profit'],
                    ]);
                    $sheet->cell('A' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('C' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('D' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('E' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('F' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('G' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('H' . ($key + 3), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }
                $sheet->cell('D' . 1, function ($cell) use ($purchaseVolume) {
                    $cell->setValue($purchaseVolume);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('F' . 1, function ($cell) use ($salesVolume) {
                    $cell->setValue($salesVolume);
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('H' . 1, function ($cell) use ($profit) {
                    $cell->setValue($profit);
                    $cell->setFontWeight('bold');
                });

            });
        })->download('xlsx');
    }

    protected function exportReport(Request $request)
    {
        $data = [];
        $file = public_path() . '/excel/Export_report.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                // $revenue = 0;
                // $profit = 0;
                // $cost = 0;
                // $quantity = 0;
                // foreach ($data as $key => $value) {
                //     $quantity += $value['total'];
                //     $revenue += $value['revenue'];
                //     $profit += $value['profit'];
                //     $cost += $value['cost'];
                //     $sheet->row($key + 3, [
                //         $value['symbol'],
                //         $value['name'],
                //         $value['total'],
                //         $value['revenue'],
                //         $value['profit'],
                //         $value['cost'],
                //     ]);
                //     $sheet->cell('A' . ($key + 3), function ($cell) {
                //         $cell->setBorder('thin', 'thin', 'thin', 'thin');
                //     });
                //     $sheet->cell('B' . ($key + 3), function ($cell) {
                //         $cell->setBorder('thin', 'thin', 'thin', 'thin');
                //     });
                //     $sheet->cell('C' . ($key + 3), function ($cell) {
                //         $cell->setBorder('thin', 'thin', 'thin', 'thin');
                //     });
                //     $sheet->cell('D' . ($key + 3), function ($cell) {
                //         $cell->setBorder('thin', 'thin', 'thin', 'thin');
                //     });
                //     $sheet->cell('E' . ($key + 3), function ($cell) {
                //         $cell->setBorder('thin', 'thin', 'thin', 'thin');
                //     });
                //     $sheet->cell('F' . ($key + 3), function ($cell) {
                //         $cell->setBorder('thin', 'thin', 'thin', 'thin');
                //     });
                // }
                // $sheet->cell('C' . 2, function ($cell) use ($quantity) {
                //     $cell->setValue($quantity);
                //     $cell->setFontWeight('bold');
                // });
                // $sheet->cell('D' . 2, function ($cell) use ($revenue) {
                //     $cell->setValue($revenue);
                //     $cell->setFontWeight('bold');
                // });
                // $sheet->cell('E' . 2, function ($cell) use ($profit) {
                //     $cell->setValue($profit);
                //     $cell->setFontWeight('bold');
                // });
                // $sheet->cell('F' . 2, function ($cell) use ($cost) {
                //     $cell->setValue($cost);
                //     $cell->setFontWeight('bold');
                // });
            });
        })->download('xlsx');
    }
}
