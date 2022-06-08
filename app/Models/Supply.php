<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stok',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
