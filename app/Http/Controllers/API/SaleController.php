<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Supply;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
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
                    $actionBtn = '<a href="' . route('admin.sales.edit', Crypt::encrypt($row->id)) . '"
                        class="btn btn-success btn-sm">Ubah</a> <a
                        href="javascript:void(0)"
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
                'total_bayar' => $request->total_bayar,
                'kembalian' => $request->kembalian,
                'jumlah' => array_sum($request->totals)
            ]);
            for ($i = 0; $i < count($request->product_ids); $i++) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $request->product_ids[$i],
                    'qty' => $request->qtys[$i],
                    'total' => $request->totals[$i]
                ]);
                $product = Product::with('supply')->find($request->product_ids[$i]);
                $stok = (int) $product->supply->stok - (int) $request->qtys[$i];
                $product->supply()->update([
                    'stok' => $stok,
                    'total' => $stok * $product->harga
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

    public function update(Request $request, $id)
    {
        try {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
            }
            $sale = Sale::find($id)->update([
                'customer_id' => $request->customer_id,
                'no_faktur' => $request->no_faktur,
                'tanggal' => Carbon::make($request->tanggal)->format('Y-m-d'),
                'keterangan' => $request->keterangan,
                'total_bayar' => $request->total_bayar,
                'kembalian' => $request->kembalian,
                'jumlah' => array_sum($request->totals)
            ]);
            for ($i = 0; $i < count($request->product_ids); $i++) {
                try {
                    $sale_detail_id = isset($request->sale_detail_ids[$i]) ? Crypt::decrypt($request->sale_detail_ids[$i]) : null;
                } catch (DecryptException $e) {

                }
                SaleDetail::updateOrCreate(
                    [
                        'id' => $sale_detail_id
                    ],
                    [
                        'sale_id' => $id,
                        'product_id' => $request->product_ids[$i],
                        'qty' => $request->qtys[$i],
                        'total' => $request->totals[$i]
                    ]
                );
                if (isset($request->sale_detail_ids[$i])) {
                    $product = Product::with('supply')->find($request->product_ids[$i]);
                    $stok = (int) $product->supply->stok - (int) $request->qtys[$i];
                    $product->supply()->update([
                        'stok' => $stok,
                        'total' => $stok * $product->harga
                    ]);
                }
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
            foreach ($sale->saleDetails as $item) {
                $product = Product::with('supply')->find($item->product_id);
                $stok = $product->supply->stok + $item->qty;
                $product->supply()->update([
                    'stok' => $stok,
                    'total' => $stok * $product->harga
                ]);
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

    public function destroySaleDetail($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $sale_detail = SaleDetail::with('product')->find($id);

            if (!$sale_detail) {
                throw new Exception('Data Penjualan tidak ditemukan!', 400);
            }
            $stok = $sale_detail->product->supply->stok + $sale_detail->qty;
            $sale_detail->product->supply->update([
                'stok' => $stok,
                'total' => $stok * $sale_detail->product->harga
            ]);
            $sale_detail->delete();
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
