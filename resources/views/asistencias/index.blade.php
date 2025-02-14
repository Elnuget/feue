<x-app-layout>
    @section('page_title', 'Asistencias')
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

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
                        <button id="export-excel" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Exportar a Excel
                        </button>
                    </div>

                    <h2 class="text-xl font-semibold mb-4">Asistencias Mensuales</h2>
                    <div class="flex gap-4 mb-4">
                        <select id="anio" class="w-1/4 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">Seleccione un año</option>
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>

                        <select id="mes" class="w-1/4 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">Seleccione un mes</option>
                            @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $mes)
                                <option value="{{ $index + 1 }}" {{ $index + 1 == date('n') ? 'selected' : '' }}>{{ $mes }}</option>
                            @endforeach
                        </select>

                        <select id="tipo_curso" class="w-1/4 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">Seleccione un tipo de curso</option>
                            @foreach($tiposCursos as $tipoCurso)
                                <option value="{{ $tipoCurso->id }}">{{ $tipoCurso->nombre }}</option>
                            @endforeach
                        </select>

                        <select id="curso" class="w-1/4 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" disabled>
                            <option value="">Seleccione un curso</option>
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
</x-app-layout>

<!-- Include SheetJS library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Agrega los scripts necesarios para la funcionalidad de Asistencias Mensuales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anioSelect = document.getElementById('anio');
    const mesSelect = document.getElementById('mes');
    const tipoCursoSelect = document.getElementById('tipo_curso');
    const cursoSelect = document.getElementById('curso');
    const tablaAsistencias = document.getElementById('tabla-asistencias');

    // Obtener los cursos
    const cursos = @json($cursos);

    anioSelect.addEventListener('change', resetSelections);
    mesSelect.addEventListener('change', resetSelections);

    function resetSelections() {
        if (anioSelect.value && mesSelect.value) {
            tipoCursoSelect.disabled = false;
            tipoCursoSelect.value = '';
            cursoSelect.disabled = true;
            cursoSelect.value = '';
            tablaAsistencias.classList.add('hidden');
        } else {
            tipoCursoSelect.disabled = true;
            tipoCursoSelect.value = '';
            cursoSelect.disabled = true;
            cursoSelect.value = '';
            tablaAsistencias.classList.add('hidden');
        }
    }

    tipoCursoSelect.addEventListener('change', function() {
        if (tipoCursoSelect.value) {
            cursoSelect.disabled = false;
            const cursosFiltrados = cursos.filter(curso => curso.tipo_curso_id == tipoCursoSelect.value);
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
            cursosFiltrados.forEach(curso => {
                cursoSelect.innerHTML += `<option value="${curso.id}">${curso.nombre} (${curso.horario})</option>`;
            });
        } else {
            cursoSelect.disabled = true;
            cursoSelect.value = '';
            tablaAsistencias.classList.add('hidden');
        }
    });

    cursoSelect.addEventListener('change', function() {
        if (cursoSelect.value) {
            cargarDatosAsistencia();
        } else {
            tablaAsistencias.classList.add('hidden');
        }
    });

    async function cargarDatosAsistencia() {
        const mes = parseInt(mesSelect.value);
        const anio = parseInt(anioSelect.value);
        const cursoId = parseInt(cursoSelect.value);

        if (!mes || !anio || !cursoId) return;

        try {
            // Mostrar indicador de carga
            tablaAsistencias.innerHTML = '<div class="text-center py-4">Cargando datos...</div>';
            tablaAsistencias.classList.remove('hidden');

            const response = await fetch('/asistencias/get-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ anio, mes, curso_id: cursoId })
            });

            if (!response.ok) throw new Error('Error al cargar los datos');

            const data = await response.json();
            actualizarTabla(data.matriculas, data.asistencias, mes, anio);
        } catch (error) {
            console.error('Error:', error);
            tablaAsistencias.innerHTML = '<div class="text-center py-4 text-red-500">Error al cargar los datos</div>';
        }
    }

    function actualizarTabla(matriculas, asistencias, mes, anio) {
        const diasEnMes = new Date(anio, mes, 0).getDate();
        
        // Crear estructura de la tabla
        let tablaHTML = `
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        ${Array.from({ length: diasEnMes }, (_, i) => `
                            <th class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-8">${i + 1}</th>
                        `).join('')}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;

        // Generar filas de la tabla
        matriculas.forEach((matricula, index) => {
            const asistenciasUsuario = asistencias.filter(a => a.user_id === matricula.usuario.id);
            const diasConAsistencia = asistenciasUsuario.map(a => new Date(a.fecha_hora).getDate());

            tablaHTML += `
                <tr>
                    <td class="px-2 py-2 text-center text-sm text-gray-900">${index + 1}</td>
                    <td class="px-4 py-2 text-left text-sm text-gray-900">${matricula.usuario.name}</td>
                    ${Array.from({ length: diasEnMes }, (_, dia) => `
                        <td class="px-1 py-2 text-center text-sm ${diasConAsistencia.includes(dia + 1) ? 'text-green-500' : 'text-gray-900'}">
                            ${diasConAsistencia.includes(dia + 1) ? '✔️' : ''}
                        </td>
                    `).join('')}
                </tr>
            `;
        });

        tablaHTML += '</tbody></table>';
        tablaAsistencias.innerHTML = tablaHTML;
    }

    // Exportar a Excel
    document.getElementById('export-excel').addEventListener('click', function() {
        const table = document.querySelector('#tabla-asistencias table');
        if (!table) {
            alert('Por favor, primero cargue los datos de asistencia');
            return;
        }

        const tipoCurso = tipoCursoSelect.options[tipoCursoSelect.selectedIndex].text || 'TipoCurso';
        const curso = cursoSelect.options[cursoSelect.selectedIndex].text || 'Curso';
        let titulo = `${tipoCurso}_${curso}`.replace(/\s+/g, '_');
        
        const workbook = XLSX.utils.table_to_book(table, { sheet: 'Asistencias' });
        XLSX.writeFile(workbook, `${titulo}.xlsx`);
    });
});
</script>