@extends('layouts.auth2.main')

@push('styles')
<style>
    .card.shadow-flat{
        border:1px solid #e5e7eb;
        border-radius:12px;
        box-shadow:0 1px 2px rgba(0,0,0,.04);
    }
    .card .hd{
        padding:.65rem .9rem;
        border-bottom:1px solid #e5e7eb;
        background:#f1f5f9;
        border-radius:12px 12px 0 0;
        font-weight:700;
        text-transform:uppercase;
        color:#111827;
        display:flex;
        align-items:center;
        gap:.5rem;
    }
    .card .bd{
        padding:.9rem;
    }
    .input, .select{
        width:100%;
    }
    .table-cart th,.table-cart td{
        vertical-align:middle;
    }
    .table-cart tfoot th{
        background:#f8fafc;
    }
    .right{
        text-align:right;
    }
    .summary-sticky{
        position:sticky;
        top:1rem;
    }
    .footer-sticky{
        position:sticky;
        bottom:0;
        z-index:1020;
    }
    .gap-8{
        gap:.5rem;
    }
    .badge-soft{
        padding:.2rem .5rem;
        border-radius:6px;
        background:#ecfeff;
        color:#0369a1;
        font-weight:700;
    }
</style>
@endpush

@section('content')

    {{-- ROW: PILIH PRODUK + PANEL BATCH --}}
    <div class="row g-3 mb-3">
        {{-- PILIH PRODUK --}}
        <div class="col-sm-6 col-md-5">
        <div class="card shadow-flat">
            <div class="hd">Pilih Produk</div>
            <div class="bd">
            <select id="product-select" class="form-select" data-placeholder="Search / scan barcode" style="width:100%"></select>
            </div>
        </div>
        </div>

        {{-- PANEL BATCH CEPAT --}}
        @include('pages.sales.partials.panel-batch')
    </div>

    {{-- ROW: KERANJANG + RINGKASAN --}}
    @include('pages.sales.partials.cart-summary')

@endsection

@section('footer')
  <div class="card mt-3 shadow-flat footer-sticky">
    <div class="card-body py-2">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
        <small class="text-muted">Total Items: <b id="summaryTotalItemBottom">0</b></small>
        <small class="fw-bold text-success">Grand Total: <span id="grandTotalBottom">Rp0</span></small>
      </div>
      <div class="row g-2 align-items-center">
        <div class="col-12 col-sm-auto">
          <button type="button" class="btn btn-outline-danger btn-sm">⟲ Kosongkan Keranjang</button>
        </div>
        <div class="col-12 col-sm d-flex justify-content-sm-end">
          <button type="button" class="btn btn-primary btn-sm">✔ Penjualan Selesai</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // ===== Helpers =====
  const fmt = n => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(Number(n||0));
  const num = s => Number(String(s??'').replace(/[^\d-]/g,''))||0;

  // Baca subtotal per baris
  function readSubtotal(){
    let sum = 0;
    document.querySelectorAll('#cart-body .sub-total').forEach(td=>{
      const ds = td.getAttribute('data-subtotal');
      sum += (ds!=null ? Number(ds)||0 : num(td.textContent));
    });
    return sum;
  }
  // Baca total qty (opsional)
  function readTotalQty(){
    let qty = 0;
    document.querySelectorAll('#cart-body .qty-cell').forEach(td=>{
      const dq = td.getAttribute('data-qty');
      qty += (dq!=null ? Number(dq)||0 : num(td.textContent));
    });
    return qty;
  }
  // Hitung jumlah baris item (bukan placeholder)
  function readTotalItems(){
    const rows = Array.from(document.querySelectorAll('#cart-body tr'));
    return rows.filter(tr=>{
      const tds = tr.querySelectorAll('td');
      if(tds.length<=1) return false;
      return tr.querySelector('.sub-total') || tds.length>=4;
    }).length;
  }

  function refreshSummary(){
    const subtotal = readSubtotal();
    const addCost  = num(document.getElementById('add-cost-amount')?.value);
    const grand    = subtotal + addCost;
    const paid     = num(document.getElementById('amount_paid')?.value);
    const change   = Math.max(0, paid - grand);
    const totalQty = readTotalQty();
    const items    = readTotalItems();

    // Header keranjang + ringkasan
    const el = id => document.getElementById(id);
    el('summarySubtotal') && (el('summarySubtotal').textContent = fmt(subtotal));
    el('sum-subtotal')    && (el('sum-subtotal').textContent    = fmt(subtotal));
    el('ft-subtotal')     && (el('ft-subtotal').textContent     = fmt(subtotal));
    el('ft-total-qty')    && (el('ft-total-qty').textContent    = totalQty);
    el('summaryTotalAkhir') && (el('summaryTotalAkhir').textContent = fmt(grand));
    el('change_due')      && (el('change_due').textContent      = fmt(change));
    el('summaryTotalItem') && (el('summaryTotalItem').textContent = items);
    el('summaryTotalItemBottom') && (el('summaryTotalItemBottom').textContent = items);
    el('grandTotalBottom') && (el('grandTotalBottom').textContent = fmt(grand));
  }

  // Trigger hitung saat input berubah
  document.getElementById('add-cost-amount')?.addEventListener('input', refreshSummary);
  document.getElementById('amount_paid')?.addEventListener('input', refreshSummary);

  // Auto-refresh ketika tbody berubah (baris tambah/hapus/update oleh logic kamu)
  const cartBody = document.getElementById('cart-body');
  if(cartBody){
    const obs = new MutationObserver(refreshSummary);
    obs.observe(cartBody, {childList:true, subtree:true, characterData:true});
  }

  document.addEventListener('DOMContentLoaded', refreshSummary);
</script>
@endpush
