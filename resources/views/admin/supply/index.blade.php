@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Persediaan</h3>
                        <button class="btn btn-sm btn-primary float-right" id="addNewSupply"><i class="fas fa-plus mr-2"></i>
                            Tambah
                            Data</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="supply" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Harga Barang</th>
                                    <th>Stok</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
    @include('admin.supply.modal')
@endsection
@include('layouts.inc.datatables')
@include('layouts.inc.toastr')
@include('layouts.inc.select2', [
    'attributes' => ['product_id'],
])
@include('layouts.inc.format-rupiah', [
    'attributes' => ['total'],
])
@push('script')
    <script type="text/javascript">
        var table;
        setTimeout(function() {
            tablesupply();
        }, 500);
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // function to retrieve DataTable server side
        function tablesupply() {
            $('#supply').dataTable().fnDestroy();
            table = $('#supply').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('api.supplies.index') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'product.nama',
                        name: 'product.nama'
                    },
                    {
                        data: 'product.harga',
                        name: 'product.harga'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 50, -1],
                    [10, 20, 50, 'All']
                ]
            });
        }

        $('#addNewSupply').click(function() {
            $('#addEditSupplyForm').trigger("reset");
            $("#id").val('');
            $('.modal-title').html("Tambah Persediaan");
            $('#Supply-modal').modal('show');
        });

        $('body').on('click', '.edit', function() {
            var id = $(this).data('id');

            // ajax
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}/api/supplies/" + id + "/edit",
                dataType: 'json',
                success: function(res) {
                    $('.modal-title').html("Edit Persediaan");
                    $('#Supply-modal').modal('show');
                    $('#id').val(res.data.id);
                    $('#stok').val(res.data.stok);
                    $('#total').val(formatRupiah(res.data.total, 'Rp.'));
                    $('#product_id').val(res.data.product_id).trigger('change');
                    $('#total').val(res.data.total);
                },
                error: ajaxError,
            });
        });

        $('body').on('click', '#btn-save', function(event) {
            var id = $("#id").val();
            var stok = $("#stok").val();
            var total = $("#total").val();
            var product_id = $("#product_id").val();
            $("#btn-save").html('Please Wait...');
            $("#btn-save").attr("disabled", true);

            // ajax
            $.ajax({
                type: "POST",
                url: "{{ route('api.supplies.update-or-create') }}",
                data: {
                    id: id,
                    stok: stok,
                    product_id: product_id,
                    total: total,
                },
                dataType: 'json',
                success: function(res) {
                    table.ajax.reload();
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    toastr.success(res.message, 'Berhasil!');
                    $('#Supply-modal').modal('hide');
                },
                error: ajaxError,
            });
        });

        // // delete
        $('body').on('click', '.delete', function(e) {
            e.preventDefault();
            deletesupply($(this).attr('id'))
        });

        function deletesupply(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data Persediaan akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus Sekarang!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/') }}/api/supplies/" + id + "/destroy",
                        type: 'DELETE',
                        success: function(resp) {
                            toastr.success(resp.message, 'Berhasil!');
                            table.ajax.reload();
                        },
                        error: ajaxError,
                    });
                }
            })
        }

        $('#product_id').change(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}/api/products/" + e.target.value + "/by-id",
                dataType: 'json',
                success: function(res) {
                    $('#harga').val(formatRupiah(String(res.data.harga), 'Rp.'));
                },
                error: ajaxError,
            });
        });

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
        }

        function replaceFormatRupiah(str) {
            return str.replace(new RegExp(escapeRegExp('.'), 'g'), '').replace('Rp ', '');
        }

        $('#stok').keyup(function(e) {
            var harga = replaceFormatRupiah($('#harga').val());
            $('#total').val(formatRupiah(String(parseInt(e.target.value) * parseInt(harga)), 'Rp.'));
        });
    </script>
@endpush
