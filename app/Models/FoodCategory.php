<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodCategory extends Model
{
    use HasFactory, SoftDeletes ;

    protected $fillable = [
        'name',
    ];

    public function foods(){
         return $this->hasMany(Food::class, 'categories_id', 'id');
        }
}
