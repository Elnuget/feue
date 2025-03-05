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
            'imagenes.*' => 'nullable|image|max:10240', // 10MB máximo por imagen
            'estado' => 'required|in:activo,inactivo',
            'enlaces.*' => 'nullable|url' // Validar enlaces
        ]);

        $data = $request->except(['archivos', 'imagenes', 'enlaces']);
        $archivos = [];
        $imagenes = [];
        
        // Procesamiento de enlaces
        $enlaces = array_filter($request->input('enlaces', [])); // Eliminar enlaces vacíos
        $data['enlaces'] = !empty($enlaces) ? $enlaces : null;

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
            'imagenes.*' => 'nullable|image|max:10240', // 10MB máximo por imagen
            'estado' => 'required|in:activo,inactivo',
            'enlaces.*' => 'nullable|url' // Validar enlaces
        ]);

        $data = $request->except(['archivos', 'imagenes', 'enlaces']);
        $archivos = $tarea->archivos ?? [];
        $imagenes = $tarea->imagenes ?? [];
        
        // Procesamiento de enlaces
        $enlaces = array_filter($request->input('enlaces', [])); // Eliminar enlaces vacíos
        $data['enlaces'] = !empty($enlaces) ? $enlaces : null;

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

    public function toggleEstado(Tarea $tarea)
    {
        // Verificamos que la tarea exista y tenga un estado
        if (!$tarea || !isset($tarea->estado)) {
            return redirect()->back()->with('error', 'No se pudo encontrar la tarea o su estado.');
        }

        // Cambiar estado: activo -> inactivo, inactivo -> activo
        $estadoActual = $tarea->estado;
        $nuevoEstado = $estadoActual === 'activo' ? 'inactivo' : 'activo';
        
        // Actualizar el estado
        $tarea->estado = $nuevoEstado;
        $result = $tarea->save();
        
        if (!$result) {
            return redirect()->back()->with('error', 'No se pudo actualizar el estado de la tarea.');
        }
        
        $mensaje = $nuevoEstado === 'activo' 
            ? "Tarea activada exitosamente. Estado anterior: {$estadoActual}, Nuevo estado: {$nuevoEstado}" 
            : "Tarea desactivada exitosamente. Estado anterior: {$estadoActual}, Nuevo estado: {$nuevoEstado}";
        
        return redirect()->route('aulas_virtuales.show', $tarea->aulaVirtual)
            ->with('success', $mensaje);
    }
} 