<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailIncome extends Model
{
    protected $table = 'detail_income';
    protected $fillable = [
        'idincome',
        'idarticles',
        'quantity',
        'price'
    ];

    public $timestamps = false;
}
