    <footer class="text-center text-xs text-gray-500 py-4 border-t mt-8">
        <p>&copy; {{ date('Y') }} {{ env('APP_NAME') }} - Laravel </p>
        <p>
            Aplicación desarrollada por {{ env('APP_AUTHOR') }} — v{{ env('APP_VERSION') }}
        </p>
    </footer>

    <!-- JS jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> {{-- carga la biblioteca DataTables v1.13.6 para jQuery, una herramienta muy potente para convertir tablas HTML en tablas interactivas con funcionalidades avanzadas. --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script> {{-- carga la extensión Buttons de DataTables (versión 2.4.1), que amplía las funcionalidades de las tablas interactivas con botones personalizados. Por ejemplo, los botones de exportación PDF,EXCEL, ... --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script> {{-- carga el adaptador visual de la extensión Buttons de DataTables para integrarse con Bootstrap 5. No añade nuevas funcionalidades por sí solo, pero sí mejora la apariencia y compatibilidad visual de los botones generados por dataTables.buttons.min.js. --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script> {{-- Activa los siguientes botones de exportación:
            - copyHtml5 → Copia los datos al portapapeles.
            - excelHtml5 → Exporta a archivo .xlsx (requiere jszip).
            - csvHtml5 → Exporta a archivo .csv.
            - pdfHtml5 → Exporta a archivo .pdf (requiere pdfmake).
        NOTA: Este módulo no incluye los motores de exportación como jszip o pdfmake. Debes cargarlos por separado si usas excelHtml5 o pdfHtml5.
    --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script> {{-- activa el botón de impresión directa en DataTables. Es parte del módulo Buttons y permite que el usuario imprima la tabla con un diseño optimizado para papel o PDF. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> {{-- carga JSZip, una biblioteca JavaScript que permite crear, leer y manipular archivos ZIP directamente en el navegador. En el contexto de DataTables, JSZip es requisito obligatorio para que el botón excelHtml5 funcione correctamente. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script> {{-- carga pdfmake, una biblioteca JavaScript que permite generar archivos PDF directamente en el navegador, sin necesidad de servidor ni plugins externos. En el contexto de DataTables, es esencial para que el botón pdfHtml5 funcione correctamente. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script> {{-- carga el archivo vfs_fonts.js, que contiene las fuentes embebidas necesarias para que pdfmake pueda generar archivos PDF en el navegador. --}}
    
    <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script> {{-- carga el módulo ColReorder de DataTables (versión 1.6.2), que permite a los usuarios reordenar las columnas de una tabla HTML de forma interactiva, simplemente arrastrándolas con el ratón --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> {{-- Carga alpine para usarlo en los botones desplegables de welcome.blade
    ¿Que permite hacer:  Reactividad declarativa en HTML
        - x-data: define el estado local de un componente.
        - x-show, x-if: controlan visibilidad condicional.
        - x-bind: enlaza atributos dinámicamente.
        - x-on / @: escucha eventos (@click, @keydown, etc.).
        - x-model: enlaza inputs bidireccionalmente.
        - x-transition: animaciones suaves al mostrar/ocultar elementos.
    --}}
    @yield('scripts')
</body>
</html> 