<div class="modal fade" id="Supply-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addEditSupplyForm" action="javascript:void(0)" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label>Barang</label>
                        <select name="product_id" id="product_id" class="form-control" style="width: 100%;">
                            <option selected="selected" disabled>-- pilih --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="text" readonly class="form-control" name="harga" id="harga" placeholder="Stok">
                        <span class="text-danger" id="hargaError"></span>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" name="stok" id="stok" placeholder="Stok">
                        <span class="text-danger" id="stokError"></span>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="text" readonly class="form-control" name="total" id="total" placeholder="Total">
                        <span class="text-danger" id="totalError"></span>
                    </div>

                    <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
