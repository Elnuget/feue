<x-app-layout>
    @section('page_title', 'Cursos')
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cursos') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <a href="{{ route('cursos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Crear Curso
                        </a>
                        <button onclick="limpiarFiltros()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-sync-alt mr-2"></i>Limpiar Filtros
                        </button>
                    </div>

                    <!-- Filtros -->
                    <div class="mt-4 p-4 bg-white dark:bg-gray-700 rounded-lg shadow">
                        <form id="filtrosForm" action="{{ route('cursos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Búsqueda por nombre -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar por nombre</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                           class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:text-gray-300"
                                           placeholder="Buscar curso...">
                                </div>
                            </div>

                            <!-- Filtro por estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                <select name="estado" id="estado" onchange="this.form.submit()"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-600 dark:text-gray-300">
                                    <option value="">Todos los estados</option>
                                    <option value="Activo" {{ request('estado') === 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ request('estado') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <!-- Filtro por tipo de curso -->
                            <div>
                                <label for="tipo_curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Curso</label>
                                <select name="tipo_curso_id" id="tipo_curso_id" onchange="this.form.submit()"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-600 dark:text-gray-300">
                                    <option value="">Todos los tipos</option>
                                    @foreach($tiposCursos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ request('tipo_curso_id') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4">
                        <table class="w-full divide-y divide-gray-200 border text-sm">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-graduation-cap mr-1"></i> Nombre/Tipo
                                        </div>
                                    </th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i> Horario/Horas
                                        </div>
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-white uppercase tracking-wider w-20">
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-dollar-sign mr-1"></i> Precio
                                        </div>
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-white uppercase tracking-wider w-24">Estado</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-white uppercase tracking-wider w-28">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($cursos as $curso)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-2 py-2">
                                            <div class="flex items-center">
                                                @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                                                    <img src="{{ asset('storage/' . $curso->imagen) }}" alt="{{ $curso->nombre }}" class="w-8 h-8 rounded-full mr-2">
                                                @else
                                                    <i class="fas fa-book w-8 h-8 text-gray-500 dark:text-gray-300 mr-2"></i>
                                                @endif
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-gray-200">{{ $curso->nombre }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $curso->tipoCurso->nombre }}</div>
                                                    @if($curso->descripcion)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($curso->descripcion, 30) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-2 py-2">
                                            <div class="text-sm text-gray-900 dark:text-gray-200">{{ $curso->horario }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $curso->horas }} horas</div>
                                        </td>
                                        <td class="px-2 py-2 text-center text-sm text-gray-900 dark:text-gray-200">
                                            ${{ number_format($curso->precio, 2) }}
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $curso->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $curso->estado }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-2">
                                            <div class="flex items-center justify-center space-x-1">
                                                <a href="{{ route('cursos.edit', $curso) }}" 
                                                   class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold p-1.5 rounded"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold p-1.5 rounded"
                                                            title="Eliminar"
                                                            onclick="return confirm('¿Estás seguro de eliminar este curso?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @if($curso->estado == 'Activo')
                                                    <form action="{{ route('cursos.disable', $curso) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-yellow-500 hover:bg-yellow-700 text-white text-xs font-bold p-1.5 rounded"
                                                                title="Deshabilitar">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('cursos.enable', $curso) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold p-1.5 rounded"
                                                                title="Habilitar">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
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

<script>
    // Función para limpiar todos los filtros
    function limpiarFiltros() {
        document.getElementById('search').value = '';
        document.getElementById('estado').value = '';
        document.getElementById('tipo_curso_id').value = '';
        document.getElementById('filtrosForm').submit();
    }

    // Agregar un pequeño delay a la búsqueda por nombre para evitar muchas peticiones
    let timeoutId;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            document.getElementById('filtrosForm').submit();
        }, 500);
    });
</script>
