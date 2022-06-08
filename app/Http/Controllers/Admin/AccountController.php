<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return view('admin.master-data.account.index', [
            'breadcrumbs' => [
                'title' => 'Data Rekening',
                'path' => [
                    'Master Data' => route('admin.accounts.index'),
                    'Data Rekening' => 0
                ]
            ],
            'classifications' => Classification::latest()->get()
        ]);
    }
}
