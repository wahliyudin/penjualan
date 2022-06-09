<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supply;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supply::with('product')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm"
        data-id="' . Crypt::encrypt($row->id) . '">Ubah</a> <a href="javascript:void(0)"
        class="delete btn btn-danger btn-sm" id="' . Crypt::encrypt($row->id) . '">Hapus</a>';
                    return $actionBtn;
                })
                ->addColumn('total', function ($row) {
                    return numberFormat($row->total, 'Rp.');
                })
                ->addColumn('product.harga', function ($row) {
                    return numberFormat($row->product->harga, 'Rp.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function updateOrCreate(Request $request)
    {
        try {
            if (!$request->id && $supply = Supply::where('product_id',$request->product_id)->first()) {
                $supply->update([
                    'stok' => $supply->stok + $request->stok,
                    'total' => $supply->total + replaceRupiah($request->total)
                ]);
            } else {
                Supply::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'product_id' => $request->product_id,
                        'stok' => $request->stok,
                        'total' => replaceRupiah($request->total),
                    ]
                );
            }
            return response()->json([
                'status' => 'success',
                'message' => isset($request->id) ? 'Ubah Data Persediaan' : 'Menambahkan data Persediaan',
            ]);
        } catch (\Exception $th) {
            $th->getCode() == 400 ? $code = 400 : $code = 500;
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], $code);
        }
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $supply = Supply::with('product')->find($id);
            if (!$supply) {
                throw new Exception('Data Persediaan tidak ditemukan!', 400);
            }
            $data = [
                'id' => $supply->id,
                'product_id' => $supply->product_id,
                'harga' => $supply->harga,
                'stok' => $supply->stok,
                'total' => $supply->total,
            ];
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Exception $th) {
            $th->getCode() == 400 ? $code = 400 : $code = 500;
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], $code);
        }
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $supply = Supply::find($id);

            if (!$supply) {
                throw new Exception('Data Persediaan tidak ditemukan!', 400);
            }
            $supply->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Menghapus data Persediaan',
            ]);
        } catch (\Exception $th) {
            $th->getCode() == 400 ? $code = 400 : $code = 500;
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], $code);
        }
    }
}
