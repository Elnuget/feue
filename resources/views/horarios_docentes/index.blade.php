<!-- Modal de Edición -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editHorarioForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="form-group">
                        <label>Docente</label>
                        <select class="form-control" id="edit_user_id" name="user_id" required>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Curso</label>
                        <select class="form-control" id="edit_curso_id" name="curso_id" required>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Día de la Semana</label>
                        <select class="form-control" id="edit_dia_semana" name="dia_semana" required>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Hora de Inicio</label>
                        <input type="time" class="form-control" id="edit_hora_inicio" name="hora_inicio" required>
                    </div>

                    <div class="form-group">
                        <label>Hora de Fin</label>
                        <input type="time" class="form-control" id="edit_hora_fin" name="hora_fin" required>
                    </div>

                    <div class="form-group">
                        <label>Aula</label>
                        <input type="text" class="form-control" id="edit_aula" name="aula">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/horarios-docentes.js') }}"></script>
@endpush
