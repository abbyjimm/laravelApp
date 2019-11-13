<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $fillable =[
        'name', 'type_document', 'num_document', 'address', 'email',
    ];

    public function Supplier()
    {
        return $this->hasOne('App\Supplier');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
