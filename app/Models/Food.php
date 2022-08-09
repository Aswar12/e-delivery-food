<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'ingredients' ,'price', 'rate', 'tags', 'categories_id', 'types'
    ];

    public function getCreatedAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    } 

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function toArray()
    {
        $toArray = parent::toArray();
       
    }

    public function galleries(){
        return $this->hasMany(FoodGallery::class, 'food_id', 'id');
    }

    public function category(){
        return $this->belongsTo(FoodCategory::class, 'categories_id', 'id');
    }
}
