<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index()
    {
        return view('admin.master-data.classification.index', [
            'breadcrumbs' => [
                'title' => 'Klasifikasi',
                'path' => [
                    'Master Data' => route('admin.classifications.index'),
                    'Klasifikasi' => 0
                ]
            ]
        ]);
    }
}
