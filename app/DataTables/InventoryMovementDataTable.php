<?php

namespace App\DataTables;

use App\Enums\InventoryFlag;
use App\Models\InventoryMovement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InventoryMovementDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Produk', function($row){
                return $row->item->name;
            })
            ->addColumn('No. Batch', function($row){
                return $row->productBatch->batch_number;
            })
            ->addColumn('Tgl Exp', function($row){
                return $row->productBatch->exp_date;
            })
            ->editColumn('qty', function($row){
                return $row->qty . ' ' . $row->unit;
            })
            ->editColumn('flag', function($row){
                $flag = $row->flag == InventoryFlag::in->value
                        ? InventoryFlag::in
                        : ($row->flag == InventoryFlag::out->value
                            ? InventoryFlag::out
                            : null);

                return '<span><i class="'. ($flag ? $flag->icon() : 'bi bi-exclamation-square').' me-1" style="font-size:18px;"></i> '. ($flag ? $flag->label() : 'Undefined') .'</span>';
            })
            ->editColumn('reference_type', function($row){
                return str_replace("App\\Models\\",'',$row->reference_type ?? '');
            })
            ->addColumn('Dibuat Oleh', function($row){
                return $row->user->name;
            })
            ->editColumn('created_at', function($row){
                return $row->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['flag'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(InventoryMovement $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['user', 'item', 'productBatch']);
        $query->when(request('start_at') && request('end_at'), function($q){
            $q->whereBetween('created_at', [
                Carbon::parse(request('start_at'))->startOfDay(),
                Carbon::parse(request('end_at'))->endOfDay()
            ]);
        });

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('inventorymovement-table')
                    ->columns($this->getColumns())
                    ->ajax([
                        'data' => 'function(d) {
                            d.start_at = $("#start_at").val();
                            d.end_at = $("#end_at").val();
                        }'
                    ])
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false
                    ])
                    ->orderBy(0,'desc')
                    ->dom('Bfrtip')
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
            Column::make('created_at')
                    ->title('Dibuat Pada'),
            Column::make('Produk'),
            Column::make('No. Batch'),
            Column::make('qty')
                    ->title('Jumlah')
                    ->orderable(true)
                    ->searchable(true),
            Column::make('Tgl Exp'),
            Column::make('flag')
                    ->title('Flag Movement'),
            Column::make('reference_type')
                    ->title('Modul'),
            Column::make('note')
                    ->title('Catatan')
                    ->defaultContent('-'),
            Column::make('Dibuat Oleh'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InventoryMovement_' . date('YmdHis');
    }
}
