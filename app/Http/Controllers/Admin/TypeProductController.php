<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TypeProductController extends Controller
{
    public function index()
    {
        return view('admin.master-data.type-product.index', [
            'breadcrumbs' => [
                'title' => 'Tipe Barang',
                'path' => [
                    'Master Data' => route('admin.type-products.index'),
                    'Tipe Barang' => 0
                ]
            ]
        ]);
    }
}
