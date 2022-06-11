@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $jumlah_pelanggan }}</h3>

                        <p>Jumlah Pelanggan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $jumlah_barang }}</h3>

                        <p>Jumlah Barang</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ numberFormat($jumlah_pendapatan, 'Rp.') }}</h3>

                        <p>Jumlah Pendapatan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <table id="sale" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nomor Transaksi</th>
                                <th>Keterangan</th>
                                <th>Jumlah Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::make($sale->tanggal)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $sale->no_faktur }}</td>
                                    <td>{{ $sale->keterangan }}</td>
                                    <td>{{ numberFormat($sale->jumlah, 'Rp.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@endsection
@include('layouts.inc.datatables')
@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function() {
            $("#sale").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["pdf", "print"]
            }).buttons().container().appendTo('#sale_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
