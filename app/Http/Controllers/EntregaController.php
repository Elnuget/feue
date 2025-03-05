<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EntregaController extends Controller
{
    public function store(Request $request, Tarea $tarea)
    {
        $request->validate([
            'archivo' => 'nullable|file|max:10240', // 10MB máximo
            'enlace' => 'nullable|url'
        ]);

        // Verificar que al menos se haya proporcionado un archivo o un enlace
        if (!$request->hasFile('archivo') && !$request->enlace) {
            return back()->with('error', 'Debes proporcionar un archivo o un enlace.');
        }

        // Verificar que no haya una entrega previa
        if ($tarea->entregas()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Ya has realizado una entrega para esta tarea.');
        }

        $entrega = new Entrega([
            'user_id' => auth()->id(),
            'tarea_id' => $tarea->id
        ]);

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('entregas');
            $entrega->archivo = $path;
        }

        if ($request->enlace) {
            $entrega->enlace = $request->enlace;
        }

        $entrega->save();

        return redirect()->route('aulas_virtuales.show', $tarea->aulaVirtual)
            ->with('success', 'Tarea entregada exitosamente.');
    }

    public function destroy(Entrega $entrega)
    {
        // Verificar que el usuario sea el propietario de la entrega
        if ($entrega->user_id !== auth()->id()) {
            return back()->with('error', 'No tienes permiso para eliminar esta entrega.');
        }

        // Eliminar el archivo si existe
        if ($entrega->archivo) {
            Storage::delete($entrega->archivo);
        }

        $entrega->delete();

        return redirect()->route('aulas_virtuales.show', $entrega->tarea->aulaVirtual)
            ->with('success', 'Entrega eliminada exitosamente.');
    }
} 