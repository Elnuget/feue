<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Pruebas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <!-- Los días se agregarán dinámicamente -->
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Las filas se agregarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Asistencias Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Asistencias</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Usuario</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Fecha y Hora</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($asistencias as $asistencia)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ optional($asistencia->user)->name }}</td>
                                        <td class="px-4 py-2">{{ $asistencia->fecha_hora }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Listas Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Listas de Matriculados</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border-b">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border-b">Nombre del Matriculado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border-b">Curso</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($listas as $index => $lista)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b">{{ optional($lista->usuario)->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ optional($lista->curso)->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Matrículas Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Matrículas</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Usuario</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Curso</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Fecha</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($matriculas as $matricula)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ optional($matricula->usuario)->name }}</td>
                                        <td class="px-4 py-2">{{ optional($matricula->curso)->nombre }}</td>
                                        <td class="px-4 py-2">{{ $matricula->fecha_matricula }}</td>
                                        <td class="px-4 py-2">{{ $matricula->estado_matricula }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cursos Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Cursos</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Nombre</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Descripción</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Estado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Horario</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cursos as $curso)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $curso->nombre }}</td>
                                        <td class="px-4 py-2">{{ $curso->descripcion }}</td>
                                        <td class="px-4 py-2">{{ $curso->estado }}</td>
                                        <td class="px-4 py-2">{{ $curso->horario }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Agrega los scripts necesarios para el escáner QR -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>
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
            const headerRow = tablaAsistencias.querySelector('thead tr');
            headerRow.innerHTML = `
                <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
            `;

            // Agregar columnas de días
            for (let dia = 1; dia <= diasEnMes; dia++) {
                headerRow.innerHTML += `
                    <th class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-8">${dia}</th>
                `;
            }

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

    // Configuración del escáner QR
    const scannerStatus = document.getElementById('scanner-status');
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        mirror: false, // Deshabilitar efecto espejo
        scanPeriod: 5 // Escanear cada 5 ms
    });

    scanner.addListener('scan', function (content) {
        scannerStatus.textContent = '¡Código QR detectado!';
        // Enviar el contenido escaneado al servidor
        window.location.href = '/asistencias/register-scan?data=' + encodeURIComponent(content);
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            // Intentar usar la cámara trasera primero
            const rearCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
            scanner.start(rearCamera || cameras[0]);
            scannerStatus.textContent = 'Escáner QR activo';
        } else {
            scannerStatus.textContent = 'No se encontraron cámaras disponibles';
        }
    }).catch(function (e) {
        scannerStatus.textContent = 'Error al acceder a la cámara: ' + e;
        console.error(e);
    });
});
</script>
