<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Response;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name= $request->input('name');
        $description= $request->input('description');
        $ingredients= $request->input('ingredients');
        $price= $request->input('price');
        $rate= $request->input('rate');
        $tags= $request->input('tags');
        $categories= $request->input('categories');
        $price_from= $request->input('price_from');
        $price_to= $request->input('price_to');

  

         $foods = Food::with(['category','galleries'])->get();
         $foodResources = FoodResource::collection($foods);
         return $this->sendResponse($foodResources,'Data makanan berhasil didapatkan', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request , Id $id)
    {
        $id = $request->input('id');
        $food = Food::with(['category','galleries'])->find($id);
        if(!$food){
            return response()->json([
                'status' => 'error',
                'message' => 'food not found'
            ], 404);
        }
        $data = FoodResource::collection($food);
        return $this->sendResponse($data, 200);

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
    public function destroy($id)
    {
        //
    }
}
