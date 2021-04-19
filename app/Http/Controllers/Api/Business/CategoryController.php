<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $all = $request->query('all');
        $root = $request->query('category_id', 1);
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $query = Category::where('category_id', $root);
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', '%' . $search . '%');
                $query->orWhere('description', 'ilike', '%' . $search . '%');
            });
        }
        $query->orderBy('name');
        return CategoryResource::collection($all ? $query->get() : $query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (Category::where('category_id', $request->category_id)->whereName($request->name)->first())
            return Response::error('The name already exists.');
        Category::create($request->all());
        return Response::created();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        if (Category::where('category_id', $request->category_id)
            ->whereName($request->name)->where('id', '<>', $request->id)->first()
        )
            return Response::error('The name already exists.');
        $category->update($request->all());
        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        return Helper::delete($category);
    }
    public function listCategories(Request $request)
    {
        $root = $request->query('root');
        $parent = $request->query('parent');
        return ['data' => Category::where([
            ['category_id', null],
            ['name', $root],
        ])->first()->categories()->where('name', $parent)->first()->categories()->select('id', 'name')->get()];
    }
}
