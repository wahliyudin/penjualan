<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'classification_id'
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
}
