<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $cuestionario->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Información del cuestionario -->
                    <div class="mb-6">
                        @if($cuestionario->descripcion)
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $cuestionario->descripcion }}</p>
                        @endif
                        
                        <div class="flex flex-wrap gap-4">
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                                <span class="font-semibold">Tiempo restante:</span>
                                <span id="timer" data-tiempo="{{ $tiempoRestante }}"></span>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                                <span class="font-semibold">Intento:</span>
                                <span>{{ $intento->numero_intento }} de {{ $cuestionario->intentos_permitidos }}</span>
                            </div>
                        </div>
                    </div>

                    <form id="cuestionarioForm" action="{{ route('cuestionarios.finalizar', $intento) }}" method="POST">
                        @csrf

                        <!-- Preguntas -->
                        <div class="space-y-6">
                            @foreach($cuestionario->preguntas as $index => $pregunta)
                                <div class="pregunta p-4 border dark:border-gray-700 rounded-lg {{ $index === 0 ? '' : 'hidden' }}" 
                                     data-pregunta="{{ $index + 1 }}">
                                    <h3 class="text-lg font-semibold mb-4">
                                        Pregunta {{ $index + 1 }} de {{ $cuestionario->preguntas->count() }}
                                    </h3>
                                    
                                    <p class="mb-4 text-gray-700 dark:text-gray-300">{{ $pregunta->pregunta }}</p>
                                    
                                    <div class="space-y-3">
                                        @if($pregunta->tipo === 'verdadero_falso')
                                            @foreach($pregunta->opciones as $opcion)
                                                <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                                    <input type="radio" 
                                                           name="respuestas[{{ $pregunta->id }}]" 
                                                           value="{{ $opcion->id }}"
                                                           class="mr-3"
                                                           {{ isset($respuestasUsuario[$pregunta->id]) && $respuestasUsuario[$pregunta->id] == $opcion->id ? 'checked' : '' }}
                                                           onchange="guardarRespuesta({{ $intento->id }}, {{ $pregunta->id }}, {{ $opcion->id }})">
                                                    <span>{{ $opcion->texto }}</span>
                                                </label>
                                            @endforeach
                                        @else
                                            @foreach($pregunta->opciones as $opcion)
                                                <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                                    <input type="radio" 
                                                           name="respuestas[{{ $pregunta->id }}]" 
                                                           value="{{ $opcion->id }}"
                                                           class="mr-3"
                                                           {{ isset($respuestasUsuario[$pregunta->id]) && $respuestasUsuario[$pregunta->id] == $opcion->id ? 'checked' : '' }}
                                                           onchange="guardarRespuesta({{ $intento->id }}, {{ $pregunta->id }}, {{ $opcion->id }})">
                                                    <span>{{ $opcion->texto }}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Navegación -->
                        <div class="mt-6 flex justify-between">
                            <button type="button" 
                                    onclick="navegarPregunta('anterior')"
                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Anterior
                            </button>
                            <button type="button" 
                                    onclick="navegarPregunta('siguiente')"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Siguiente
                            </button>
                        </div>

                        <div class="mt-6 flex justify-center">
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                Finalizar Cuestionario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let preguntaActual = 1;
        const totalPreguntas = {{ $cuestionario->preguntas->count() }};
        let tiempoRestante = {{ $tiempoRestante }};

        // Actualizar el timer cada segundo
        function actualizarTimer() {
            const minutos = Math.floor(tiempoRestante / 60);
            const segundos = tiempoRestante % 60;
            document.getElementById('timer').textContent = 
                `${minutos}:${segundos.toString().padStart(2, '0')}`;

            if (tiempoRestante <= 0) {
                document.getElementById('cuestionarioForm').submit();
            }

            tiempoRestante--;
        }

        setInterval(actualizarTimer, 1000);
        actualizarTimer(); // Llamar inmediatamente para mostrar el tiempo inicial

        // Navegación entre preguntas
        function navegarPregunta(direccion) {
            const nuevaPregunta = direccion === 'siguiente' ? 
                Math.min(preguntaActual + 1, totalPreguntas) : 
                Math.max(preguntaActual - 1, 1);

            if (nuevaPregunta !== preguntaActual) {
                document.querySelector(`.pregunta[data-pregunta="${preguntaActual}"]`).classList.add('hidden');
                document.querySelector(`.pregunta[data-pregunta="${nuevaPregunta}"]`).classList.remove('hidden');
                preguntaActual = nuevaPregunta;
            }
        }

        // Guardar respuesta
        async function guardarRespuesta(intentoId, preguntaId, opcionId) {
            try {
                const response = await fetch(`/intentos/${intentoId}/respuestas`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pregunta_id: preguntaId,
                        opcion_id: opcionId
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al guardar la respuesta');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la respuesta');
            }
        }
    </script>
    @endpush
</x-app-layout> 