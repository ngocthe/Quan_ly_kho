<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Http\Resources\PartnerResource;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Import;
use App\Models\Partner;
use App\Models\Product;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class PartnerController extends Controller
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
        $active = $request->query('active');
        $type = $request->query('type');
        $query = Partner::query();
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', '%' . $search . '%');
                $query->orWhere('code', 'ilike', '%' . $search . '%');
                $query->orWhere('address', 'ilike', '%' . $search . '%');
                $query->orWhere('tax_code', 'ilike', '%' . $search . '%');
                $query->orWhere('phone_number', 'ilike', '%' . $search . '%');
                $query->orWhere('pic', 'ilike', '%' . $search . '%');
                $query->orWhere('main_product', 'ilike', '%' . $search . '%');
            });
        }

        if (isset($active)) {
            $query->whereActive($active);
        }

        if (isset($type)) {
            $query->whereType($type);
        }
        $query->orderBy('code');
        if ($request->query('export')) {
            $data = $query->get();
            foreach($data as $it){
                $data->debt=Debt::where('reference_id',$it->id)->sum(DB::raw('amount_owed * exchange_rate -amount_payment'));
            }
            return $this->export($data);
        }

        return PartnerResource::collection($request->list ?  $query->get() : $query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerRequest $request)
    {
        Partner::create($request->all());
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerRequest $request, Partner $partner)
    {
        $partner->update($request->all());
        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        return Helper::delete($partner);
    }

    public function export($data)
    {
        $file = public_path() . '/excel/partner.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                foreach ($data as $key => $value) {
                    $sheet->row($key + 2, [
                        $key + 1,
                        $value['code'],
                        $value['name'],
                        $value['address'],
                        $value['tax_code'],
                        $value['phone_number'],
                        $value['pic'],
                        $value['main_product'],
                        $value['type'] === 'C' ? 'KHÁCH HÀNG' : 'NCC',
                    ]);
                    $sheet->cell('A' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('C' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('D' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('E' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('F' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('G' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('H' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('I' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }
            });
        })->download('xlsx');
    }
    public function import()
    {
        DB::beginTransaction();
        try {
            $this->importPartners();
            $this->importProducts();
            $this->importProductPrice();
            $this->importImports();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }
    }
    public function importPartners()
    {
        $file = public_path() . '/excel/import-partner.xlsx';
        $results = [];
        \Excel::load($file, function ($reader) use (&$results) {
            $results = $reader->get();
        });
        $results->each(function ($item) {
            unset($item['0']);
            $item->created_at = Carbon::now();
            $item->updated_at = Carbon::now();
            $item['phone_number'] = str_replace(' ', '', $item['phone_number']);
        });
        DB::table('partners')->insert($results->toArray());
        return $results;
    }
    public function importProducts()
    {
        $file = public_path() . '/excel/import-product.xlsx';
        $results = [];
        \Excel::load($file, function ($reader) use (&$results) {
            $results = $reader->get();
        });
        $results->each(function ($item) {
            $project = Category::firstOrCreate([
                'category_id' => 1,
                'name' => $item['project'],
            ]);
            $plastic = Category::firstOrCreate([
                'category_id' => 2,
                'name' => $item['plastic'],
            ]);
            Product::create([
                'project_id' => $project->id,
                'plastic_id' => $plastic->id,
                'symbol' => $item['symbol'],
                'name' => $item['name'],
                'unit' => $item['unit'],
                'code' => $item['code'],
            ]);
        });
        //DB::table('partners')->insert($results->toArray());
        return $results;
    }
    public function importProductPrice()
    {
        $file = public_path() . '/excel/import-product-price.xlsx';
        $results = [];
        \Excel::load($file, function ($reader) use (&$results) {
            $results = $reader->get();
        });
        $results->each(function ($item) {
            $item['price'] = $item['price'] * 1000;
            $product = Product::whereSymbol($item['symbol'])->first();
            if ($product) {
                $product->update(['price' => $item['price'] ? $item['price'] : 0]);
            }
        });
        return $results;
    }
    public function importImports()
    {
        $file = public_path() . '/excel/import-import.xlsx';
        $results = [];
        \Excel::load($file, function ($reader) use (&$results) {
            $results = $reader->get();
        });
        $source1 = Category::whereName('HQ')->first();
        $source2 = Category::whereName('VN')->first();
        $paymentTerm1 = Category::whereCode('credit')->first();
        $paymentTerm2 = Category::whereCode('advanced')->first();
        $results->each(function ($item) use ($source1, $source2, $paymentTerm1, $paymentTerm2) {
            $item['etd'] = substr($item['etd'], 0, 10);
            $item['eta'] = substr($item['eta'], 0, 10);
            $item['payment_date'] = substr($item['payment_date'], 0, 10);
            $product = Product::whereName($item['product'])->first();
            if ($product) {
                Import::create([
                    'etd' => $item['etd'],
                    'eta' => $item['eta'],
                    'term' => $item['term'],
                    'source_id' => $item['source'] == 'HQ' ? $source1->id : $source2->id,
                    'customer_id' => Partner::whereType('C')->inRandomOrder()->first()->id,
                    'supplier_id' => Partner::whereType('S')->inRandomOrder()->first()->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'unit' => '$',
                    'payment_term_id' => $item['payment_term'] === "By SD's credit" ? $paymentTerm1->id : $paymentTerm2->id,
                    'payment_date' => $item['payment_date'] ? $item['payment_date'] : null,
                ]);
            }
        });
        return $results;
    }
}
