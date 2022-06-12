<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with('typeProduct')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="d-flex align-items-center"><a href="javascript:void(0)" class="edit btn btn-success btn-sm mr-2"
        data-id="' . Crypt::encrypt($row->id) . '">Ubah</a> <a href="javascript:void(0)"
        class="delete btn btn-danger btn-sm" id="' . Crypt::encrypt($row->id) . '">Hapus</a></div>';
                    return $actionBtn;
                })
                ->addColumn('harga', function($row){
                    return numberFormat($row->harga,'Rp.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function updateOrCreate(Request $request)
    {
        try {
            if (isset($request->id)) {
                $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate($request->kode);
                $name_qrcode = time() . '.png';
                Storage::delete(Str::replaceFirst('storage', 'public', $request->qrcode));
                Storage::disk('local')->put('public/images/products/qr-code/'.$name_qrcode, $image);
            } else {
                $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate($request->kode);
                $name_qrcode = time() . '.png';
                Storage::disk('local')->put('public/images/products/qr-code/'.$name_qrcode, $image);
            }

            Product::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'type_product_id' => $request->type_product_id,
                    'qrcode' => 'storage/images/products/qr-code/'. $name_qrcode,
                    'harga' => replaceRupiah($request->harga),
                ]
            );
            return response()->json([
                'status' => 'success',
                'message' => isset($request->id) ? 'Ubah Data Barang' : 'Menambahkan data Barang',
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
            $product = Product::find($id);
            if (!$product) {
                throw new Exception('Data Barang tidak ditemukan!', 400);
            }
            $data = [
                'id' => $product->id,
                'nama' => $product->nama,
                'kode' => $product->kode,
                'type_product_id' => $product->type_product_id,
                'harga' => $product->harga,
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

    public function byId($id)
    {
        try {
            $product = Product::with('supply')->find($id);
            if (!$product) {
                throw new Exception('Data Barang tidak ditemukan!', 400);
            }
            return response()->json([
                'status' => 'success',
                'data' => $product,
            ]);
        } catch (\Exception $th) {
            $th->getCode() == 400 ? $code = 400 : $code = 500;
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], $code);
        }
    }

    public function byKode($kode)
    {
        try {
            $product = Product::with('supply')->where('kode', $kode)->first();
            if (!$product) {
                throw new Exception('Data Barang tidak ditemukan!', 400);
            }
            return response()->json([
                'status' => 'success',
                'data' => $product,
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
            $product = Product::find($id);

            if (!$product) {
                throw new Exception('Data Barang tidak ditemukan!', 400);
            }
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Menghapus data Barang',
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
