{{-- ========================================================== --}}
{{-- 1. MODAL TAMBAH SATUAN CEPAT                               --}}
{{-- ========================================================== --}}
<div class="modal fade" id="modalTambahSatuan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formQuickSatuan">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-1"></i> Tambah Satuan Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="alertQuickSatuan" class="alert alert-danger d-none"></div>
                    <div class="form-group">
                        <label>Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_satuan" class="form-control" placeholder="Contoh: Kilogram" required>
                    </div>
                    <div class="form-group">
                        <label>Singkatan <span class="text-danger">*</span></label>
                        <input type="text" name="singkatan" class="form-control" placeholder="Contoh: kg" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveSatuan">Simpan Satuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================================== --}}
{{-- 2. MODAL TAMBAH BAHAN BAKU CEPAT                           --}}
{{-- ========================================================== --}}
@if(isset($satuans))
<div class="modal fade" id="modalTambahBahan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formQuickBahan">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-1"></i> Tambah Bahan Baku Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="alertQuickBahan" class="alert alert-danger d-none"></div>
                    <div class="form-group">
                        <label>Nama Bahan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bahan" class="form-control" placeholder="Contoh: Gula Pasir" required>
                    </div>
                    <div class="form-group">
                        <label>Satuan Beli <span class="text-danger">*</span></label>
                        <select name="id_satuan" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $s)
                                <option value="{{ $s->id_satuan }}">{{ $s->nama_satuan }} ({{ $s->singkatan }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveBahan">Simpan Bahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ========================================================== --}}
{{-- 3. MODAL TAMBAH SUPPLIER CEPAT                             --}}
{{-- ========================================================== --}}
<div class="modal fade" id="modalTambahSupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formQuickSupplier">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-1"></i> Tambah Supplier Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="alertQuickSupplier" class="alert alert-danger d-none"></div>
                    <div class="form-group">
                        <label>Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" class="form-control" placeholder="Contoh: Toko Bahan Kue Jaya" required>
                    </div>
                    <div class="form-group">
                        <label>Kontak / No. HP</label>
                        <input type="text" name="kontak" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="2" class="form-control" placeholder="Alamat supplier (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveSupplier">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    function handleQuickSubmit(formId, url, selectId, modalId, alertId, btnId, extraCallback) {
        $(formId).on('submit', function (e) {
            e.preventDefault();
            let $form = $(this);
            let $btn = $(btnId);
            let $alert = $(alertId);

            $alert.addClass('d-none').empty();
            $btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: url,
                method: 'POST',
                data: $form.serialize(),
                headers: { 'Accept': 'application/json' },
                success: function (res) {
                    if (res.success && res.data) {
                        let $select = $(selectId);
                        let $opt = $('<option>', {
                            value: res.data.id,
                            text: res.data.label,
                            selected: true
                        });
                        if (res.data.satuan) {
                            $opt.attr('data-satuan', res.data.satuan);
                        }
                        $select.append($opt).trigger('change');
                        if (typeof extraCallback === 'function') {
                            extraCallback(res.data);
                        }
                    }
                    $form[0].reset();
                    $(modalId).modal('hide');
                },
                error: function (xhr) {
                    let msg = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    $alert.html(msg).removeClass('d-none');
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Simpan');
                }
            });
        });
    }

    // 1. Submit Modal Satuan -> isi dropdown #id_satuan
    handleQuickSubmit('#formQuickSatuan', "{{ route('satuan.store') }}", '#id_satuan', '#modalTambahSatuan', '#alertQuickSatuan', '#btnSaveSatuan');

    // 2. Submit Modal Bahan -> isi dropdown #id_bahan + update badge satuan
    handleQuickSubmit('#formQuickBahan', "{{ route('bahan.store') }}", '#id_bahan', '#modalTambahBahan', '#alertQuickBahan', '#btnSaveBahan', function (data) {
        if (data.satuan && $('#label-satuan').length) {
            $('#label-satuan').text(data.satuan);
        }
    });

    // 3. Submit Modal Supplier -> isi dropdown #id_supplier
    handleQuickSubmit('#formQuickSupplier', "{{ route('supplier.store') }}", '#id_supplier', '#modalTambahSupplier', '#alertQuickSupplier', '#btnSaveSupplier');
});
</script>
@endpush