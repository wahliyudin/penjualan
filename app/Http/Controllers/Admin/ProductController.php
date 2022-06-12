<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index', [
            'breadcrumbs' => [
                'title' => 'Data Barang',
                'path' => [
                    'Data Barang' => 0
                ]
            ],
            'type_products' => TypeProduct::latest()->get(['id', 'nama']),
        ]);
    }
}
