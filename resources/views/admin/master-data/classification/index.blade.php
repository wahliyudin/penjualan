@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Klasipikasi</h3>
                        <button class="btn btn-sm btn-primary float-right" id="addNewClassification"><i
                                class="fas fa-plus mr-2"></i>
                            Tambah
                            Data</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="classification" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
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
    @include('admin.master-data.classification.modal')
@endsection
@include('layouts.inc.datatables')
@include('layouts.inc.toastr')
@push('script')
    <script type="text/javascript">
        var table;
        setTimeout(function() {
            tableclassification();
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
        function tableclassification() {
            $('#classification').dataTable().fnDestroy();
            table = $('#classification').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('api.classifications.index') }}",
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
                        data: 'nama',
                        name: 'nama'
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

        $('#addNewClassification').click(function() {
            $('#addEditClassificationForm').trigger("reset");
            $("#id").val('');
            $('.modal-title').html("Tambah Klasipikasi");
            $('#Classification-modal').modal('show');
        });

        $('body').on('click', '.edit', function() {
            var id = $(this).data('id');

            // ajax
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}/api/classifications/" + id + "/edit",
                dataType: 'json',
                success: function(res) {
                    $('.modal-title').html("Edit Klasipikasi");
                    $('#Classification-modal').modal('show');
                    $('#id').val(res.data.id);
                    $('#nama').val(res.data.nama);
                },
                error: ajaxError,
            });
        });

        $('body').on('click', '#btn-save', function(event) {
            var id = $("#id").val();
            var nama = $("#nama").val();
            $("#btn-save").html('Please Wait...');
            $("#btn-save").attr("disabled", true);

            // ajax
            $.ajax({
                type: "POST",
                url: "{{ route('api.classifications.update-or-create') }}",
                data: {
                    id: id,
                    nama: nama
                },
                dataType: 'json',
                success: function(res) {
                    table.ajax.reload();
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    toastr.success(res.message, 'Berhasil!');
                    $('#Classification-modal').modal('hide');
                },
                error: ajaxError,
            });
        });

        // // delete
        $('body').on('click', '.delete', function(e) {
            e.preventDefault();
            deleteclassification($(this).attr('id'))
        });

        function deleteclassification(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data Klasipikasi akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus Sekarang!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/') }}/api/classifications/" + id + "/destroy",
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
    </script>
@endpush
