<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;



    protected $fillable = [
        'transactions_id',
        'food_id',
        'quantity',
    ];



    public function food(){
        return $this->hasOne(Food::class, 'id', 'food_id');
    }
}
