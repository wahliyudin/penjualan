<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customer.index', [
            'breadcrumbs' => [
                'title' => 'Pelanggan',
                'path' => [
                    'Master Data' => route('admin.customers.index'),
                    'Pelanggan' => 0
                ]
            ]
        ]);
    }
}
