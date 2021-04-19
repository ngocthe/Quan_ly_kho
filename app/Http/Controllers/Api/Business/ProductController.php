<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachmentRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $all = $request->query('all');
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $project = $request->query('project_id');
        $plastic = $request->query('plastic_id');
        $query = Product::query();
        if ($request->query('all')) {
            return ['data' => $query->select('id', 'name', 'price', 'plastic_id')->orderBy('name')->get()];
        }

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('symbol', 'ilike', '%' . $search . '%');
                $query->orWhere('name', 'ilike', '%' . $search . '%');
            });
        }

        if (isset($project)) {
            $query->whereIn('project_id', $project);
        }

        if (isset($plastic)) {
            $query->whereIn('plastic_id', $plastic);
        }
        $query->with('project:id,name', 'plastic:id,name')->orderBy('symbol')->orderBy('name');
        if ($request->query('export')) {
            return $this->export($query->get());
        }

        return ProductResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        Product::create(Arr::add($request->all(), 'symbol', Category::find($request->plastic_id)->name));
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();
            if ($product->price != $request->price) {
                $product->priceChanges()->create([
                    'old_price' => $product->price,
                    'new_price' => $request->price,
                    'user_id' => Auth::user()->id,
                ]);
            }
            $product->update($request->all());
            $product->update(['symbol' => $product->plastic->name]);
            DB::commit();
            return Response::updated();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::error($e->getMessage());
        }

        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return Helper::delete($product);
    }
    public function export($data)
    {
        $file = public_path() . '/excel/product.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                foreach ($data as $key => $value) {
                    $sheet->row($key + 2, [
                        $value['plastic']['name'],
                        $value['project']['name'],
                        $value['name'],
                        $value['unit'],
                        $value['price'],
                        $value['quantity'],
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

                }
            });
        })->download('xlsx');
    }
    public function attach(AttachmentRequest $request, Product $product)
    {
        $type = $request->type;
        $file = $request->file('file');
        $attachment = $product->attachment;
        $path = Storage::putFileAs(
            'public/attachment',
            $file,
            str_replace('/', '_', $product->name . '_' . $type . '.pdf')
        );
        $attachment[$type] = Storage::url($path);
        $product->attachment = $attachment;
        $product->save();
        return Response::updated();
    }
    public function getPriceChanges(Product $product)
    {
        return ['data' => $product->priceChanges()->with('user')->latest()->get()];
    }
}
