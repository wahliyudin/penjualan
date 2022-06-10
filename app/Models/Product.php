<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
        'type_product_id'
    ];

    public function typeProduct()
    {
        return $this->belongsTo(TypeProduct::class);
    }

    public function supply()
    {
        return $this->hasOne(Supply::class);
    }
}
