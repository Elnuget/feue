<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Matricula') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('matriculas.update', $matricula) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                        
                        <!-- Usuario -->
                        <div class="relative">
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-user"></i>
                                </span>
                                @if(auth()->user()->hasRole(1))
                                    <select name="usuario_id" id="usuario_id" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" {{ $usuario->id == $matricula->usuario_id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="usuario_name" id="usuario_name" value="{{ auth()->user()->name }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                    <input type="hidden" name="usuario_id" id="usuario_id" value="{{ auth()->id() }}">
                                @endif
                            </div>
                        </div>

                        <!-- Sede (Tipo de Curso) -->
                        <div class="relative">
                            <label for="tipo_curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Sede
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-list-alt"></i>
                                </span>
                                <select name="tipo_curso_id" id="tipo_curso_id" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300" onchange="handleTipoCursoChange()">
                                    <option value="">Selecciona una Sede</option>
                                    @foreach($tiposCursos as $tipoCurso)
                                        <option value="{{ $tipoCurso->id }}" {{ isset($tipoCursoSeleccionado) && $tipoCursoSeleccionado == $tipoCurso->id ? 'selected' : '' }}>
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
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-book"></i>
                                </span>
                                <select name="curso_id" id="curso_id" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300" onchange="updateCoursePrice()">
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ $curso->id == $matricula->curso_id ? 'selected' : '' }} data-precio="{{ $curso->precio }}">
                                            {{ $curso->nombre }} - {{ $curso->horario }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Fecha de Matrícula -->
                        <div class="relative">
                            <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Matrícula</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" name="fecha_matricula" id="fecha_matricula" value="{{ $matricula->fecha_matricula }}" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>

                        <!-- Monto Total -->
                        <div class="relative">
                            <label for="monto_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Total</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="monto_total" id="monto_total" value="{{ $matricula->monto_total }}" required readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>

                        <!-- Tipo de Pago -->
                        <div class="relative">
                            <label for="tipo_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Pago</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                <select name="tipo_pago" id="tipo_pago" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Selecciona un tipo de pago</option>
                                    <option value="Pago Único" {{ $matricula->tipo_pago == 'Pago Único' ? 'selected' : '' }}>Pago Único</option>
                                    <option value="Mensual" {{ $matricula->tipo_pago == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                </select>
                            </div>
                        </div>

                        <!-- Estado de Matrícula -->
                        <div class="relative">
                            <label for="estado_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado de Matrícula</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                @if(auth()->user()->hasRole(1))
                                    <select name="estado_matricula" id="estado_matricula" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                        <option value="Pendiente" {{ $matricula->estado_matricula == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="Aprobada" {{ $matricula->estado_matricula == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                                        <option value="Completada" {{ $matricula->estado_matricula == 'Completada' ? 'selected' : '' }}>Completada</option>
                                        <option value="Rechazada" {{ $matricula->estado_matricula == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                                    </select>
                                @else
                                    <input type="text" name="estado_matricula" id="estado_matricula" value="Pendiente" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                @endif
                            </div>
                        </div>

                        <!-- Universidad a Postular -->
                        <div class="relative" id="universidad-container" style="display: none;">
                            <label for="universidad_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Universidad a postular
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-university"></i>
                                </span>
                                <select name="universidad_id" id="universidad_id" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                    @foreach($universidades as $universidad)
                                        <option value="{{ $universidad->id }}" {{ $matricula->universidad_id == $universidad->id ? 'selected' : '' }}>
                                            {{ $universidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 active:bg-blue-700 dark:active:bg-blue-600 disabled:opacity-25 transition">
                            <i class="fas fa-save mr-2"></i> Actualizar
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
                            @if(isset($cursoSeleccionado))
                                if (curso.id == {{ $cursoSeleccionado }}) {
                                    option.selected = true;
                                }
                            @endif

                            cursoSelect.add(option);
                        }
                    });

                    // Actualizar el precio del curso
                    updateCoursePrice();
                } else {
                    // No hay cursos para este tipo de curso
                    document.getElementById('monto_total').value = '';
                    document.getElementById('universidad-container').style.display = 'none';
                }
            } else {
                // Ocultar el combo de Curso si no se ha seleccionado un tipo de curso
                cursoContainer.style.display = 'none';
                cursoSelect.innerHTML = '';
                document.getElementById('monto_total').value = '';
                document.getElementById('universidad-container').style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Por defecto, ocultar el combo de curso hasta que se seleccione un tipo de curso
            document.getElementById('curso_container').style.display = 'none';
            document.getElementById('universidad-container').style.display = 'none';

            // Si se proporcionó un tipo de curso seleccionado, llamar la función para mostrarlo
            @if(isset($tipoCursoSeleccionado))
                handleTipoCursoChange();
            @endif
        });
    </script>
</x-app-layout>