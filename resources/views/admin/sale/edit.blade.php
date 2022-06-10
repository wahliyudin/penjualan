@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($sale->id) }}">
                            <div class="form-group col-6">
                                <label>Pelanggan</label>
                                <select name="customer_id" id="customer_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option selected="selected" disabled>-- pilih --</option>
                                    @foreach ($customers as $customer)
                                        <option {{ $sale->customer_id == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="no_faktur">No Faktur</label>
                                <input type="text" class="form-control" readonly value="{{ $sale->no_faktur }}"
                                    name="no_faktur" id="no_faktur" placeholder="No Faktur">
                                <span class="text-danger" id="no_fakturError"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Tanggal</label>
                                <div class="input-group date" id="tanggal" data-target-input="nearest">
                                    <input type="text" required name="tanggal" class="form-control datetimepicker-input"
                                        data-target="#tanggal" value="{{ \Carbon\Carbon::make($sale->tanggal)->format('d/m/Y') }}">
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" value="{{ $sale->keterangan }}" id="keterangan" placeholder="keterangan">
                                <span class="text-danger" id="keteranganError"></span>
                            </div>
                        </div>

                        <button class="btn btn-secondary add-input float-right mb-2">tambah</button>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="list">
                                @foreach ($sale->saleDetails as $saleDetail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="sale_detail_id[]" value="{{ Crypt::encrypt($saleDetail->id) }}">
                                            <div class="form-group">
                                                <select name="product_id[]" id="product_id" class="form-control select2"
                                                    style="width: 100%;">
                                                    <option selected="selected" disabled>-- pilih --</option>
                                                    @foreach ($products as $product)
                                                        <option {{ $saleDetail->product_id == $product->id ? 'selected' : '' }} value="{{ $product->id }}">
                                                            {{ $product->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" style="width: 80px;" value="{{ $saleDetail->product->supply->stok + $saleDetail->qty }}" class="form-control" readonly id="stok">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" value="{{ numberFormat($saleDetail->product->harga, 'Rp.') }}" style="width: 150px;" class="form-control" readonly id="harga">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" value="{{ $saleDetail->qty }}" style="width: 80px;" name="qty[]" class="form-control" id="qty">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" value="{{ numberFormat($saleDetail->total, 'Rp.') }}" style="width: 150px;" class="form-control" readonly name="total[]" id="total">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger remove">hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button class="btn btn-primary btn-save float-right">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('layouts.inc.toastr')
@include('layouts.inc.select2')
@include('layouts.inc.datetimepicker')
@push('script')
    <script type="text/javascript">
        var ajaxError = function(jqXHR, xhr, textStatus, errorThrow, exception) {
            if (jqXHR.status === 0) {
                toastr.error('Not connect.\n Verify Network.', 'Error!');
            } else if (jqXHR.status == 400) {
                toastr.warning(jqXHR['responseJSON'].message, 'Peringatan!');
            } else if (jqXHR.status == 404) {
                toastr.error('Requested page not found. [404]', 'Error!');
            } else if (jqXHR.status == 500) {
                toastr.error('Internal Server Error [500].' + jqXHR['responseJSON'].message, 'Error!');
            } else if (exception === 'parsererror') {
                toastr.error('Requested JSON parse failed.', 'Error!');
            } else if (exception === 'timeout') {
                toastr.error('Time out error.', 'Error!');
            } else if (exception === 'abort') {
                toastr.error('Ajax request aborted.', 'Error!');
            } else {
                toastr.error('Uncaught Error.\n' + jqXHR.responseText, 'Error!');
            }
        };
        $('.add-input').click(function(e) {
            e.preventDefault();
            $('#list').append(`
                <tr>
                    <td>
                        <div class="form-group">
                            <select name="product_id[]" id="product_id" class="form-control select2"
                                style="width: 100%;">
                                <option selected="selected" disabled>-- pilih --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" style="width: 80px;" class="form-control" readonly id="stok">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" style="width: 150px;" class="form-control" readonly id="harga">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" style="width: 80px;" name="qty[]" class="form-control" id="qty">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" style="width: 150px;" class="form-control" readonly name="total[]" id="total">
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-danger remove">hapus</button>
                    </td>
                </tr>
            `);
        });
        $('body').on('change', '#product_id', function(event) {
            var harga = $(event.target).parent().parent().parent().find('#harga');
            var stok = $(event.target).parent().parent().parent().find('#stok');
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}/api/products/" + event.target.value + "/by-id",
                dataType: 'json',
                success: function(res) {
                    harga.val(formatRupiah(String(res.data.harga), 'Rp.'));
                    stok.val(res.data.supply.stok);
                },
                error: ajaxError,
            });
        });

        $('body').on('change', '#qty', function(event) {
            if (parseInt(event.target.value) < 0) {
                $(this).val(0);
            }
            if (parseInt(event.target.value) > parseInt($($(event.target).parent().parent().parent().find('#stok')).val())) {
                $(this).val($($(event.target).parent().parent().parent().find('#stok')).val());
            }
            var harga = $($(event.target).parent().parent().parent().find('#harga')).val();
            $($(event.target).parent().parent().parent().find('#total')).val(formatRupiah(String(parseInt(replaceFormatRupiah(String(harga))) * parseInt(event.target.value)), 'Rp.'));
        });

        $('body').on('click', '.remove', function(event) {
            if (typeof $(event.target).parent().parent().find('input[name="sale_detail_id[]"]').val() === 'undefined') {
                $(event.target).parent().parent().remove();
            } else {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Record akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus Sekarang!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ url('/') }}/api/sales/" + $(event.target).parent().parent().find('input[name="sale_detail_id[]"]').val() + "/destroy-sale-detail",
                            type: 'DELETE',
                            success: function(resp) {
                                toastr.success(resp.message, 'Berhasil!');
                                location.reload();
                            },
                            error: ajaxError,
                        });
                    }
                })
            }

        });

        $(function() {
            $('#customer_id').select2()
        });
        $('#tanggal').datetimepicker({
            format: 'L'
        });

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
        }

        function replaceFormatRupiah(str) {
            return str.replace(new RegExp(escapeRegExp('.'), 'g'), '').replace('Rp ', '');
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $('body').on('click', '.btn-save', function(event) {
            var id = $('input[name="id"]').val();
            console.log(id);
            var customer_id = $("#customer_id").val();
            var no_faktur = $("#no_faktur").val();
            var tanggal = $('input[name="tanggal"]').val();
            var keterangan = $("#keterangan").val();
            var qtys = $("input[name='qty[]']").map(function(){
                return $(this).val();
            }).get();
            var product_ids = $("select[name='product_id[]']").map(function(){
                return $(this).val();
            }).get();
            var totals = $("input[name='total[]']").map(function(){
                return replaceFormatRupiah($(this).val());
            }).get();
            var sale_detail_ids = $("input[name='sale_detail_id[]']").map(function(){
                return $(this).val();
            }).get();
            $("#btn-save").html('Please Wait...');
            $("#btn-save").attr("disabled", true);

            // ajax
            $.ajax({
                type: "PUT",
                url: "{{ url('/') }}/api/sales/"+id+"/update",
                data: {
                    customer_id: customer_id,
                    no_faktur: no_faktur,
                    tanggal: tanggal,
                    keterangan: keterangan,
                    qtys: qtys,
                    sale_detail_ids: sale_detail_ids,
                    product_ids: product_ids,
                    totals: totals
                },
                dataType: 'json',
                success: function(res) {
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    toastr.success(res.message, 'Berhasil!');
                    window.location.reload()
                },
                error: ajaxError,
            });
        });
    </script>
@endpush
