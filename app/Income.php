<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = 'income';
    protected $fillable = [
            'idsupplier',
            'iduser',
            'type_voucher',
            'series_voucher',
            'num_voucher',
            'date_time',
            'tax',
            'total',
            'status'
    ];
    public function user()
    {
        return $this->belogsTo('App\User');
    }
    public function supplier()
    {
        return $this->belogsTo('App\Supplier');
    }
}
