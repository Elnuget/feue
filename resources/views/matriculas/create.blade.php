<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Matricula') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('matriculas.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                        <div class="relative">
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-user"></i>
                                </span>
                                @if(auth()->user()->hasRole(1))
                                    <select name="usuario_id" id="usuario_id" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="usuario_name" id="usuario_name" value="{{ auth()->user()->name }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                    <input type="hidden" name="usuario_id" id="usuario_id" value="{{ auth()->id() }}">
                                @endif
                            </div>
                        </div>
                        <div class="relative">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-book"></i>
                                </span>
                                <select name="curso_id" id="curso_id" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300" onchange="updateCoursePrice()">
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" data-precio="{{ $curso->precio }}" {{ isset($cursoSeleccionado) && $cursoSeleccionado == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="relative">
                            <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Matricula</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" name="fecha_matricula" id="fecha_matricula" value="{{ \Carbon\Carbon::now('America/Guayaquil')->format('Y-m-d') }}" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="monto_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Total</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="monto_total" id="monto_total" required readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="estado_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado de Matricula</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                @if(auth()->user()->hasRole(1))
                                    <select name="estado_matricula" id="estado_matricula" required class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Aprobada">Aprobada</option>
                                        <option value="Completada">Completada</option>
                                        <option value="Rechazada">Rechazada</option>
                                    </select>
                                @else
                                    <input type="text" name="estado_matricula" id="estado_matricula" value="Pendiente" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 active:bg-blue-700 dark:active:bg-blue-600 disabled:opacity-25 transition">
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
            document.getElementById('valor_pendiente').value = precio;
        }

        // Initialize the price fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCoursePrice();
        });
    </script>
</x-app-layout>