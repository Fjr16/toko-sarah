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
                        <input id="add-cost-amount" type="text" class="form-control form-control-sm text-end" oninput="this.value = this.value.replace(/[^0-9]/, '')" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label small text-muted mb-1">Pelanggan / Member *</label>
                <div class="input-group input-group-sm">
                    <select id="customer_id" name="customer_id" class="form-control form-control-sm"></select>
                    <button class="btn btn-outline-secondary" type="button" id="btnAddCustomer"><i class="bi bi-person-plus"></i></button>
                </div>
                <small class="text-muted">F3: cari, F4: tambah pelanggan</small>
            </div>

            <div class="d-flex justify-content-between mb-2 p-2 bg-primary text-white rounded">
                <b>Total Bayar</b>
                <b id="summaryTotalAkhir">Rp0</b>
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
        });

        cartTable.on('xhr.dt', function(e, settings, json, xhr){
            if (json?.summary) {
                const subTotalFirst = json.summary.subTotalSum;
                const otherCost = $('#add-cost-amount').val();
                const grandTotal = reverseFormatRupiah(subTotalFirst) + toNum(otherCost);

                const grandTotalRp = rupiahFormatter(grandTotal);

                $('#summaryTotalAkhir').text(grandTotalRp);
                $('#grandTotalBottom').text(grandTotalRp);

                $('#summaryTotalItemBottom').text(json.summary.itemsCount ?? '0')
                $('#ft-subtotal').text(subTotalFirst ?? 'Rp -')
                $('#sum-subtotal').text(subTotalFirst ?? 'Rp -')
                $('#summarySubtotal').text(subTotalFirst ?? 'Rp -')
                $('#ft-total-qty').text(json.summary.qtySum ?? '0')

                // untuk confirm modal
                $('#totalItems').text(json.summary.itemsCount ?? '0')
                $('#totalQty').text(json.summary.qtySum ?? '0')
                $('#subTotalConfirm').text(subTotalFirst ?? 'Rp -')
                $('#totalAkhirConfirm').text(grandTotalRp);
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

    var onedited;
    function editOnCart(element){
        if (onedited) {
            cancelEdit();
        }

        const tr = $(element).closest('tr');
        const row = cartTable.row(tr);
        onedited = row;

        const data = row.data();
        const tdAction = cartTable.cell(row,0).node();
        const tdQty = cartTable.cell(row,3).node();

        const prodBatchId = data.id;
        const prodQty = data.jumlah;
        const prodSatuan = data.satuan;

        $(tdAction).find('#btn_edit_'+prodBatchId).prop('hidden',true);
        $(tdAction).find('#btn_update_'+prodBatchId).prop('hidden', false);

        tdQty.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" id="qty_${prodBatchId}" name="qty" value="${prodQty}" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                <span class="input-group-text">${prodSatuan}</span
            </div>
        `;

        const inp = $(tdQty).find('#qty_'+prodBatchId);
        inp.focus().select();
    }

    function cancelEdit(){
        const oldData = onedited.data();
        const tdOldAction = cartTable.cell(onedited,0).node();
        const tdOldQty = cartTable.cell(onedited,3).node();
        $(tdOldAction).find('#btn_edit_'+oldData.id).prop('hidden',false);
        $(tdOldAction).find('#btn_update_'+oldData.id).prop('hidden', true);
        tdOldQty.innerHTML = oldData.jumlah;
    }

    async function updateOnCart(element){
        const tr = $(element).closest('tr');
        const id = element.id.replace('btn_update_', '');
        const newQty = $(tr).find('#qty_'+id).val();

        try {
            const res = await fetch("{{ route('sales/update/on.cart') }}", {
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                    'Content-Type' : 'application/json',
                },
                body: JSON.stringify({
                    batch_id:id,
                    qty:newQty
                })
            });

            if (!res.ok) {
                const errorText = await res.statusText;
                throw new Error(errorText || 'Gagal diperbarui');
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

    $(document).on('keydown', 'input[id^="qty_"]', function(e){
        if(e.key === 'Enter') {
            e.preventDefault();
            const id = this.id.replace('qty_', '');
            $(`#btn_update_${id}`).trigger('click');
        }else if(e.key === 'Escape'){
            const tr = $(this).closest('tr');
            cancelEdit(cartTable.row(tr));
        }
    });

    $('#add-cost-amount').on('change', function(e){
        let subTotal = $('#sum-subtotal').text();
        subTotal = reverseFormatRupiah(subTotal);
        const totalBayar = rupiahFormatter(subTotal + toNum(this.value));

        $('#summaryTotalAkhir').text(totalBayar ?? 'Rp -');
        $('#grandTotalBottom').text(totalBayar);
        $('#totalAkhirConfirm').text(totalBayar);
    });

    const cust = $('#customer_id');

    // sisipkan sekali jika belum ada
    if (!cust.find('option[value="umum"]').length) {
        cust.append(new Option('Umum (Non-member)', 'umum', true, true));
    }
    cust.select2({
        theme: 'bootstrap-5',
        placeholder : 'Cari dan pilih data pelanggan',
        ajax : {
            url : '/customer/search',
            dataType : 'json',
            delay : 250,
            data : function(params){
                return {
                    search : params.term,
                }
            },
            processResults : function(data, params){
                const results = data.map(item => ({
                    id : item.id,   //menjadi value pada select
                    text : item.name+ ' - ' + item.member_code,
                }));
                if (!results.some(r => r.id === 'umum')) {
                    results.unshift({ id: 'umum', text: 'Umum (Non-member)' });
                }
                return {
                    results
                };
            },
            cache : true    //respon pencarian ajax akan disimpan pada chace, jika dilakukan pencarian dengan keyword yang sama maka tidak memanggi ulang ajax melainkan diambil dari chace
        },
        minimumInputLength : 1,     //pencarian baru akan dilakukan jika terdapat 1 character pada form input select
    });

    cust.val('umum').trigger('change');
</script>
@endpush
