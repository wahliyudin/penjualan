<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
        return view('admin.sale.create-by-qrcode', [
            'breadcrumbs' => [
                'title' => 'Penjualan',
                'path' => [
                    'Penjualan' => 0
                ]
            ],
            'customers' => Customer::latest()->get(['id', 'nama']),
            'products' => Product::latest()->get(['id', 'nama']),
            'no_faktur' => generateNoFaktur(),
            'account_cashs' => Account::where('classification_id', 1)->get(),
            'account_sales' => Account::where('classification_id', 2)->get()
        ]);
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {

        }
        return view('admin.sale.edit', [
            'breadcrumbs' => [
                'title' => 'Penjualan',
                'path' => [
                    'Penjualan' => 0
                ]
            ],
            'customers' => Customer::latest()->get(['id', 'nama']),
            'products' => Product::latest()->get(['id', 'nama']),
            'sale' => Sale::with('saleDetails', 'saleDetails.product', 'saleDetails.product.supply')->find($id)
        ]);
    }

    public function cetakStruk($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {

        }
        $sale = Sale::with('saleDetails', 'saleDetails.product', 'customer')->find($id);
        $pdf = Pdf::loadView('admin.exports.struk', compact('sale'));
        return $pdf->setPaper('A4')->stream();
    }
}
