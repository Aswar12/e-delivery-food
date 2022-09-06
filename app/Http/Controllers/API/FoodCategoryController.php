<?php

namespace App\Http\Controllers\API;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\FoodCategory;

class FoodCategoryController extends Controller
{
    public function all(Request $request){
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name= $request->input('name');
        $show_product = $request->input('show_product');

        if($id){
            $category = FoodCategory::with(['foods',])->find($id);
            if($category){
                return $this->sendResponse([
                    'status' => 'success',
                    'data' => $category
                ], 'Data kategori berhasil diambil'); 
            }
            return $this->sendError([
                'status' => 'error',
                'message' => 'category not found'
            ], 404);
        } 

        $category = FoodCategory::query();

        if($name){
            $category->where('name', 'like', '%'.$name.'%');
        }

        if($show_product){
            $category->with(['foods']);

        }
        return $this->sendResponse([
            'status' => 'Data kategori berhasil diambil',
            'data' => $category->paginate($limit)
        ], 200);
    }
}
