<div class="col-sm-6 col-md-7">
    <div class="card shadow-flat">
    <div class="hd">Panel Batch Cepat</div>
    <div class="bd" id="panel-batch">
        <div class="row g-2 mb-1">
        <div class="col-md-5">
            <label class="form-label small mb-1">Batch</label>
            <select id="quick-batch1" class="form-select form-select-sm" data-placeholder="Pilih batch…" style="width:100%"></select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Exp Date</label>
            <input id="quick-exp1" type="date" class="form-control form-control-sm text-end" placeholder="1" disabled>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Qty</label>
            <div class="input-group input-group-sm">
                <input id="quick-qty1" type="text" class="form-control form-control-sm text-end" placeholder="1">
                <span class="input-group-text">pcs</span>
            </div>
        </div>
        <div class="col-md-1">
            <label class="form-label small mb-1 d-none d-md-block">&nbsp;</label>
            <button type="button" class="btn btn-primary btn-sm" onclick="addRowBatch()">
            <i class="bi bi-plus"></i>
            </button>
        </div>
        </div>
        <div class="mt-2 d-flex gap-8">
        <button class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clock-history"></i> Riwayat Batch
        </button>
        <button class="btn btn-success btn-sm border-0">
            <i class="bi bi-plus"></i> Keranjang
        </button>
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
    let counter = 1;
    function addRowBatch(){
        const newRow = `
            <div class="row g-2 mb-1">
                <div class="col-md-5">
                    <select id="quick-batch${counter+1}" class="form-select form-select-sm" data-placeholder="Pilih batch…" style="width:100%"></select>
                </div>
                <div class="col-md-3">
                    <input id="quick-exp${counter+1}" type="date" class="form-control form-control-sm text-end" disabled>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input id="quick-qty${counter+1}" type="text" class="form-control form-control-sm text-end" placeholder="1">
                        <span class="input-group-text">pcs</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeBatchRow(this)">
                        <i class="bi bi-dash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#panel-batch .row').last().after(newRow);
        counter = counter+1;
    }
    function removeBatchRow(element){
        $(element).closest('.row').remove();
    }
</script>
@endpush
