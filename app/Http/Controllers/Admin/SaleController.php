<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return view('admin.sale.index', [
            'breadcrumbs' => [
                'title' => 'Data Penjualan',
                'path' => [
                    'Data Penjualan' => 0
                ]
            ]
        ]);
    }

    public function create()
    {
        return view('admin.sale.create', [
            'breadcrumbs' => [
                'title' => 'Penjualan',
                'path' => [
                    'Penjualan' => 0
                ]
                ],
                'customers' => Customer::latest()->get(['id', 'nama']),
                'products' => Product::latest()->get(['id', 'nama']),
                'no_faktur' => generateNoFaktur()
        ]);
    }
}
