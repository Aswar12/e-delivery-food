<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodGalleryRequest;
use App\Models\Food;
use App\Models\FoodGallery;
use Illuminate\Http\Request;

class FoodGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Food $food)
    {
        $gallery = FoodGallery::where('food_id', $food->id)->get();
        return view('gallery.index',[
            'food' => $food,
            'gallery'=>$gallery
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Food $food)
    {
        return view('gallery.create',[
            'food' => $food
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodGalleryRequest $request, Food $food)
    {
        $files = $request->file('files');

        if($request->hasFile('files'))
        {
            foreach ($files as $file) {
                $path = $file->store('public/gallery');

                FoodGallery::create([
                    'food_id' => $food->id,
                    'url' => $path
                ]);
            }
        }

        return redirect()->route('food.gallery.index', $food->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodGallery $foodGallery , Food $food)
    {
        FoodGallery::where('id', $foodGallery)->delete();
        return redirect()->route('food.gallery.index', $foodGallery->id );
    }
}
