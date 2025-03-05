<?php

namespace App\Http\Controllers;

use App\Models\AulaVirtual;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
{
    public function store(Request $request, AulaVirtual $aula)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'required|date',
            'puntos_maximos' => 'required|integer|min:0',
            'archivos.*' => 'nullable|file|max:10240', // 10MB máximo por archivo
            'imagenes.*' => 'nullable|image|max:10240' // 10MB máximo por imagen
        ]);

        $data = $request->except(['archivos', 'imagenes']);
        $archivos = [];
        $imagenes = [];

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('tareas/archivos', 'public');
                $archivos[] = $path;
            }
        }

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('tareas/imagenes', 'public');
                $imagenes[] = $path;
            }
        }

        $data['archivos'] = $archivos;
        $data['imagenes'] = $imagenes;
        
        $tarea = $aula->tareas()->create($data);

        return redirect()->route('aulas_virtuales.show', $aula)
            ->with('success', 'Tarea creada exitosamente.');
    }

    public function edit(Tarea $tarea)
    {
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'required|date',
            'puntos_maximos' => 'required|integer|min:0',
            'archivos.*' => 'nullable|file|max:10240', // 10MB máximo por archivo
            'imagenes.*' => 'nullable|image|max:10240' // 10MB máximo por imagen
        ]);

        $data = $request->except(['archivos', 'imagenes']);
        $archivos = $tarea->archivos ?? [];
        $imagenes = $tarea->imagenes ?? [];

        if ($request->hasFile('archivos')) {
            // Eliminar archivos antiguos
            foreach ($archivos as $archivo) {
                Storage::disk('public')->delete($archivo);
            }

            // Guardar nuevos archivos
            $archivos = [];
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('tareas/archivos', 'public');
                $archivos[] = $path;
            }
        }

        if ($request->hasFile('imagenes')) {
            // Eliminar imágenes antiguas
            foreach ($imagenes as $imagen) {
                Storage::disk('public')->delete($imagen);
            }

            // Guardar nuevas imágenes
            $imagenes = [];
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('tareas/imagenes', 'public');
                $imagenes[] = $path;
            }
        }

        $data['archivos'] = $archivos;
        $data['imagenes'] = $imagenes;
        $tarea->update($data);

        return redirect()->route('aulas_virtuales.show', $tarea->aulaVirtual)
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Tarea $tarea)
    {
        // Eliminar archivos e imágenes
        if ($tarea->archivos) {
            foreach ($tarea->archivos as $archivo) {
                Storage::disk('public')->delete($archivo);
            }
        }

        if ($tarea->imagenes) {
            foreach ($tarea->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen);
            }
        }

        $aula = $tarea->aulaVirtual;
        $tarea->delete();

        return redirect()->route('aulas_virtuales.show', $aula)
            ->with('success', 'Tarea eliminada exitosamente.');
    }

    public function calificar(Request $request, Tarea $tarea, Entrega $entrega)
    {
        $request->validate([
            'calificacion' => 'required|numeric|min:0|max:' . $tarea->puntos_maximos,
            'comentarios' => 'nullable|string'
        ]);

        $entrega->update([
            'calificacion' => $request->calificacion,
            'comentarios' => $request->comentarios
        ]);

        return redirect()->route('aulas_virtuales.show', $tarea->aulaVirtual)
            ->with('success', 'Tarea calificada exitosamente.');
    }
} 