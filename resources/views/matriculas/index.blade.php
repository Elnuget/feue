<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Matriculas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('matriculas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Matricula</a>
                    <div class="mt-4">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">ID</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Usuario</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Curso</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Fecha</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Monto Total</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Estado</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matriculas as $matricula)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $matricula->id }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $matricula->usuario->name }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $matricula->curso->nombre }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $matricula->fecha_matricula }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $matricula->monto_total }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $matricula->estado_matricula }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                                            <a href="{{ route('matriculas.show', $matricula) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Ver</a>
                                            <a href="{{ route('matriculas.edit', $matricula) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Editar</a>
                                            <form action="{{ route('matriculas.destroy', $matricula) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
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
    </div>
</x-app-layout>