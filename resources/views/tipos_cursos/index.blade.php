<x-app-layout>
    @section('page_title', 'Tipos Cursos')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tipos de Cursos') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <a href="{{ route('tipos_cursos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Agregar Nuevo Tipo de Curso') }}
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-blue-500">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Descripción</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @foreach ($tiposCursos as $tipoCurso)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $tipoCurso->nombre }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $tipoCurso->descripcion }}</td>
                                    <td class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2">
                                        <a href="{{ route('tipos_cursos.edit', $tipoCurso) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">Editar</a>
                                        <form action="{{ route('tipos_cursos.destroy', $tipoCurso) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded" onclick="return confirm('¿Estás seguro de eliminar este tipo de curso?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>