<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class ClassificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Classification::oldest()->get();
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
            Classification::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'nama' => $request->nama
                ]
            );
            return response()->json([
                'status' => 'success',
                'message' => isset($request->id) ? 'Ubah Data klasifikasi' : 'Menambahkan data klasifikasi',
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
            $classification = Classification::find($id);
            if (!$classification) {
                throw new Exception('Data klasifikasi tidak ditemukan!', 400);
            }
            $data = [
                'id' => $classification->id,
                'nama' => $classification->nama
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
            $classification = Classification::find($id);

            if (!$classification) {
                throw new Exception('Data klasifikasi tidak ditemukan!', 400);
            }
            $classification->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Menghapus data klasifikasi',
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
