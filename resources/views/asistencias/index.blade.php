<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asistencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex space-x-4 mb-4">
                        <a href="{{ route('asistencias.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Registrar Asistencia Manual
                        </a>
                        <a href="{{ route('asistencias.scan') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            📷 Escanear QR
                        </a>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Usuario</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Fecha y Hora</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($asistencias as $asistencia)
                                    <tr>
                                        <td class="px-4 py-2">{{ $asistencia->user->name }}</td>
                                        <td class="px-4 py-2">{{ $asistencia->fecha_hora }}</td>
                                        <td class="px-4 py-2 flex space-x-2">
                                            <a href="{{ route('asistencias.edit', $asistencia) }}" 
                                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                                Editar
                                            </a>
                                            <form action="{{ route('asistencias.destroy', $asistencia) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                                    Eliminar
                                                </button>
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