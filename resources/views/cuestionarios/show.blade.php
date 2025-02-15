<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $cuestionario->titulo }}
            </h2>
            
            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                <div class="flex gap-2">
                    <!-- Botón de Editar -->
                    <a href="{{ route('cuestionarios.edit', $cuestionario) }}" 
                       class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Editar
                    </a>
                    
                    <!-- Botón de Programar -->
                    <button onclick="mostrarModalProgramacion()"
                            class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition-colors">
                        <i class="fas fa-clock mr-2"></i>Programar
                    </button>

                    <!-- Botón de Eliminar -->
                    <button onclick="confirmarEliminacion()"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar
                    </button>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Panel de Administración para docentes -->
            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Panel de Administración</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Estadísticas -->
                            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Estadísticas</h4>
                                <p>Total de intentos: {{ $cuestionario->intentos()->count() }}</p>
                                <p>Promedio: {{ number_format($cuestionario->intentos()->avg('calificacion'), 2) }}%</p>
                            </div>

                            <!-- Estado -->
                            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Estado</h4>
                                <div class="flex items-center justify-between">
                                    <span>Activo</span>
                                    <label class="switch">
                                        <input type="checkbox" 
                                               {{ $cuestionario->activo ? 'checked' : '' }}
                                               onchange="toggleEstado(this)">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Configuración Rápida -->
                            <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Configuración Rápida</h4>
                                <div class="space-y-2">
                                    <button onclick="editarTiempoLimite()"
                                            class="w-full text-left px-3 py-1 hover:bg-purple-100 dark:hover:bg-purple-800 rounded">
                                        <i class="fas fa-clock mr-2"></i>Tiempo: {{ $cuestionario->tiempo_limite }} min
                                    </button>
                                    <button onclick="editarIntentos()"
                                            class="w-full text-left px-3 py-1 hover:bg-purple-100 dark:hover:bg-purple-800 rounded">
                                        <i class="fas fa-redo mr-2"></i>Intentos: {{ $cuestionario->intentos_permitidos }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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

    <!-- Modal de Programación -->
    <div id="modalProgramacion" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Programar Cuestionario</h3>
            <form id="formProgramacion" class="space-y-4">
                <div>
                    <label class="block mb-1">Fecha de inicio</label>
                    <input type="datetime-local" class="w-full rounded-md" name="fecha_inicio">
                </div>
                <div>
                    <label class="block mb-1">Fecha de fin</label>
                    <input type="datetime-local" class="w-full rounded-md" name="fecha_fin">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" 
                            onclick="cerrarModalProgramacion()"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        /* Estilos para el switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    @endpush

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

        // Funciones para el panel administrativo
        function toggleEstado(checkbox) {
            fetch(`/cuestionarios/${cuestionarioId}/toggle-estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    activo: checkbox.checked
                })
            }).then(response => {
                if (!response.ok) {
                    checkbox.checked = !checkbox.checked;
                    throw new Error('Error al cambiar el estado');
                }
            }).catch(error => {
                alert(error.message);
            });
        }

        function mostrarModalProgramacion() {
            document.getElementById('modalProgramacion').classList.remove('hidden');
            document.getElementById('modalProgramacion').classList.add('flex');
        }

        function cerrarModalProgramacion() {
            document.getElementById('modalProgramacion').classList.add('hidden');
            document.getElementById('modalProgramacion').classList.remove('flex');
        }

        function editarTiempoLimite() {
            const nuevoTiempo = prompt('Ingrese el nuevo tiempo límite en minutos:', 
                                     '{{ $cuestionario->tiempo_limite }}');
            if (nuevoTiempo !== null) {
                actualizarConfiguracion('tiempo_limite', nuevoTiempo);
            }
        }

        function editarIntentos() {
            const nuevosIntentos = prompt('Ingrese el nuevo número de intentos permitidos:', 
                                        '{{ $cuestionario->intentos_permitidos }}');
            if (nuevosIntentos !== null) {
                actualizarConfiguracion('intentos_permitidos', nuevosIntentos);
            }
        }

        function actualizarConfiguracion(campo, valor) {
            fetch(`/cuestionarios/${cuestionarioId}/actualizar-config`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    campo: campo,
                    valor: valor
                })
            }).then(response => {
                if (!response.ok) throw new Error('Error al actualizar la configuración');
                location.reload();
            }).catch(error => {
                alert(error.message);
            });
        }

        function confirmarEliminacion() {
            if (confirm('¿Está seguro de que desea eliminar este cuestionario? Esta acción no se puede deshacer.')) {
                fetch(`/cuestionarios/${cuestionarioId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    if (!response.ok) throw new Error('Error al eliminar el cuestionario');
                    window.location.href = '/aulas-virtuales/{{ $cuestionario->aula_virtual_id }}';
                }).catch(error => {
                    alert(error.message);
                });
            }
        }
    </script>
    @endpush
</x-app-layout> 