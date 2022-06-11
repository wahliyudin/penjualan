<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'breadcrumbs' => [
                'title' => 'Dashboard',
                'path' => [
                    'Dashboard' => 0
                ]
            ],
            'jumlah_pelanggan' => Customer::count(),
            'jumlah_barang' => Product::count(),
            'jumlah_pendapatan' => Sale::sum('jumlah'),
            'sales' => Sale::latest()->get()
        ]);
    }
}
