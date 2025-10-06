<?php

namespace App\DataTables;

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
        return (new EloquentDataTable($query))
            ->addColumn('action', function($row){
                return '<button onclick="removeItem('.$row->id.')" class="text-danger border-0 bg-transparent p-0"><i class="bx bx-x fs-4"></i></button>';
            })
            ->addColumn('Produk', function($row){
                return '<span class="d-block">'.($row->item->name ?? '').'</span>
                <span class="badge bg-primary">
                    <small class="text-start">
                        kode : '.($row->item->code ?? '').' |
                        Stok : '. ($row->item->all_stok ?? '0') .' '.($row->item->small_unit).'
                    </small>
                </span>';
            })
            ->addColumn('Batch', function($row){
                return $row->productBatch->batch_number ?? $row->temp_batch_number ?? '-';
            })
            ->addColumn('Exp Date', function($row){
                return $row->productBatch->exp_date ?? $row->exp_date ?? '00-00-0000';
            })
            ->addColumn('Harga Beli + margin (%)', function($row){
                $unitPrice = 'Rp. ' . number_format(($row->unit_price ?? $row->item->default_cost) ,0);
                $margin = ($row->item->margin ?? 0) . ' %';
                $btnEdit = '<button type="button" class="btn btn-icon text-warning" onclick="openModalUpdatePrice(\'' . encrypt($row->item->id) . '\', 
                                    \'' . addslashes($row->item->name) . '\', 
                                    \'' . $row->item->default_cost . '\',
                                    \'' . ($row->item->margin ?? 0) . '\', 
                                    \'' . ($row->item->default_price ?? 0) . '\')">
                                <i class="bx bx-edit"></i>
                            </button>';
                return $unitPrice . '+' . $margin . $btnEdit;
            })
            ->addColumn('Harga Jual', function($row){
                return 'Rp. ' . number_format($row->item->default_price, 0);
            })
            ->addColumn('Qty', function($row){
                return '<div class="input-group">
                    <input type="number" class="form-control" name="jumlah" id="jumlah" value="'.$row->qty.'" data-encrypt-id="'.encrypt($row->id).'" readonly ondblclick="enableForm(this)">
                    <span class="input-group-text bg-primary text-white">'.$row->item->small_unit.'</span>
                </div>';
            })
            ->addColumn('Diskon', function($row){
                return '<input type="number" value="0" name="discount" id="discount" class="form-control form-control-sm">';
            })
            ->addColumn('Pajak', function($row){
                return '<input type="number" value="0" name="tax" id="tax" class="form-control form-control-sm">';
            })
            ->addColumn('Total Harga', function($row){
                return 'Rp. ' . number_format($row->sub_total,0);
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
                ->with(['purchaseTemp','item', 'productBatch'])
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
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false
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
                  ->title('')
                  ->orderable(false)
                  ->searchable(false),
            Column::make('Produk'),
            Column::make('Batch')
                    ->defaultContent('-'),
            Column::make('Exp Date'),
            Column::make('Harga Beli + margin (%)'),
            Column::make('Harga Jual'),
            Column::make('Qty'),
            Column::make('Diskon'),
            Column::make('Pajak'),
            Column::make('Total Harga')
                    ->addClass('text-end'),
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
