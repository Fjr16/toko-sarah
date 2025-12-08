<div class="row g-3">
    {{-- KERANJANG --}}
    <div class="col-lg-9">
        <div class="card shadow-flat">
            <div class="hd">
                Keranjang
                <span class="badge-soft ms-auto">Subtotal: <span id="summarySubtotal">Rp0</span></span>
            </div>
            <div class="bd">
                {{-- <div class="table-responsive"> --}}
                    <table id="cart-table" class="table table-sm table-hover mb-0 table-cart">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Produk</th>
                                <th>Batch</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tfoot class="text-dark">
                            <tr>
                                <th colspan="3">Total</th>
                                <th id="ft-total-qty" class="text-start">0</th>
                                <th>Subtotal</th>
                                <th id="ft-subtotal"><b>Rp0</b></th>
                            </tr>
                        </tfoot>
                    </table>
                {{-- </div> --}}
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

@push('scripts')
<script>
    var cartTable;
    $(document).ready(function(){
        cartTable = $('#cart-table').DataTable({
            processing:true,
            serverSide:true,
            info:false,
            paging:false,
            scrollY:'200px',
            scrollCollapse:true,
            responsive:true,
            searching:false,
            autoWidth:true,
            columnDefs: [
                {
                    targets: [0],
                    className: 'fit',
                    width: '1%',
                    orderable: false
                }
            ],
            ajax: {
                url: "{{ route('sales/get.dataTable') }}",
            },
            columns:[
                {data:'action', name:'action', searchable:false, orderable:false},
                {data:'product_name',name:'product_name', searchable:true, orderable:true, 'defaultContent':'-'},
                {data:'batch_number',name:'batch_number', searchable:true, orderable:true, 'defaultContent':'-'},
                {data:'jumlah',name:'jumlah', searchable:true, orderable:true,},
                {data:'harga',name:'harga', searchable:true, orderable:true, 'defaultContent':'-'},
                {data:'subtotal',name:'subtotal', searchable:true, orderable:true, 'defaultContent':'-'},
            ],
            order:[[1,'asc']],
            // drawCallback:function(){
            //     const json = this.api().ajax.json();
            //     if (json?.summary) {
            //         console.log(json.summary.qtySum);
            //         console.log(json.summary.subTotalSum);
            //     }
            // }
        });

        cartTable.on('xhr.dt', function(e, settings, json, xhr){
            if (json?.summary) {
                $('#ft-subtotal').text(json.summary.subTotalSum ?? 'Rp -')
                $('#sum-subtotal').text(json.summary.subTotalSum ?? 'Rp -')
                $('#summarySubtotal').text(json.summary.subTotalSum ?? 'Rp -')
                $('#ft-total-qty').text(json.summary.qtySum ?? '0')
            }
        })

    });

    async function deleteProdOnCart(element){
        const url = element.dataset.url;
        try {
            const res = await fetch (url, {
                method:'DELETE',
                headers:{
                    'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                },
            });

            if (!res.ok) {
                const errorText = await res.statusText;
                throw new Error(errorText || 'Gagal dihapus');
            }
            const result = await res.json();
            if (!result || result.status == false) {
                notify('error', result.message.slice(0,150));
                return;
            }

            cartTable.ajax.reload();
            notify('success', result.message.slice(0,150));
        } catch (error) {
            console.log(error.message);
            notify('error', error.message.slice(0,150));
        }
    }
</script>
@endpush
