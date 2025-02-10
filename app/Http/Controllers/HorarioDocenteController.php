// ...existing code...

public function edit($id)
{
    $horario = HorarioDocente::findOrFail($id);
    return response()->json($horario);
}

public function update(Request $request, $id)
{
    $horario = HorarioDocente::findOrFail($id);
    
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'curso_id' => 'required|exists:cursos,id',
        'dia_semana' => 'required|integer|between:1,7',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        'aula' => 'nullable|string|max:100',
    ]);

    $horario->update($validated);
    
    return response()->json(['message' => 'Horario actualizado correctamente']);
}

// ...existing code...
