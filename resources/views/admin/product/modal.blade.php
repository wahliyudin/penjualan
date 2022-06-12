<div class="modal fade" id="Product-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addEditProductForm" action="javascript:void(0)" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" readonly name="kode" id="kode" placeholder="Kode">
                        <span class="text-danger" id="kodeError"></span>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama">
                        <span class="text-danger" id="namaError"></span>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga">
                        <span class="text-danger" id="hargaError"></span>
                    </div>
                    <div class="form-group">
                        <label>Tipe Barang</label>
                        <select name="type_product_id" id="type_product_id" class="form-control" style="width: 100%;">
                            <option selected="selected" disabled>-- pilih --</option>
                            @foreach ($type_products as $type_product)
                                <option value="{{ $type_product->id }}">{{ $type_product->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
