<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodCategoryRequest;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = FoodCategory::paginate(10);
           return view('category.index', ['category'=> $category]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodCategoryRequest $request)
    {
        $data = $request->all();
        FoodCategory::create($data);
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FoodCategory $foodCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FoodCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodCategory $category)
    {
        return view('category.edit', ['item'=> $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(FoodCategoryRequest $request, FoodCategory $category)
    {
       $data = request()->all();
         $category->update($data);
         return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodCategory $category)
    {
        $category->delete();
        return redirect()->route('category.index');
    }
}
