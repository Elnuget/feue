<x-app-layout>
    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de error -->
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tarjeta principal -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 transition duration-300 hover:shadow-lg"
            >
                <!-- Encabezado de la Tarjeta (opcional) -->
                <div class="mb-4">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-gray-100">
                        Registrar Matrícula
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base mt-1">
                        Completa la siguiente información para registrar una nueva matrícula.
                    </p>
                </div>

                <form
                    action="{{ route('matriculas.store') }}"
                    method="POST"
                    class="space-y-6"
                >
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">

                        <!-- Usuario -->
                        <div class="relative">
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Usuario
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-user"></i>
                                </span>
                                @if(auth()->user()->hasRole(1))
                                    <select
                                        name="usuario_id"
                                        id="usuario_id"
                                        required
                                        class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600
                                        dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    >
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">
                                                {{ $usuario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input
                                        type="text"
                                        name="usuario_name"
                                        id="usuario_name"
                                        value="{{ auth()->user()->name }}"
                                        readonly
                                        class="flex-1 block w-full rounded-none rounded-r-md border-gray-300
                                        dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                                        focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    >
                                    <input
                                        type="hidden"
                                        name="usuario_id"
                                        id="usuario_id"
                                        value="{{ auth()->id() }}"
                                    >
                                @endif
                            </div>
                        </div>

                        <!-- Tipo de Curso -->
                        <div class="relative">
                            <label for="tipo_curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Sede
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-list-alt"></i>
                                </span>
                                <select
                                    name="tipo_curso_id"
                                    id="tipo_curso_id"
                                    required
                                    class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600
                                    dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    onchange="handleTipoCursoChange()"
                                >
                                    <option value="">Selecciona una Sede</option>
                                    @foreach($tiposCursos as $tipoCurso)
                                        <option
                                            value="{{ $tipoCurso->id }}"
                                            {{ isset($tipoCursoSeleccionado) && $tipoCursoSeleccionado == $tipoCurso->id ? 'selected' : '' }}
                                        >
                                            {{ $tipoCurso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Curso (oculto por defecto) -->
                        <div class="relative" id="curso_container" style="display: none;">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Curso
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-book"></i>
                                </span>
                                <select
                                    name="curso_id"
                                    id="curso_id"
                                    required
                                    class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600
                                    dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    onchange="updateCoursePrice()"
                                >
                                    <!-- Se llenará dinámicamente con JavaScript -->
                                </select>
                            </div>
                        </div>

                        <!-- Fecha de Matricula -->
                        <div class="relative">
                            <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Fecha de Matrícula
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input
                                    type="date"
                                    name="fecha_matricula"
                                    id="fecha_matricula"
                                    value="{{ \Carbon\Carbon::now('America/Guayaquil')->format('Y-m-d') }}"
                                    required
                                    class="flex-1 block w-full rounded-none rounded-r-md border-gray-300
                                    dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                                    focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                >
                            </div>
                        </div>

                        <!-- Monto Total -->
                        <div class="relative">
                            <label for="monto_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Monto Total
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input
                                    type="number"
                                    name="monto_total"
                                    id="monto_total"
                                    required
                                    readonly
                                    placeholder="Selecciona un curso"
                                    class="flex-1 block w-full rounded-none rounded-r-md border-gray-300
                                    dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                                    focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                >
                            </div>
                        </div>

                        <!-- Estado de Matrícula -->
                        <div class="relative">
                            <label for="estado_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Estado de Matrícula
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                @if(auth()->user()->hasRole(1))
                                    <select
                                        name="estado_matricula"
                                        id="estado_matricula"
                                        required
                                        class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600
                                        dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    >
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Aprobada">Aprobada</option>
                                        <option value="Completada">Completada</option>
                                        <option value="Rechazada">Rechazada</option>
                                    </select>
                                @else
                                    <input
                                        type="text"
                                        name="estado_matricula"
                                        id="estado_matricula"
                                        value="Pendiente"
                                        readonly
                                        class="flex-1 block w-full rounded-none rounded-r-md border-gray-300
                                        dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                                        focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    >
                                @endif
                            </div>
                        </div>

                        <!-- Universidad a Postular -->
                        <div class="relative" id="universidad-container">
                            <label for="universidad_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Universidad a postular
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50
                                    text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <i class="fas fa-university"></i>
                                </span>
                                <select
                                    name="universidad_id"
                                    id="universidad_id"
                                    class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600
                                    dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                >
                                    @foreach($universidades as $universidad)
                                        <option value="{{ $universidad->id }}">
                                            {{ $universidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para Guardar -->
                    <div class="flex justify-end pt-4">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent
                            rounded-md font-semibold text-xs text-white uppercase tracking-widest
                            hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none
                            focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400
                            focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-800
                            transition duration-150 ease-in-out"
                        >
                            <i class="fas fa-save mr-2"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateCoursePrice() {
            const cursoSelect = document.getElementById('curso_id');
            const selectedOption = cursoSelect.options[cursoSelect.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');
            document.getElementById('monto_total').value = precio;

            const cursoNombre = selectedOption.text.toLowerCase();
            const universidadContainer = document.getElementById('universidad-container');
            // Mostrar u ocultar la lista de universidades si la palabra "preuniversitario" está en el nombre del curso
            if (cursoNombre.includes('preuniversitario')) {
                universidadContainer.style.display = 'block';
            } else {
                universidadContainer.style.display = 'none';
            }
        }

        function handleTipoCursoChange() {
            const tipoCursoSelect = document.getElementById('tipo_curso_id');
            const tipoCursoId = tipoCursoSelect.value;
            const cursoContainer = document.getElementById('curso_container');
            const cursoSelect = document.getElementById('curso_id');

            if (tipoCursoId) {
                // Mostrar el combo de Curso
                cursoContainer.style.display = 'block';

                // Limpiar opciones previas
                cursoSelect.innerHTML = '';

                // Obtener los cursos para el tipo de curso seleccionado
                const cursosPorTipo = @json($cursosPorTipo);

                if (cursosPorTipo[tipoCursoId]) {
                    cursosPorTipo[tipoCursoId].forEach(curso => {
                        if (curso.estado === 'Activo') { // Use 'Activo' here
                            const option = document.createElement('option');
                            option.value = curso.id;
                            option.text = `${curso.nombre} - ${curso.horario}`;
                            option.setAttribute('data-precio', curso.precio);

                            // Si existe un curso previamente seleccionado
                            if (curso.id == {{ $cursoSeleccionado ?? 'null' }}) {
                                option.selected = true;
                            }
                            cursoSelect.add(option);
                        }
                    });

                    // Actualizar el precio del curso
                    updateCoursePrice();
                } else {
                    // No hay cursos para este tipo de curso
                    document.getElementById('monto_total').value = '';
                }
            } else {
                // Ocultar el combo de Curso si no se ha seleccionado un tipo de curso
                cursoContainer.style.display = 'none';
                cursoSelect.innerHTML = '';
                document.getElementById('monto_total').value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Por defecto, ocultar el combo de curso hasta que se seleccione un tipo de curso
            document.getElementById('curso_container').style.display = 'none';

            // Si se proporcionó un tipo de curso seleccionado, llamar la función para mostrarlo
            if ({{ $tipoCursoSeleccionado ?? 'null' }}) {
                handleTipoCursoChange();
            }
        });
    </script>
</x-app-layout>
