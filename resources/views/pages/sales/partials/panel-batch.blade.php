<div class="col-sm-6 col-md-7">
    <div class="card shadow-flat d-flex flex-column">
        <div class="hd flex-shrink-0">
            Panel Batch Cepat
            <span class="ms-auto">
                <input type="text" name="search_batch_table" id="search_batch_table" class="form-control form-control-md" placeholder="Cari batch">
            </span>
        </div>
        <div class="bd table-responsive overflow-auto flex-grow-1" id="panel-batch">
            <table class="table table-striped mb-0" id="batch-table">
                <thead>
                    <tr>
                        <th width="5%">Add</th>
                        <th width="25%">Qty</th>
                        <th>No. Batch</th>
                        <th>Exp Date</th>
                        <th>Stock</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function addProdToCart(element){
        const batchId = element.dataset.batchId;
        const qty = $(element).closest('tr').find('input[name="qty"]').val();

        try {
            const res = await fetch("{{ route('sales/add/to.cart') }}", {
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}",
                    'Accept' : 'application/json',
                    'Content-Type' : 'application/json',
                },
                body:JSON.stringify({
                    batch_id : batchId,
                    qty : qty
                })
            });
            if (!res.ok) {
                const errorText = await res.statusText;
                throw new Error(errorText || 'Gagal ditambahkan');
            }

            const result = await res.json();
            if (!result || result.status == false) {
                notify('error', result.message.slice(0,150));
                return;
            }

            cartTable.ajax.reload();
            notify('success', result.message.slice(0,150))

        } catch (error) {
            console.log(error.message);
            notify('error', error.message.slice(0,150));
        }
    }

    var batchTable;
    var productId;
    $(document).ready(function(){
        batchTable = $('#batch-table').DataTable({
            processing:true,
            serverSide:true,
            searching:true,
            info:false,
            paging:false,
            lengthChange:false,
            deferLoading:0,
            scrollY:'150px',
            scrollCollapse:true,
            responsive:true,
            dom: "<'d-none'f>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            ajax:{
                url: "{{ route('product/get/data/batch.v2') }}",
                data:function(d){
                    d.product_id = productId
                }
            },
            columns: [
                {data:'action', name:'action', orderable:false, searchable:false},
                {data:'qty', name:'qty', 'defaultContent' : '-', orderable:false, searchable:false},
                {data:'batch_number', name:'batch_number', 'defaultContent' : '-', searchable:true, orderable:false},
                {data:'exp_date', name:'exp_date', 'defaultContent' : '-', searchable:true, orderable:true},
                {data:'stock', name:'stock', orderable:false, searchable:false},
            ],

            order:[[3,'asc']],
        });

        $('#product-select').on('change', function(e){
            productId = $(this).val();
            batchTable.ajax.reload();
        });

        $('input[name="search_batch_table"]').on('keyup',function(){
            batchTable.search(this.value, false).draw();
        });
    });
</script>
@endpush
