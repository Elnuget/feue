<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asistencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex space-x-4 mb-4">
                        <a href="{{ route('asistencias.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Registrar Asistencia Manual
                        </a>
                        <a href="{{ route('asistencias.scan') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            📷 Escanear QR
                        </a>
                    </div>

                    <!-- Nueva Tarjeta de Asistencias Mensuales -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">Asistencias Mensuales</h2>
                            <div class="flex gap-4 mb-4">
                                <select id="anio" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Seleccione un año</option>
                                    @for($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>

                                <select id="mes" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" disabled>
                                    <option value="">Seleccione un mes</option>
                                    @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $mes)
                                        <option value="{{ $index + 1 }}">{{ $mes }}</option>
                                    @endforeach
                                </select>

                                <select id="curso" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" disabled>
                                    <option value="">Seleccione un curso</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tabla de asistencias mensuales -->
                            <div id="tabla-asistencias" class="hidden overflow-x-auto mt-4">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead class="bg-gray-50">
                                        <!-- Aquí se agregarán dinámicamente las cabeceras de los días -->
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Aquí se agregarán dinámicamente las filas de usuarios y asistencias -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Agrega los scripts necesarios para la funcionalidad de Asistencias Mensuales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anioSelect = document.getElementById('anio');
    const mesSelect = document.getElementById('mes');
    const cursoSelect = document.getElementById('curso');
    const tablaAsistencias = document.getElementById('tabla-asistencias');

    // Deshabilitar mes y curso al inicio
    mesSelect.disabled = true;
    cursoSelect.disabled = true;

    // Obtener las listas de usuarios matriculados y asistencias
    const listas = @json($listas);
    const asistencias = @json($asistencias);

    anioSelect.addEventListener('change', function() {
        if (anioSelect.value) {
            mesSelect.disabled = false;
        } else {
            mesSelect.disabled = true;
            mesSelect.value = '';
            cursoSelect.disabled = true;
            cursoSelect.value = '';
            tablaAsistencias.classList.add('hidden');
        }
    });

    mesSelect.addEventListener('change', function() {
        if (mesSelect.value) {
            cursoSelect.disabled = false;
        } else {
            cursoSelect.disabled = true;
            cursoSelect.value = '';
            tablaAsistencias.classList.add('hidden');
        }
    });

    cursoSelect.addEventListener('change', function() {
        if (cursoSelect.value) {
            actualizarTabla();
        } else {
            tablaAsistencias.classList.add('hidden');
        }
    });

    function actualizarTabla() {
        const mes = parseInt(mesSelect.value);
        const anio = parseInt(anioSelect.value);
        const cursoId = parseInt(cursoSelect.value);

        if (mes && anio && cursoId) {
            // Mostrar la tabla
            tablaAsistencias.classList.remove('hidden');

            const diasEnMes = new Date(anio, mes, 0).getDate();
            const headerRow = tablaAsistencias.querySelector('thead');
            headerRow.innerHTML = `
                <tr>
                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <!-- Días del mes -->
                    ${Array.from({ length: diasEnMes }, (_, i) => `
                        <th class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-8">${i + 1}</th>
                    `).join('')}
                </tr>
            `;

            // Filtrar usuarios matriculados en el curso seleccionado
            const usuariosMatriculados = listas.filter(lista => lista.curso_id === cursoId);
            const tbody = tablaAsistencias.querySelector('tbody');
            tbody.innerHTML = '';

            usuariosMatriculados.forEach((lista, index) => {
                const user = lista.usuario;

                // Filtrar asistencias del usuario en el mes y año seleccionados
                const asistenciasUsuario = asistencias.filter(asistencia => {
                    return asistencia.user_id === user.id &&
                        new Date(asistencia.fecha_hora).getFullYear() === anio &&
                        new Date(asistencia.fecha_hora).getMonth() + 1 === mes;
                });

                // Crear un array de días con asistencia
                const diasConAsistencia = asistenciasUsuario.map(asistencia => new Date(asistencia.fecha_hora).getDate());

                let row = `
                    <tr>
                        <td class="px-2 py-2 text-center text-sm text-gray-900">${index + 1}</td>
                        <td class="px-4 py-2 text-left text-sm text-gray-900">${user ? user.name : 'N/A'}</td>
                `;

                // Agregar celdas para cada día
                for (let dia = 1; dia <= diasEnMes; dia++) {
                    if (diasConAsistencia.includes(dia)) {
                        // Marcar con un check
                        row += `
                            <td class="px-1 py-2 text-center text-sm text-green-500">✔️</td>
                        `;
                    } else {
                        row += `
                            <td class="px-1 py-2 text-center text-sm text-gray-900"></td>
                        `;
                    }
                }

                row += '</tr>';
                tbody.innerHTML += row;
            });
        }
    }
});
</script>