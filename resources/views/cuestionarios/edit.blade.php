<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Cuestionario
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('cuestionarios.show', $cuestionario) }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Información básica -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                            Información Básica
                        </h3>
                        <form id="formBasico" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Título del Cuestionario *
                                    </label>
                                    <input type="text" 
                                           name="titulo" 
                                           value="{{ old('titulo', $cuestionario->titulo) }}"
                                           required 
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    @error('titulo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Descripción
                                    </label>
                                    <textarea name="descripcion" 
                                              rows="3" 
                                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">{{ old('descripcion', $cuestionario->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" 
                                        onclick="actualizarInformacionBasica()"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Configuración -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                            Configuración
                        </h3>
                        <form id="formConfiguracion" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tiempo Límite (minutos) *
                                    </label>
                                    <input type="number" 
                                           name="tiempo_limite" 
                                           value="{{ old('tiempo_limite', $cuestionario->tiempo_limite) }}"
                                           required 
                                           min="1" 
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    @error('tiempo_limite')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Intentos Permitidos *
                                    </label>
                                    <input type="number" 
                                           name="intentos_permitidos" 
                                           value="{{ old('intentos_permitidos', $cuestionario->intentos_permitidos) }}"
                                           required 
                                           min="1" 
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    @error('intentos_permitidos')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="permite_revision" 
                                           id="permite_revision" 
                                           {{ old('permite_revision', $cuestionario->permite_revision) ? 'checked' : '' }}
                                           class="rounded border-gray-300 dark:border-gray-600">
                                    <label for="permite_revision" class="text-sm text-gray-700 dark:text-gray-300">
                                        Permitir revisión después de finalizar
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="aleatorio" 
                                           id="aleatorio" 
                                           {{ old('aleatorio', $cuestionario->aleatorio) ? 'checked' : '' }}
                                           class="rounded border-gray-300 dark:border-gray-600">
                                    <label for="aleatorio" class="text-sm text-gray-700 dark:text-gray-300">
                                        Mostrar preguntas en orden aleatorio
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="activo" 
                                           id="activo" 
                                           {{ old('activo', $cuestionario->activo) ? 'checked' : '' }}
                                           class="rounded border-gray-300 dark:border-gray-600">
                                    <label for="activo" class="text-sm text-gray-700 dark:text-gray-300">
                                        Cuestionario activo
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" 
                                        onclick="actualizarConfiguracion()"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Preguntas -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Preguntas
                            </h3>
                            <button onclick="mostrarModalPregunta()"
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Agregar Pregunta
                            </button>
                        </div>

                        <div class="space-y-4">
                            @forelse($cuestionario->preguntas as $pregunta)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-grow">
                                            <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $pregunta->pregunta }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Tipo: {{ $pregunta->tipo === 'opcion_multiple' ? 'Opción Múltiple' : 'Verdadero/Falso' }}
                                            </p>
                                            
                                            <div class="mt-2 space-y-1">
                                                @foreach($pregunta->opciones as $opcion)
                                                    <div class="flex items-center text-sm">
                                                        <span class="mr-2 {{ $opcion->es_correcta ? 'text-green-500' : 'text-gray-400' }}">
                                                            {{ $opcion->es_correcta ? '✓' : '·' }}
                                                        </span>
                                                        {{ $opcion->texto }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="editarPregunta({{ $pregunta->id }})"
                                                    class="text-blue-500 hover:text-blue-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button onclick="eliminarPregunta({{ $pregunta->id }})"
                                                    class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
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

    <!-- Modal para agregar/editar pregunta -->
    <div id="modalPregunta" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="modalTitulo">
                    Agregar Pregunta
                </h3>
                <button onclick="cerrarModalPregunta()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="formPregunta" class="space-y-4">
                <input type="hidden" name="pregunta_id" id="pregunta_id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Pregunta *
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
                    <select name="tipo" 
                            onchange="cambiarTipoPregunta(this.value)"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="opcion_multiple">Opción Múltiple</option>
                        <option value="verdadero_falso">Verdadero/Falso</option>
                    </select>
                </div>

                <div id="opcionesContainer" class="space-y-2">
                    <!-- Las opciones se agregarán aquí dinámicamente -->
                </div>

                <div class="flex justify-between">
                    <button type="button" 
                            onclick="agregarOpcion()"
                            class="text-blue-500 hover:text-blue-700">
                        + Agregar Opción
                    </button>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" 
                            onclick="cerrarModalPregunta()"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="guardarPregunta()"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Guardar Pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let opcionCount = 0;

        async function actualizarInformacionBasica() {
            const form = document.getElementById('formBasico');
            const formData = new FormData();

            // Agregar los campos básicos
            formData.append('titulo', form.titulo.value);
            formData.append('descripcion', form.descripcion.value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch(`/cuestionarios/{{ $cuestionario->id }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Error al actualizar la información básica');
                }

                mostrarMensaje('Información básica actualizada correctamente');
                setTimeout(() => {
                    location.reload();
                }, 1500);

            } catch (error) {
                console.error('Error:', error);
                mostrarError(error.message || 'Error al actualizar la información básica');
            }
        }

        async function actualizarConfiguracion() {
            const formConfig = document.getElementById('formConfiguracion');
            const formData = new FormData();

            // Agregar los campos de configuración
            formData.append('tiempo_limite', formConfig.tiempo_limite.value);
            formData.append('intentos_permitidos', formConfig.intentos_permitidos.value);
            formData.append('permite_revision', formConfig.permite_revision.checked ? '1' : '0');
            formData.append('aleatorio', formConfig.aleatorio.checked ? '1' : '0');
            formData.append('activo', formConfig.activo.checked ? '1' : '0');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                console.log('Datos a enviar:', Object.fromEntries(formData));

                const response = await fetch(`/cuestionarios/{{ $cuestionario->id }}/actualizar-config`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al actualizar la configuración');
                }

                mostrarMensaje('Configuración actualizada correctamente');
                setTimeout(() => {
                    location.reload();
                }, 1500);

            } catch (error) {
                console.error('Error:', error);
                mostrarError(error.message || 'Error al actualizar la configuración');
            }
        }

        function mostrarModalPregunta() {
            document.getElementById('modalTitulo').textContent = 'Agregar Pregunta';
            document.getElementById('pregunta_id').value = '';
            document.getElementById('formPregunta').reset();
            document.getElementById('opcionesContainer').innerHTML = '';
            opcionCount = 0;
            agregarOpcion();
            agregarOpcion();

            const modal = document.getElementById('modalPregunta');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModalPregunta() {
            const modal = document.getElementById('modalPregunta');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function cambiarTipoPregunta(tipo) {
            const container = document.getElementById('opcionesContainer');
            container.innerHTML = '';
            opcionCount = 0;

            if (tipo === 'verdadero_falso') {
                container.innerHTML = `
                    <div class="space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="respuesta_correcta" value="verdadero" required>
                            <span class="ml-2">Verdadero</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="respuesta_correcta" value="falso" required>
                            <span class="ml-2">Falso</span>
                        </label>
                    </div>
                `;
            } else {
                agregarOpcion();
                agregarOpcion();
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

        async function guardarPregunta() {
            const form = document.getElementById('formPregunta');
            const preguntaId = form.pregunta_id.value;
            const formData = new FormData(form);

            try {
                const url = preguntaId ? 
                    `/preguntas/${preguntaId}` : 
                    `/cuestionarios/{{ $cuestionario->id }}/preguntas`;

                const response = await fetch(url, {
                    method: preguntaId ? 'PUT' : 'POST',
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

                if (!response.ok) throw new Error('Error al guardar la pregunta');

                cerrarModalPregunta();
                mostrarMensaje('Pregunta guardada correctamente');
                location.reload();

            } catch (error) {
                mostrarError(error.message);
            }
        }

        async function editarPregunta(preguntaId) {
            try {
                // Mostrar el modal primero con título de edición
                document.getElementById('modalTitulo').textContent = 'Editar Pregunta';
                const modal = document.getElementById('modalPregunta');
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                const response = await fetch(`/preguntas/${preguntaId}/obtener`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar la pregunta');
                }

                const pregunta = await response.json();
                console.log('Datos de pregunta recibidos:', pregunta);

                // Llenar el formulario con los datos
                const form = document.getElementById('formPregunta');
                form.pregunta_id.value = preguntaId;
                form.pregunta.value = pregunta.pregunta;
                form.tipo.value = pregunta.tipo;

                // Limpiar y configurar las opciones según el tipo
                const container = document.getElementById('opcionesContainer');
                container.innerHTML = '';
                opcionCount = 0;

                if (pregunta.tipo === 'verdadero_falso') {
                    container.innerHTML = `
                        <div class="space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="respuesta_correcta" value="verdadero" ${pregunta.respuesta_correcta === 'verdadero' ? 'checked' : ''}>
                                <span class="ml-2">Verdadero</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="respuesta_correcta" value="falso" ${pregunta.respuesta_correcta === 'falso' ? 'checked' : ''}>
                                <span class="ml-2">Falso</span>
                            </label>
                        </div>
                    `;
                } else {
                    // Crear las opciones para preguntas de opción múltiple
                    pregunta.opciones.forEach((opcion, index) => {
                        const opcionDiv = document.createElement('div');
                        opcionDiv.className = 'flex items-center space-x-2';
                        opcionDiv.innerHTML = `
                            <input type="text" 
                                   name="opciones[${index}][texto]" 
                                   value="${opcion.texto}"
                                   required 
                                   class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <input type="radio" 
                                   name="opciones_correcta" 
                                   value="${index}"
                                   ${opcion.es_correcta ? 'checked' : ''}
                                   required>
                            ${index > 1 ? `
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
                    });
                }

            } catch (error) {
                console.error('Error completo:', error);
                mostrarError('Error al cargar la pregunta: ' + error.message);
                cerrarModalPregunta();
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

                mostrarMensaje('Pregunta eliminada correctamente');
                location.reload();

            } catch (error) {
                mostrarError(error.message);
            }
        }

        function mostrarMensaje(mensaje) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
            toast.textContent = mensaje;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        function mostrarError(mensaje) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
            toast.textContent = mensaje;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>
    @endpush
</x-app-layout> 