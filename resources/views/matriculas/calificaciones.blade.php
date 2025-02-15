<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Calificaciones de {{ $matricula->usuario->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjeta de Estadísticas -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Resumen de Rendimiento</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <p class="text-sm text-blue-600 dark:text-blue-300">Promedio General</p>
                            <p class="text-2xl font-bold text-blue-800 dark:text-blue-100">
                                {{ number_format($estadisticas['promedio'], 2) }}%
                            </p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <p class="text-sm text-green-600 dark:text-green-300">Mejor Nota</p>
                            <p class="text-2xl font-bold text-green-800 dark:text-green-100">
                                {{ number_format($estadisticas['mejor_nota'], 2) }}%
                            </p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                            <p class="text-sm text-red-600 dark:text-red-300">Nota más Baja</p>
                            <p class="text-2xl font-bold text-red-800 dark:text-red-100">
                                {{ number_format($estadisticas['peor_nota'], 2) }}%
                            </p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                            <p class="text-sm text-purple-600 dark:text-purple-300">Cuestionarios Completados</p>
                            <p class="text-2xl font-bold text-purple-800 dark:text-purple-100">
                                {{ $estadisticas['cuestionarios_completados'] }} / {{ $estadisticas['total_cuestionarios'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Calificaciones -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Historial de Calificaciones</h3>
                    
                    @if($intentos->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Cuestionario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Calificación
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($intentos as $intento)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $intento->cuestionario->titulo }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium 
                                                    {{ $intento->calificacion >= 70 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ number_format($intento->calificacion, 2) }}%
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ Carbon\Carbon::parse($intento->created_at)->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($intento->cuestionario->permite_revision)
                                                    <a href="{{ route('cuestionarios.revision', $intento->cuestionario) }}" 
                                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                        Ver Revisión
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">Revisión no disponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 dark:text-gray-400">No hay calificaciones disponibles</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('matriculas.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:border-gray-900 dark:focus:border-gray-50 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Volver a Matrículas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 