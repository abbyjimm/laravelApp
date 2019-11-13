<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $fillable =[
        'idcategory', 'code', 'name', 'sale_price', 'stock', 'description', 'condition'
    ];
    public function category(){
        return $this->belongsToMany('App\Category');
    }
}
