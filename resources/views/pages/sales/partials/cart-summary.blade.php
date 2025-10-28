<div class="row g-3">
    {{-- KERANJANG --}}
    <div class="col-lg-9">
        <div class="card shadow-flat">
        <div class="hd">
            Keranjang
            <span class="badge-soft ms-2">Items: <span id="summaryTotalItem">0</span></span>
            <span class="badge-soft ms-auto">Subtotal: <span id="summarySubtotal">Rp0</span></span>
        </div>
        <div class="bd p-0">
            <div class="table-responsive">
            <table id="cart-table" class="table table-sm table-hover mb-0 table-cart">
                <thead class="table-light">
                <tr>
                    <th style="width:34px">#</th>
                    <th>Item</th>
                    <th style="width:180px">Batch</th>
                    <th class="right" style="width:90px">Qty</th>
                    <th style="width:100px">Unit</th>
                    <th class="right" style="width:140px">Harga</th>
                    <th class="right" style="width:160px">Sub Total</th>
                    <th style="width:40px"></th>
                </tr>
                </thead>
                <tbody id="cart-body">
                <tr>
                    <td colspan="8" class="text-muted">Belum ada item. Cari/scan produk di atas.</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="right">Total</th>
                    <th class="right" id="ft-total-qty">0</th>
                    <th></th>
                    <th class="right">Subtotal</th>
                    <th class="right" id="ft-subtotal"><b>Rp0</b></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
            </div>
        </div>
        </div>
    </div>

    {{-- RINGKASAN (Sticky) --}}
    <div class="col-lg-3">
        <div class="card shadow-flat summary-sticky">
        <div class="hd" style="background:#60a5fa;color:#fff">Ringkasan</div>
        <div class="bd">
            <div class="d-flex justify-content-between mb-2">
            <span>Subtotal</span>
            <span id="sum-subtotal" class="badge-soft">Rp0</span>
            </div>

            <div class="mb-2">
            <label class="form-label small mb-1">Biaya Lain (opsional)</label>
            <div class="row g-2">
                <div class="col-6">
                <input id="add-cost-name" type="text" class="form-control form-control-sm" placeholder="Ongkir/Admin">
                </div>
                <div class="col-6">
                <input id="add-cost-amount" type="text" class="form-control form-control-sm text-end" placeholder="0">
                </div>
            </div>
            </div>

            <hr class="my-2">

            <div class="d-flex justify-content-between mb-2">
            <b>Total Bayar</b>
            <b id="summaryTotalAkhir">Rp0</b>
            </div>

            <div class="mb-2">
            <label class="form-label small mb-1">Tipe Bayar</label>
            <select id="payment_type" class="form-select form-select-sm">
                <option value="CASH" selected>Tunai</option>
                <option value="EWALLET">E-wallet</option>
                <option value="QRIS">QRIS</option>
                <option value="TRANSFER">Transfer</option>
            </select>
            </div>

            <div class="mb-2">
            <label class="form-label small mb-1">Jumlah Bayar</label>
            <input id="amount_paid" type="text" class="form-control form-control-sm text-end" placeholder="0">
            </div>

            <div class="d-flex justify-content-between">
            <span>Kembalian</span>
            <span id="change_due" class="badge-soft">Rp0</span>
            </div>

        </div>
        </div>
    </div>
</div>

@push('sripts')
<script></script>
@endpush
