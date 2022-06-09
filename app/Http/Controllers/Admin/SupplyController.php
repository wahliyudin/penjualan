<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        return view('admin.supply.index', [
            'breadcrumbs' => [
                'title' => 'Data Persediaan',
                'path' => [
                    'Data Persediaan' => 0
                ]
            ],
            'products' => Product::latest()->get(['id', 'nama'])
        ]);
    }
}
