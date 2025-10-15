<?php

namespace App\DataTables;

use App\Helpers\CustomHelpers;
use App\Models\PurchaseTempDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PurchaseTempDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $summary = PurchaseTempDetail::query()
        ->leftJoin('items', 'purchase_temp_details.item_id', '=', 'items.id')
        ->whereHas('purchaseTemp', fn($q) => $q->where('user_id', auth()->id()))
        ->selectRaw('
            COUNT(*) AS total_items,
            COALESCE(SUM(purchase_temp_details.qty), 0) AS total_qty,
            COALESCE(
                SUM(
                    COALESCE(purchase_temp_details.unit_price,0) * COALESCE(purchase_temp_details.qty,0)
                ),0
            ) AS sub_total,
            COALESCE(SUM(purchase_temp_details.sub_total), 0) AS total_kotor,
            COALESCE(SUM(purchase_temp_details.discount), 0) AS total_diskon,
            COALESCE(SUM(purchase_temp_details.tax), 0) AS total_pajak
        ')
        ->first();
        return (new EloquentDataTable($query))
            ->with([
                'summary' => [
                    'total_rows'  => (int) $summary->total_items,
                    'total_qty'    => (int) $summary->total_qty,
                    'subtotal'    => CustomHelpers::formatterRupiah($summary->sub_total),
                    'total'    => CustomHelpers::formatterRupiah($summary->total_kotor),
                    'discount'    => CustomHelpers::formatterRupiah($summary->total_diskon),
                    'tax'         => CustomHelpers::formatterRupiah($summary->total_pajak),
                ]
            ])
            ->addColumn('action', function($row){
                $delete = '<button onclick="removeItem('.$row->id.')" class="text-danger border-0 bg-transparent p-0"><i class="bx bxs-x-square fs-4"></i></button>';
                $edit = '<button onclick="editItem('.$row->id.')" class="text-warning border-0 bg-transparent p-0"><i class="bx bx-edit fs-4"></i></button>';
                return $edit . $delete;
            })
            ->addColumn('Produk', function($row){
                return '<span class="d-block">'.($row->product_name ?? '').'</span>
                <span class="badge bg-primary">
                    <small class="text-start">
                        kode : '.($row->product_code ?? '').' |
                        Stok : '. ($row->product_total_stock ?? '0') .' '.($row->product_satuan).'
                    </small>
                </span>';
            })
            ->addColumn('Batch', function($row){
                return $row->productBatch->batch_number ?? $row->temp_batch_number ?? '-';
            })
            ->addColumn('Exp Date', function($row){
                return $row->productBatch->exp_date ?? $row->exp_date ?? '00-00-0000';
            })
            ->addColumn('Harga Satuan', function($row){
                return CustomHelpers::formatterRupiah(($row->unit_price ?? $row->product_default_cost));
            })
            ->addColumn('Qty', function($row){
                return (int) $row->qty . ' pcs';
            })
            ->addColumn('subtotal', function($row){
                return CustomHelpers::formatterRupiah(($row->unit_price ?? 0) * ($row->qty ?? 0));
            })
            ->addColumn('Diskon', function($row){
                return CustomHelpers::formatterRupiah($row->discount);
            })
            ->addColumn('Pajak', function($row){
                return CustomHelpers::formatterRupiah($row->tax);
            })
            ->addColumn('Total Harga', function($row){
                return CustomHelpers::formatterRupiah($row->sub_total);
            })
            ->rawColumns(['action', 'Produk','Harga Beli + margin (%)', 'Qty','Diskon', 'Pajak'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PurchaseTempDetail $model): QueryBuilder
    {
        return $model->newQuery()
                ->leftJoin('items', 'purchase_temp_details.item_id', '=', 'items.id')
                ->with(['purchaseTemp', 'productBatch'])
                ->select([
                    'purchase_temp_details.*',
                    'items.id as product_id',
                    'items.code as product_code',
                    'items.name as product_name',
                    'items.all_stok as product_total_stock',
                    'items.small_unit as product_satuan',
                    'items.default_cost as product_default_cost',
                ])
                ->whereHas('purchaseTemp', function($q){
                    $q->where('user_id', auth()->id());
                });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('purchasetemp-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->processing(true)
                    ->serverSide(true)
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'language' => [
                            'processing' => '<div class="d-flex align-items-center gap-2">
                                   <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
                                   <span>Loading data…</span>
                                 </div>',
                        ],
                    ])
                    ->orderBy(1)
                    ->dom('frtip')
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel')->filename($this->filename()),
                        Button::make('csv')->filename($this->filename()),
                        Button::make('pdf')->filename($this->filename()),
                        Button::make('print'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                    ->title('Aksi')
                    ->addClass('action_table')
                    ->orderable(false)
                    ->searchable(false),
            Column::make('Produk')
                    ->name('items.name')
                    ->footer('Total Akhir'),
            Column::make('Batch')
                    ->name('temp_batch_number')
                    ->defaultContent('-'),
            Column::make('Exp Date')
                    ->name('exp_date'),
            Column::make('Harga Satuan')
                    ->orderable(false)
                    ->searchable(false)
                    ->addClass('unit_price_table'),
            Column::make('Qty')
                    ->title('* Qty')
                    ->orderable(false)
                    ->searchable(false)
                    ->addClass('qty_table'),
            Column::make('subtotal')
                    ->title('= Subtotal')
                    ->orderable(false)
                    ->searchable(false)
                    ->addClass('subtotal_table'),
            Column::make('Diskon')
                    ->title('- Diskon')
                    ->orderable(false)
                    ->searchable(false)
                    ->addClass('discount_table'),
            Column::make('Pajak')
                    ->title('+ Pajak')
                    ->orderable(false)
                    ->searchable(false)
                    ->addClass('tax_table'),
            Column::make('Total Harga')
                    ->addClass('text-end')
                    ->title('Σ Total'),
            Column::make('product_code')
                    ->name('items.code')
                    ->visible(false)
                    ->searchable(true),
        ];
    }

    /**
     * Get the filename for export.
     */
    // protected function filename(): string
    // {
    //     return 'KeranjangPembelianSarah_' . date('YmdHis');
    // }
}
