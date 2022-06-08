@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Rekening</h3>
                        <button class="btn btn-sm btn-primary float-right" id="addNewAccount"><i class="fas fa-plus mr-2"></i>
                            Tambah
                            Data</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="account" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Klasipikasi</th>
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
    @include('admin.master-data.account.modal')
@endsection
@include('layouts.inc.datatables')
@include('layouts.inc.toastr')
@include('layouts.inc.select2')
@push('script')
    <script type="text/javascript">
        var table;
        setTimeout(function() {
            tableaccount();
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
        function tableaccount() {
            $('#account').dataTable().fnDestroy();
            table = $('#account').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('api.accounts.index') }}",
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
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'classification.nama',
                        name: 'classification.nama'
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

        $('#addNewAccount').click(function() {
            $('#addEditAccountForm').trigger("reset");
            $("#id").val('');
            $("#kode").val('');
            $("#nama").val('');
            $('.modal-title').html("Tambah Rekening");
            $('#Account-modal').modal('show');
        });

        $('body').on('click', '.edit', function() {
            var id = $(this).data('id');

            // ajax
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}/api/accounts/" + id + "/edit",
                dataType: 'json',
                success: function(res) {
                    $('.modal-title').html("Edit Rekening");
                    $('#Account-modal').modal('show');
                    $('#id').val(res.data.id);
                    $('#nama').val(res.data.nama);
                    $('#kode').val(res.data.kode);
                    $('#classification_id').val(res.data.classification_id).trigger(
                        'change.datetimepicker');
                },
                error: ajaxError,
            });
        });

        $('body').on('click', '#btn-save', function(event) {
            var id = $("#id").val();
            var nama = $("#nama").val();
            var kode = $("#kode").val();
            var classification_id = $("#classification_id").val();
            $("#btn-save").html('Please Wait...');
            $("#btn-save").attr("disabled", true);

            // ajax
            $.ajax({
                type: "POST",
                url: "{{ route('api.accounts.update-or-create') }}",
                data: {
                    id: id,
                    nama: nama,
                    kode: kode,
                    classification_id: classification_id,
                },
                dataType: 'json',
                success: function(res) {
                    table.ajax.reload();
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    toastr.success(res.message, 'Berhasil!');
                    $('#Account-modal').modal('hide');
                },
                error: ajaxError,
            });
        });

        // // delete
        $('body').on('click', '.delete', function(e) {
            e.preventDefault();
            deleteaccount($(this).attr('id'))
        });

        function deleteaccount(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data Rekening akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus Sekarang!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/') }}/api/accounts/" + id + "/destroy",
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

        $(function() {
            bsCustomFileInput.init();
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>
@endpush
