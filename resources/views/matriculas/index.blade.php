<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Matriculas') }}
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
                    <a href="{{ route('matriculas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Matricula</a>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Usuario</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Curso</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Monto Total</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Valor Pendiente</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($matriculas as $matricula)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $matricula->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $matricula->usuario->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $matricula->curso->nombre }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $matricula->fecha_matricula }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $matricula->monto_total }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $matricula->valor_pendiente }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $matricula->estado_matricula }}</td>
                                        <td class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2">
                                            <a href="{{ route('matriculas.show', $matricula) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Ver</a>
                                            @if(auth()->user()->hasRole(1))
                                                <a href="{{ route('matriculas.edit', $matricula) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Editar</a>
                                                <form action="{{ route('matriculas.destroy', $matricula) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
                                                </form>
                                            @endif
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