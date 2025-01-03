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
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('cursos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Curso</a>
                    <form action="{{ route('cursos.disableMultiple') }}" method="POST" id="disable-multiple-form"
                          onsubmit="return confirm('¿Estás seguro de deshabilitar los cursos seleccionados?')">
                        @csrf
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mt-4 hidden" id="disable-selected" disabled>Deshabilitar Seleccionados</button>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-blue-500">
                                    <tr>
                                        <th class="hidden px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Descripción</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Precio</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo de Curso</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-white uppercase tracking-wider">Imagen</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Horario</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                    @foreach ($cursos as $curso)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="hidden px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                                @if($curso->estado == 'Activo')
                                                    <input type="checkbox" name="cursos[]" value="{{ $curso->id }}" class="select-checkbox">
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $curso->nombre }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $curso->descripcion }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $curso->precio }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $curso->estado }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $curso->tipoCurso->nombre }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300 text-center">
                                                @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                                                    <img src="{{ asset('storage/' . $curso->imagen) }}" alt="{{ $curso->nombre }}" class="w-12 h-12 inline-block">
                                                @else
                                                    <i class="fas fa-book w-12 h-12 text-gray-500 dark:text-gray-300"></i>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $curso->horario }}</td>
                                            <td class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2">
                                                <a href="{{ route('cursos.edit', $curso) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Editar</a>
                                                <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
                                                </form>
                                                @if($curso->estado == 'Activo')
                                                    <form action="{{ route('cursos.disable', $curso) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Deshabilitar</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('select-all').addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('.select-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
            toggleDisableButton();
        });

        document.querySelectorAll('.select-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleDisableButton);
        });

        function toggleDisableButton() {
            const checkboxes = document.querySelectorAll('.select-checkbox:checked');
            document.getElementById('disable-selected').disabled = checkboxes.length === 0;
        }
    </script>
</x-app-layout>
