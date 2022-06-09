<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sale::with('customer')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm"
        data-id="' . Crypt::encrypt($row->id) . '">Ubah</a> <a href="javascript:void(0)"
        class="delete btn btn-danger btn-sm" id="' . Crypt::encrypt($row->id) . '">Hapus</a>';
                    return $actionBtn;
                })
                ->addColumn('jumlah', function ($row) {
                    return numberFormat($row->jumlah, 'Rp.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'no_faktur' => $request->no_faktur,
                'tanggal' => Carbon::make($request->tanggal)->format('Y-m-d'),
                'keterangan' => $request->keterangan,
                'jumlah' => array_sum($request->totals)
            ]);
            for ($i = 0; $i < count($request->product_ids); $i++) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $request->product_ids[$i],
                    'qty' => $request->qtys[$i],
                    'total' => $request->totals[$i]
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Menambahkan data Penjualan',
            ]);
        } catch (\Throwable $th) {
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
            $sale = Sale::find($id);

            if (!$sale) {
                throw new Exception('Data Penjualan tidak ditemukan!', 400);
            }
            $sale->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Menghapus data Penjualan',
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
