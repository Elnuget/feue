<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Cuestionario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('cuestionarios.update', $cuestionario) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Información básica -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Título del Cuestionario
                                </label>
                                <input type="text" 
                                       name="titulo" 
                                       value="{{ old('titulo', $cuestionario->titulo) }}"
                                       required 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                @error('titulo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Descripción
                                </label>
                                <textarea name="descripcion" 
                                          rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">{{ old('descripcion', $cuestionario->descripcion) }}</textarea>
                                @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tiempo Límite (minutos)
                                </label>
                                <input type="number" 
                                       name="tiempo_limite" 
                                       value="{{ old('tiempo_limite', $cuestionario->tiempo_limite) }}"
                                       required 
                                       min="1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                @error('tiempo_limite')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Intentos Permitidos
                                </label>
                                <input type="number" 
                                       name="intentos_permitidos" 
                                       value="{{ old('intentos_permitidos', $cuestionario->intentos_permitidos) }}"
                                       required 
                                       min="1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                @error('intentos_permitidos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Opciones adicionales -->
                        <div class="space-y-4 border-t dark:border-gray-700 pt-4">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" 
                                       name="permite_revision" 
                                       id="permite_revision" 
                                       value="1"
                                       {{ old('permite_revision', $cuestionario->permite_revision) ? 'checked' : '' }}
                                       class="rounded border-gray-300 dark:border-gray-600 text-blue-600">
                                <label for="permite_revision" class="text-sm text-gray-700 dark:text-gray-300">
                                    Permitir revisión después de finalizar
                                </label>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Retroalimentación General
                                </label>
                                <textarea name="retroalimentacion" 
                                          rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                          placeholder="Mensaje que verán los estudiantes al finalizar el cuestionario">{{ old('retroalimentacion', $cuestionario->retroalimentacion) }}</textarea>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ url()->previous() }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                    <!-- Sección de preguntas existentes -->
                    <div class="mt-8 border-t dark:border-gray-700 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Preguntas del Cuestionario
                            </h3>
                            <button onclick="mostrarModalPregunta()"
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                                Agregar Pregunta
                            </button>
                        </div>

                        <div class="space-y-4">
                            @forelse($cuestionario->preguntas as $pregunta)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $pregunta->pregunta }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Tipo: {{ $pregunta->tipo === 'opcion_multiple' ? 'Opción Múltiple' : 'Verdadero/Falso' }}
                                            </p>
                                        </div>
                                        <button onclick="eliminarPregunta({{ $pregunta->id }})"
                                                class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mt-2 space-y-1">
                                        @foreach($pregunta->opciones as $opcion)
                                            <div class="flex items-center text-sm">
                                                <span class="mr-2">{{ $opcion->es_correcta ? '✓' : '·' }}</span>
                                                {{ $opcion->texto }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay preguntas agregadas aún
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar pregunta -->
    <div id="modalPregunta" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Agregar Pregunta
                </h3>
                <button onclick="cerrarModalPregunta()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="formPregunta" onsubmit="guardarPregunta(event)" class="space-y-4">
                <input type="hidden" name="cuestionario_id" value="{{ $cuestionario->id }}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Pregunta
                    </label>
                    <textarea name="pregunta" 
                              required
                              rows="2"
                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Pregunta
                    </label>
                    <div class="space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="tipo" 
                                   value="opcion_multiple" 
                                   checked 
                                   onchange="toggleTipoPregunta(this)">
                            <span class="ml-2">Opción Múltiple</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="tipo" 
                                   value="verdadero_falso" 
                                   onchange="toggleTipoPregunta(this)">
                            <span class="ml-2">Verdadero/Falso</span>
                        </label>
                    </div>
                </div>

                <div id="opcionesMultiple">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Opciones
                    </label>
                    <div id="opcionesContainer" class="space-y-2">
                        <!-- Las opciones se agregarán aquí dinámicamente -->
                    </div>
                    <button type="button" 
                            onclick="agregarOpcion()"
                            class="mt-2 text-blue-500 hover:text-blue-700">
                        + Agregar Opción
                    </button>
                </div>

                <div id="opcionesVF" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Respuesta Correcta
                    </label>
                    <div class="space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="respuesta_correcta" value="verdadero">
                            <span class="ml-2">Verdadero</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="respuesta_correcta" value="falso">
                            <span class="ml-2">Falso</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" 
                            onclick="cerrarModalPregunta()"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Guardar Pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let opcionCount = 0;

        function mostrarModalPregunta() {
            const modal = document.getElementById('modalPregunta');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Reiniciar el formulario
            document.getElementById('formPregunta').reset();
            document.getElementById('opcionesContainer').innerHTML = '';
            opcionCount = 0;
            agregarOpcion();
            agregarOpcion();
        }

        function cerrarModalPregunta() {
            const modal = document.getElementById('modalPregunta');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function toggleTipoPregunta(radio) {
            const opcionesMultiple = document.getElementById('opcionesMultiple');
            const opcionesVF = document.getElementById('opcionesVF');
            
            if (radio.value === 'verdadero_falso') {
                opcionesMultiple.classList.add('hidden');
                opcionesVF.classList.remove('hidden');
            } else {
                opcionesMultiple.classList.remove('hidden');
                opcionesVF.classList.add('hidden');
            }
        }

        function agregarOpcion() {
            const container = document.getElementById('opcionesContainer');
            const opcionDiv = document.createElement('div');
            opcionDiv.className = 'flex items-center space-x-2';
            opcionDiv.innerHTML = `
                <input type="text" 
                       name="opciones[${opcionCount}][texto]" 
                       required 
                       placeholder="Opción ${opcionCount + 1}" 
                       class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <input type="radio" 
                       name="opciones_correcta" 
                       value="${opcionCount}" 
                       required>
                ${opcionCount > 1 ? `
                    <button type="button" 
                            onclick="eliminarOpcion(this)" 
                            class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                ` : ''}
            `;
            container.appendChild(opcionDiv);
            opcionCount++;
        }

        function eliminarOpcion(btn) {
            const opcionDiv = btn.parentElement;
            if (document.getElementById('opcionesContainer').children.length > 2) {
                opcionDiv.remove();
            }
        }

        async function guardarPregunta(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch(`/cuestionarios/${formData.get('cuestionario_id')}/preguntas`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pregunta: formData.get('pregunta'),
                        tipo: formData.get('tipo'),
                        respuesta_correcta: formData.get('tipo') === 'verdadero_falso' ? 
                            formData.get('respuesta_correcta') : undefined,
                        opciones: formData.get('tipo') === 'opcion_multiple' ? 
                            Array.from(document.querySelectorAll('[name^="opciones["]'))
                                .reduce((acc, input) => {
                                    if (input.type === 'text') {
                                        const match = input.name.match(/\[(\d+)\]/);
                                        if (match) {
                                            const index = match[1];
                                            acc[index] = { texto: input.value };
                                        }
                                    }
                                    return acc;
                                }, []) : undefined,
                        opciones_correcta: formData.get('opciones_correcta')
                    })
                });

                if (!response.ok) {
                    const data = await response.json();
                    throw new Error(data.error || 'Error al guardar la pregunta');
                }

                // Mostrar mensaje de éxito
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
                toast.textContent = 'Pregunta guardada exitosamente';
                document.body.appendChild(toast);

                // Cerrar modal y recargar
                cerrarModalPregunta();
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        toast.remove();
                        location.reload();
                    }, 500);
                }, 2000);

            } catch (error) {
                alert(error.message);
            }
        }

        async function eliminarPregunta(preguntaId) {
            if (!confirm('¿Está seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')) return;

            try {
                const response = await fetch(`/preguntas/${preguntaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Error al eliminar la pregunta');

                // Mostrar mensaje de éxito
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
                toast.textContent = 'Pregunta eliminada exitosamente';
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        toast.remove();
                        location.reload();
                    }, 500);
                }, 2000);

            } catch (error) {
                alert(error.message);
            }
        }
    </script>
    @endpush
</x-app-layout> 