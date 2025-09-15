<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class EntidadDataTable extends DataTable
{
    protected string $modelo;
    protected string $entidad;

    public function __construct(string $modelo, string $entidad)
    {
        $this->modelo = ucfirst($modelo);   // Ej: 'Cliente'
        $this->entidad = strtolower($entidad); // Ej: 'clientes'
    }

    /**
     * Construye el DataTable con formato y acciones.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn($registro) => Carbon::parse($registro->created_at)->format('d/m/Y'))
            ->editColumn('updated_at', fn($registro) => Carbon::parse($registro->updated_at)->format('d/m/Y'))
            //->addColumn('action', $this->entidad . '.action') // Vista Blade específica por entidad
            ->addColumn('action', 'entidad.action') // Añado los botones en lineas de la vista action.blade.php
            ->setRowId('id');
    }

    /**
     * Fuente de datos para el DataTable.
     */
    public function query(): QueryBuilder
    {
        $modelClass = 'App\\Models\\' . $this->modelo;

        if (!class_exists($modelClass)) {
            abort(404, "Modelo no encontrado: $modelClass");
        }

        return (new $modelClass)->newQuery();
    }

    /**
     * Configuración HTML del DataTable.
     */
    public function html(): HtmlBuilder
    {
        $tailwindStyle = 'inline-block px-4 py-2 text-sm font-semibold text-white bg-gray-600 rounded hover:bg-gray-700 active:scale-95 transform transition duration-100 ease-in-out';

        return $this->builder()
            ->setTableId($this->entidad . '-table') // Ej: 'clientes-table'
            ->columns($this->getColumns()) // Columnas definidas en getColumns()
            ->minifiedAjax() // Usa AJAX para cargar datos
            ->orderBy(1) // Ordena por la primera columna (ID)
            ->selectStyleSingle() // Permite seleccionar una fila a la vez
            ->responsive(true) // Hace la tabla responsive
            ->dom('Bfrtip') // Define la estructura del DataTable con botones, filtro, tabla, páginación,..
            ->buttons([ //inyecto los botones de exportación usando estilos de Tailwind CSS
                Button::make('excel')->text('Excel')->className($tailwindStyle),
                Button::make('csv')->text('CSV')->className($tailwindStyle),
                Button::make('pdf')->text('PDF')->className($tailwindStyle),
                Button::make('print')->text('Imprimir')->className($tailwindStyle),
            ]);
    }

    /**
     * Columnas del DataTable.
     */
    public function getColumns(): array
    {
        // Configuramos las columnas para cada entidad
        switch ($this->entidad) {
            case 'clientes':
                return [
                    Column::make('id'),
                    Column::make('nombre'),
                    Column::make('email'),
                    Column::make('telefono'),
                    Column::make('created_at')->title('Creado'),
                    Column::make('updated_at')->title('Actualizado'),
                    Column::computed('action')
                        ->exportable(false)
                        ->printable(false)
                        ->width(120)
                        ->addClass('text-center'),
                ];
            case 'proveedores':
                return [
                    Column::make('id'),
                    Column::make('nombre'),
                    Column::make('cif'),
                    Column::make('email'),
                    Column::make('telefono'),
                    Column::make('created_at')->title('Creado'),
                    Column::make('updated_at')->title('Actualizado'),
                    Column::computed('action')
                        ->exportable(false)
                        ->printable(false)
                        ->width(120)
                        ->addClass('text-center'),
                ];
            default:
                return [
                    Column::make('id'),
                    Column::make('nombre')->echo('define las columnas de la entidad en getColumns() de EntidadDataTable.php'),
                    Column::make('created_at')->title('Creado'),
                    Column::make('updated_at')->title('Actualizado'),
                    Column::computed('action')
                        ->exportable(false)
                        ->printable(false)
                        ->width(120)
                        ->addClass('text-center'),
                ];
        }
    }

    /**
     * Nombre del archivo exportado.
     */
    protected function filename(): string
    {
        return ucfirst($this->entidad) . '_' . date('YmdHis');
    }
}
