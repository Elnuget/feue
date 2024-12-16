<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Pruebas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Nueva Tarjeta de Asistencias y Datos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Asistencias y Datos</h2>
                    <div class="mb-4">
                        <label for="usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Seleccionar Usuario
                        </label>
                        <div class="flex items-center gap-4">
                            <div id="user-photo" class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>
                            </div>
                            <select id="usuario" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            data-photo="{{ $user->profile && $user->profile->photo ? asset('storage/' . $user->profile->photo) : '' }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="datos-usuario" class="hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cursos Matriculados -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <i class="fas fa-graduation-cap mr-2"></i>Cursos Matriculados
                                </h3>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <ul id="cursos-matriculados" class="list-disc list-inside text-gray-600 dark:text-gray-300">
                                        <li class="text-sm italic text-gray-400">No hay cursos registrados</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Número de Asistencias -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>Asistencias Registradas
                                </h3>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <div class="flex items-baseline">
                                        <span class="text-3xl font-bold text-blue-600 dark:text-blue-400" id="numero-asistencias">0</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">asistencias totales</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Valores Pendientes -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <i class="fas fa-dollar-sign mr-2"></i>Valores Pendientes
                                </h3>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <div class="flex items-baseline">
                                        <span class="text-2xl font-bold text-red-600 dark:text-red-400">$</span>
                                        <span class="text-2xl font-bold text-red-600 dark:text-red-400" id="valores-pendientes">0.00</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">en matrículas</p>
                                </div>
                            </div>

                            <!-- Estado de Matrículas -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <i class="fas fa-clipboard-check mr-2"></i>Estado de Matrículas
                                </h3>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <ul id="estado-matriculas" class="space-y-2 text-gray-600 dark:text-gray-300">
                                        <li class="text-sm italic text-gray-400">No hay matrículas registradas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-full bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <i class="fas fa-id-card mr-2"></i>Matrículas del Usuario
                                </h3>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100 dark:bg-gray-600">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Usuario</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Curso</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto Total</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Valor Pendiente</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="matriculas-usuario" class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                                <tr>
                                                    <td colspan="7" class="px-4 py-2 text-sm italic text-gray-400 dark:text-gray-400">No hay matrículas registradas</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Valor Pendiente</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($matriculas as $matricula)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ optional($matricula->usuario)->name }}</td>
                                        <td class="px-4 py-2">{{ optional($matricula->curso)->nombre }}</td>
                                        <td class="px-4 py-2">{{ $matricula->fecha_matricula }}</td>
                                        <td class="px-4 py-2">${{ number_format($matricula->valor_pendiente, 2) }}</td>
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
    // Declaración única de todas las constantes necesarias
    const users = @json($users);
    const cursos = @json($cursos);
    const asistencias = @json($asistencias);
    const matriculas = @json($matriculas);
    const listas = @json($listas);

    // Referencias a elementos del DOM
    const anioSelect = document.getElementById('anio');
    const mesSelect = document.getElementById('mes');
    const cursoSelect = document.getElementById('curso');
    const tablaAsistencias = document.getElementById('tabla-asistencias');
    const usuarioSelect = document.getElementById('usuario');
    const datosUsuarioDiv = document.getElementById('datos-usuario');
    const cursosMatriculadosUl = document.getElementById('cursos-matriculados');
    const numeroAsistenciasP = document.getElementById('numero-asistencias');
    const valoresPendientesP = document.getElementById('valores-pendientes');
    const estadoMatriculasUl = document.getElementById('estado-matriculas');
    const matriculasUsuarioTbody = document.getElementById('matriculas-usuario');
    const scannerStatus = document.getElementById('scanner-status');
    const userPhotoDiv = document.getElementById('user-photo');

    // Deshabilitar mes y curso al inicio
    mesSelect.disabled = true;
    cursoSelect.disabled = true;

    // Event Listeners
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

    function actualizarFotoPerfil(photoUrl) {
        if (photoUrl) {
            // Verificar si la imagen existe
            fetch(photoUrl)
                .then(response => {
                    if (response.ok) {
                        userPhotoDiv.innerHTML = `<img src="${photoUrl}" alt="Foto de perfil" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-2xl text-gray-600 dark:text-gray-400\'>👤</span>';">`;
                    } else {
                        userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
                    }
                })
                .catch(() => {
                    userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
                });
        } else {
            userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
        }
    }

    usuarioSelect.addEventListener('change', function() {
        const userId = parseInt(usuarioSelect.value);
        if (userId) {
            // Actualizar foto de perfil
            const selectedOption = usuarioSelect.options[usuarioSelect.selectedIndex];
            const photoUrl = selectedOption.dataset.photo;
            actualizarFotoPerfil(photoUrl);

            // Filtrar matrículas del usuario seleccionado
            const userMatriculas = matriculas.filter(m => m.usuario && m.usuario.id === userId);
            
            // Actualizar cursos matriculados
            cursosMatriculadosUl.innerHTML = '';
            if (userMatriculas.length > 0) {
                userMatriculas.forEach(matricula => {
                    if (matricula.curso) {
                        cursosMatriculadosUl.innerHTML += `
                            <li class="text-sm text-gray-600 dark:text-gray-300">${matricula.curso.nombre}</li>
                        `;
                    }
                });
            } else {
                cursosMatriculadosUl.innerHTML = `
                    <li class="text-sm italic text-gray-400">No hay cursos registrados</li>
                `;
            }

            // Actualizar número de asistencias
            const userAsistencias = asistencias.filter(a => a.user_id === userId);
            numeroAsistenciasP.textContent = userAsistencias.length;

            // Calcular y mostrar valores pendientes
            const totalPendiente = userMatriculas.reduce((total, matricula) => {
                return total + (parseFloat(matricula.valor_pendiente) || 0);
            }, 0);
            valoresPendientesP.textContent = totalPendiente.toFixed(2);

            // Actualizar estados de matrículas
            estadoMatriculasUl.innerHTML = '';
            if (userMatriculas.length > 0) {
                userMatriculas.forEach(matricula => {
                    if (matricula.curso) {
                        const estadoClass = 
                            matricula.estado_matricula === 'Aprobada' ? 'text-green-600' :
                            matricula.estado_matricula === 'Pendiente' ? 'text-yellow-600' :
                            'text-red-600';
                        estadoMatriculasUl.innerHTML += `
                            <li class="text-sm">
                                <span class="text-gray-600 dark:text-gray-300">${matricula.curso.nombre}:</span>
                                <span class="${estadoClass} font-medium"> ${matricula.estado_matricula}</span>
                            </li>
                        `;
                    }
                });
            } else {
                estadoMatriculasUl.innerHTML = `
                    <li class="text-sm italic text-gray-400">No hay matrículas registradas</li>
                `;
            }

            // Actualizar tabla de matrículas
            matriculasUsuarioTbody.innerHTML = '';
            if (userMatriculas.length > 0) {
                userMatriculas.forEach(matricula => {
                    matriculasUsuarioTbody.innerHTML += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-300">${matricula.id}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-300">${matricula.usuario.name}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-300">${matricula.curso.nombre}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-300">${matricula.fecha_matricula}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-300">$${parseFloat(matricula.monto_total).toFixed(2)}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-300">$${parseFloat(matricula.valor_pendiente).toFixed(2)}</td>
                            <td class="px-4 py-2 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    ${matricula.estado_matricula === 'Aprobada' ? 'bg-green-100 text-green-800' : 
                                    matricula.estado_matricula === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-red-100 text-red-800'}">
                                    ${matricula.estado_matricula}
                                </span>
                            </td>
                        </tr>
                    `;
                });
            } else {
                matriculasUsuarioTbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-sm italic text-gray-400 dark:text-gray-400">No hay matrículas registradas</td>
                    </tr>
                `;
            }

            datosUsuarioDiv.classList.remove('hidden');
        } else {
            userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
            datosUsuarioDiv.classList.add('hidden');
        }
    });

    // Funciones
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
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        mirror: false,
        scanPeriod: 5
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
