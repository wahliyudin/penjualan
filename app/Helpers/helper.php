<?php

use App\Models\Product;
use App\Models\Registration;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('numberFormat')) {
    function numberFormat($number, $prefix = null)
    {
        if (isset($prefix)) {
            return $prefix . ' ' . number_format($number, 0, ',', '.');
        }
        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('replaceRupiah')) {
    function replaceRupiah(string $rupiah)
    {
        $rupiah = Str::replace('Rp. ', '', $rupiah);
        return (int) Str::replace('.', '', $rupiah);
    }
}

if (!function_exists('generateNoFaktur')) {
    function generateNoFaktur()
    {
        $thnBulan = Carbon::now()->year . Carbon::now()->month;
        if (Sale::count() === 0) {
            return 'NF' . $thnBulan . '10000001';
        } else {
            return 'NF' . $thnBulan . (int) substr(Sale::get()->last()->no_faktur, -8) + 1;
        }
    }
}

if (!function_exists('generateCodeProduct')) {
    function generateCodeProduct()
    {
        $thnBulan = Carbon::now()->year . Carbon::now()->month;
        if (Product::count() === 0) {
            return $thnBulan . '10000001';
        } else {
            return $thnBulan . (int) substr(Product::get()->last()->kode, -8) + 1;
        }
    }
}
