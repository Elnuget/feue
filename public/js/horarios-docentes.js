function editHorario(id) {
    $.ajax({
        url: `/horarios-docentes/${id}/edit`,
        type: 'GET',
        success: function(response) {
            $('#edit_id').val(response.id);
            $('#edit_user_id').val(response.user_id);
            $('#edit_curso_id').val(response.curso_id);
            $('#edit_dia_semana').val(response.dia_semana);
            $('#edit_hora_inicio').val(response.hora_inicio);
            $('#edit_hora_fin').val(response.hora_fin);
            $('#edit_aula').val(response.aula);
            $('#editModal').modal('show');
        },
        error: function(xhr) {
            alert('Error al cargar los datos');
        }
    });
}

$(document).ready(function() {
    $('#editHorarioForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();
        
        $.ajax({
            url: `/horarios-docentes/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error al actualizar el horario');
            }
        });
    });
});
