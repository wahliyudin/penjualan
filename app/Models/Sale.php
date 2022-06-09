<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tanggal',
        'no_faktur',
        'keterangan',
        'jumlah'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
