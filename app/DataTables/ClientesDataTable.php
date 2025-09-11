<?php

namespace App\DataTables;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon; //Clase de Laravel para trabajar con fechas y horas de forma avanzada y muy sencilla.


class ClientesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Cliente> $query Results from query() method.
     */

    public function index(ClientesDataTable $dataTable)
    {
        return $dataTable->render('clientes.clientesdt'); // Asegúrate que 'clientes.clientesdt' es tu vista Blade
    }



    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($cliente) {
                return Carbon::parse($cliente->created_at)->format('d/m/Y');
            })
            ->editColumn('updated_at', function ($cliente) {
                return Carbon::parse($cliente->updated_at)->format('d/m/Y');
            })
            ->addColumn('action', 'clientes.action') //añado la columna de los botones de la vista action.blade.php
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Cliente>
     */
    public function query(Cliente $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        /* NOTA: Column::make() solo define la estructura de la columna para el
        HTML Builder (nombre, título, orden, visibilidad, etc.). El contenido
        que se muestra en cada celda se controla en dataTable(). Por eso, no se
        formatea las fechas aqui, sino en function DataTable()
*/
        return [
            Column::make('id'),
            Column::make('nombre'),
            Column::make('email'),
            Column::make('telefono'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120) // Tamaño minimo (para que no desaparezcan los iconos interiores)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Clientes_' . date('YmdHis');
    }
}
