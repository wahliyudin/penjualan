<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TypeProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class TypeProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TypeProduct::oldest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm"
        data-id="' . Crypt::encrypt($row->id) . '">Ubah</a> <a href="javascript:void(0)"
        class="delete btn btn-danger btn-sm" id="' . Crypt::encrypt($row->id) . '">Hapus</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function updateOrCreate(Request $request)
    {
        try {
            TypeProduct::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'nama' => $request->nama
                ]
            );
            return response()->json([
                'status' => 'success',
                'message' => isset($request->id) ? 'Ubah Data tipe Barang' : 'Menambahkan data tipe Barang',
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
            $type_product = TypeProduct::find($id);
            if (!$type_product) {
                throw new Exception('Data tipe Barang tidak ditemukan!', 400);
            }
            $data = [
                'id' => $type_product->id,
                'nama' => $type_product->nama
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
            $type_product = TypeProduct::find($id);

            if (!$type_product) {
                throw new Exception('Data Tipe Barang tidak ditemukan!', 400);
            }
            $type_product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Menghapus data Tipe Barang',
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
