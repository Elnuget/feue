<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Revisión del Cuestionario: {{ $cuestionario->titulo }}
            </h2>
            <a href="{{ route('aulas_virtuales.show', $cuestionario->aulaVirtual) }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                Volver al Aula Virtual
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Resumen del intento -->
                    <div class="mb-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                            Resumen del Intento
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Calificación</p>
                                <p class="text-2xl font-bold {{ $intento->calificacion >= 70 ? 'text-green-500' : 'text-red-500' }}">
                                    {{ number_format($intento->calificacion, 2) }}%
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Finalización</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    @if($intento->fin)
                                        {{ Carbon\Carbon::parse($intento->fin)->format('d/m/Y H:i') }}
                                    @else
                                        No finalizado
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tiempo Utilizado</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    @php
                                        $inicio = Carbon\Carbon::parse($intento->inicio);
                                        $fin = $intento->fin ? Carbon\Carbon::parse($intento->fin) : now();
                                        $tiempoUtilizado = $fin->diffInMinutes($inicio);
                                    @endphp
                                    {{ $tiempoUtilizado }} minutos
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Revisión de preguntas -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                            Revisión de Respuestas
                        </h3>

                        @foreach($cuestionario->preguntas as $index => $pregunta)
                            @php
                                $respuestaUsuario = $intento->respuestas()
                                    ->where('pregunta_id', $pregunta->id)
                                    ->first();
                                $esCorrecta = $respuestaUsuario && 
                                    $pregunta->opciones()
                                        ->where('id', $respuestaUsuario->opcion_id)
                                        ->where('es_correcta', true)
                                        ->exists();
                            @endphp

                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm p-6 {{ $esCorrecta ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                            Pregunta {{ $index + 1 }}
                                        </h4>
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">
                                            {{ $pregunta->pregunta }}
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        @if($esCorrecta)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Correcta
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Incorrecta
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @foreach($pregunta->opciones as $opcion)
                                        <div class="flex items-center p-2 rounded-lg
                                            @if($opcion->es_correcta) bg-green-50 dark:bg-green-900 @endif
                                            @if($respuestaUsuario && $respuestaUsuario->opcion_id === $opcion->id && !$opcion->es_correcta) bg-red-50 dark:bg-red-900 @endif">
                                            
                                            <div class="mr-3">
                                                @if($respuestaUsuario && $respuestaUsuario->opcion_id === $opcion->id)
                                                    @if($opcion->es_correcta)
                                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                @endif
                                            </div>

                                            <span class="flex-grow {{ $opcion->es_correcta ? 'font-medium' : '' }}">
                                                {{ $opcion->texto }}
                                            </span>

                                            @if($opcion->es_correcta)
                                                <span class="ml-2 text-sm text-green-600 dark:text-green-400">
                                                    Respuesta correcta
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if(!$esCorrecta && $pregunta->retroalimentacion)
                                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                        <p class="text-sm text-blue-800 dark:text-blue-200">
                                            <strong>Retroalimentación:</strong> {{ $pregunta->retroalimentacion }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($cuestionario->retroalimentacion)
                        <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">
                                Retroalimentación General
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $cuestionario->retroalimentacion }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 