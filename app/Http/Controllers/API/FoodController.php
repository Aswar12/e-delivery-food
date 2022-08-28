<?php

namespace App\Http\Controllers\API;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends Controller
{
    public function all(Request $request){

        $id = $request->input('id');
        $limit = $request->input('limit',6);
        $name= $request->input('name');
        $description = $request->input('description');
        $price = $request->input('price');
        $tags = $request->input('tags');
        $categories = $request->input('categories'); 

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');    

        $rate_from = $request->input('rate_from');
        $rate_to = $request->input('rate_to');

        if($id){
            $food = Food::with(['category', 'galleries'])->find($id);

        
            if($food){
                return ResponseFormatter::success($food,'Food found', 200);
            }
            return ResponseFormatter::error(null , 'Data produk tidak ada', 404);
        } 


        $food = Food::with(['category', 'galleries']);

        if($name){
            $food->where('name', 'like', '%'.$name.'%');
        }
        
        if($description){
            $food->where('description', 'like', '%'.$description.'%');
        }

        if($price_from){
            $food->where('price', '>=', $price_from);
        }
        if($price_to){
            $food->where('price', '<=', $price_to);
        }
        if($rate_from){
            $food->where('rate', '>=', $rate_from);
        }
        if($rate_to){
            $food->where('rate', '<=', $rate_to);
        }
        if($categories){
            $food->where('categories', 'like', '%'.$categories.'%');
        }

        return  ResponseFormatter::success($food,'Food found', 200);

        
    }
}
