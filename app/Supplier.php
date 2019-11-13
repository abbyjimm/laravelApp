<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable =['id','cantact', 'contact_tel'];

    public $timestamps = false;

    public function People()
    {
        return $this->belongsTo('App\People');
    }
}
