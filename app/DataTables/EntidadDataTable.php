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
    protected array  $camposVisibles=[];
    protected array  $camposOcultos=[];

    public function __construct(string $modelo, string $entidad, array $camposVisibles, array $camposOcultos = [])
    {
        $this->modelo   = ucfirst($modelo);   // Ej: 'Cliente'
        $this->entidad  = strtolower($entidad); // Ej: 'clientes'
        //$this->campos   = $campos; // Todos los campos en la tabla
        $this->camposVisibles = $camposVisibles; // Campos visibles en la tabla
        $this->camposOcultos = $camposOcultos; // Campos ocultos en la tabla
    }


    /**
     * Construye el DataTable con formato y acciones.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->editColumn('created_at', fn($registro) => Carbon::parse($registro->created_at)->format('d/m/Y'))
            ->editColumn('updated_at', fn($registro) => Carbon::parse($registro->updated_at)->format('d/m/Y'))
            ->addColumn('action', function ($row) {
                return view('entidad.action', [
                    'row' => $row,
                    'entidad' => $this->entidad,
                    'camposOcultos' => $this->camposOcultos,
            ])->render();
        })
        ->setRowId('id')
        ->with([
            'camposVisibles' => $this->camposVisibles,
            'camposOcultos' => $this->camposOcultos
        ]);

        // Solo añadir columna expandir si hay campos ocultos
        if (!empty($this->camposOcultos)) {
            $dataTable->addColumn('expandir', function ($row) {
                return '<button data-id="'.$row->id.'" class="btn-expand-row px-2 py-1 bg-gray-200 rounded hover:bg-gray-300" title="Ver más">🔽</button>';
            });

            $dataTable->rawColumns(['expandir', 'action']);
        } else {
            $dataTable->rawColumns(['action']);
        }

        return $dataTable;
    }


    /**
     * Columnas del DataTable.
     */
    public function getColumns(): array
    {
        $columnas = [];
        // Si existen camposOcultos muestro botón expandir
        if (!empty($this->camposOcultos)) {
            $columnas[] = Column::computed('expandir')
                ->exportable(false)
                ->printable(false)
                ->width(50)
                ->addClass('text-center')
                ->title(''); // Sin título para mantenerlo compacto
        }
        // Resto de columnas
        $columnas[] = Column::make('id');
        // Genero las columnas dinámicamente según los campos visibles
        foreach ($this->camposVisibles as $campo) {
            $columnas[] = Column::make($campo);
        }
        $columnas[] = Column::make('created_at')->title('Creado');
        $columnas[] = Column::make('updated_at')->title('Actualizado');

        //Botones en linea de registro (editar/eliminar)
        $columnas[] = Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(150)
            ->addClass('text-center');

        return $columnas;
    }


    /**
     * Configuración HTML del DataTable.
     */
    public function html(): HtmlBuilder
    {
        $tailwindStyle = 'inline-block px-4 py-2 text-sm font-semibold text-white bg-gray-600 rounded hover:bg-gray-700 active:scale-95 transform transition duration-100 ease-in-out cursor-pointer';

        return $this->builder()
            ->setTableId($this->entidad . '-table') // Ej: 'clientes-table'
            ->columns($this->getColumns()) // Columnas definidas en getColumns()
            ->minifiedAjax() // Usa AJAX para cargar datos
            ->orderBy(1) // Ordena por la primera columna (ID)
            ->selectStyleSingle() // Permite seleccionar una fila a la vez
            //->dom('Bfrtip') // Define la estructura del DataTable con botones, filtro, tabla, páginación,.. Metodo antiguo
            ->dom('<"flex justify-between items-center mb-4" Bfl>rtip')
            /* Estructura personalizada con Tailwind CSS:
                • 	"flex justify-between items-center mb-4": crea un contenedor con clases Tailwind.
                • 	B: muestra los botones de exportación.
                • 	f: muestra el buscador.
                • 	l: muestra el selector de cantidad de registros.
                • 	r, t, i, p: mantienen el resto del layout (procesando, tabla, info, paginación).*/

            ->buttons([ //inyecto los botones de exportación usando estilos de Tailwind CSS
                Button::make('excel')->text('Excel')->className($tailwindStyle),
                Button::make('csv')->text('CSV')->className($tailwindStyle),
                Button::make('pdf')->text('PDF')->className($tailwindStyle),
                Button::make('print')->text('Imprimir')->className($tailwindStyle),
            ])
            ->parameters([
                'responsive' => true,
                'colReorder' => true,
                'language' => [
                    //'url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    //'url' => asset('/datatables/i18n/es.json')
                ],
                'lengthMenu' => [ [5, 10, 20, 50, -1], [5, 10, 20, 50, 'Todos'] ],
                'pageLength' => 10, // valor inicial por defecto
            ]);
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
     * Nombre del archivo exportado.
     */
    protected function filename(): string
    {
        return ucfirst($this->entidad) . '_' . date('YmdHis');
    }
}
