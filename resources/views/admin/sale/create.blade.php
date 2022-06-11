@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Pelanggan</label>
                                <select name="customer_id" id="customer_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option selected="selected" disabled>-- pilih --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="no_faktur">No Faktur</label>
                                <input type="text" class="form-control" readonly value="{{ $no_faktur }}"
                                    name="no_faktur" id="no_faktur" placeholder="No Faktur">
                                <span class="text-danger" id="no_fakturError"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Tanggal</label>
                                <div class="input-group date" id="tanggal" data-target-input="nearest">
                                    <input type="text" required name="tanggal" class="form-control datetimepicker-input"
                                        data-target="#tanggal" value="">
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="keterangan">
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

                            </tbody>
                        </table>

                        <div class="row mt-4 justify-content-end pr-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grand_total">Total</label>
                                    <input type="text" class="form-control" readonly value="Rp. 0" name="grand_total" id="grand_total" placeholder="Total">
                                    <span class="text-danger" id="totalError"></span>
                                </div>
                                <div class="form-group">
                                    <label for="total_bayar">Jumlah Bayar</label>
                                    <input type="text" class="form-control" name="total_bayar" id="total_bayar" placeholder="Jumlah Bayar">
                                    <span class="text-danger" id="total_bayarError"></span>
                                </div>
                                <div class="form-group">
                                    <label for="kembalian">Kembalian</label>
                                    <input type="text" class="form-control" readonly name="kembalian" id="kembalian" placeholder="Kembalian">
                                    <span class="text-danger" id="kembalianError"></span>
                                </div>
                            </div>
                        </div>
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
            if (parseInt(event.target.value) > parseInt($('#stok').val())) {
                $(this).val($('#stok').val());
            }
            var harga = $($(event.target).parent().parent().parent().find('#harga')).val();
            $($(event.target).parent().parent().parent().find('#total')).val(formatRupiah(String(parseInt(replaceFormatRupiah(String(harga))) * parseInt(event.target.value)), 'Rp.'));

            var sum = 0;
            $.each($('#total*'), function (indexInArray, valueOfElement) {
                sum += parseInt(replaceFormatRupiah(String(valueOfElement.value)))
            });
            $('#grand_total').val(formatRupiah(String(sum), 'Rp.'));
            generateKembalian()
        });

        $('body').on('click', '.remove', function(event) {
            $(event.target).parent().parent().remove();
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

        $('#total_bayar').keyup(function (e) {
            $('#total_bayar').val(formatRupiah(this.value, 'Rp.'));
            generateKembalian()
        });

        function generateKembalian() {
            var kembalian = parseInt(replaceFormatRupiah($('#total_bayar').val())) - parseInt(replaceFormatRupiah($(
                '#grand_total').val()));
            var a = parseInt(replaceFormatRupiah($('#grand_total').val()));
            var b = parseInt(replaceFormatRupiah($('#total_bayar').val()));
            var c = parseInt(replaceFormatRupiah($('#kembalian').val()));
            if (a >= b) {
                $('#kembalian').val('Rp. 0');
            } else {
                $('#kembalian').val(formatRupiah(String(kembalian), 'Rp. '));
            }
        }

        $('body').on('click', '.btn-save', function(event) {
            if (parseInt(replaceFormatRupiah($('#total_bayar').val())) < parseInt(replaceFormatRupiah($('#grand_total').val()))) {
                toastr.warning('Jumkah Bayar Kurang!', 'Peringatan!');
            } else {
                var customer_id = $("#customer_id").val();
                var no_faktur = $("#no_faktur").val();
                var tanggal = $('input[name="tanggal"]').val();
                var keterangan = $("#keterangan").val();
                var total_bayar = replaceFormatRupiah($("#total_bayar").val());
                var kembalian = replaceFormatRupiah($("#kembalian").val());
                var qtys = $("input[name='qty[]']").map(function(){
                    return $(this).val();
                }).get();
                var product_ids = $("select[name='product_id[]']").map(function(){
                    return $(this).val();
                }).get();
                var totals = $("input[name='total[]']").map(function(){
                    return replaceFormatRupiah($(this).val());
                }).get();
                $("#btn-save").html('Please Wait...');
                $("#btn-save").attr("disabled", true);

                // ajax
                $.ajax({
                    type: "POST",
                    url: "{{ route('api.sales.store') }}",
                    data: {
                        customer_id: customer_id,
                        no_faktur: no_faktur,
                        tanggal: tanggal,
                        keterangan: keterangan,
                        total_bayar: total_bayar,
                        kembalian: kembalian,
                        qtys: qtys,
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
            }
        });
    </script>
@endpush
