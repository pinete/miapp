<?php
namespace App\Http\Controllers;
use App\Models\Adjunto;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class AdjuntoController extends Controller
{
    /** Filtra los adjuntos de un registro de una entidad */
    public function filtrados(Request $request)
    {
        $entidad = $request->get('entidad');
        $id = $request->get('id');

        if (! $entidad || ! $id) {
            return response()->json([], 400); // o puedes devolver todos si lo prefieres
        }

        $modelo = 'App\\Models\\' . ucfirst(Str::singular($entidad));

        $adjuntos = Adjunto::where('adjuntable_type', $modelo)
                        ->where('adjuntable_id', $id);

        return DataTables::of($adjuntos)->toJson();
    }

    /** Elimina un adjunto */
    public function destroy($id)
    {
        try {
            $adjunto = Adjunto::findOrFail($id);
            $adjunto->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar adjunto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el adjunto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar un archivo adjunto
     */
    public function descargar($id)
    {
        $adjunto = Adjunto::findOrFail($id);

        if (! $adjunto->contenido) {
            return response()->json(['error' => 'Contenido no disponible'], 404);
        }

        return response($adjunto->contenido)
            ->header('Content-Type', $adjunto->mime)
            ->header('Content-Disposition', 'attachment; filename="' . $adjunto->nombre . '"');
    }
}
