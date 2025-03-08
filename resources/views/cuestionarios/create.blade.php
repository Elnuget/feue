<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Crear Nuevo Cuestionario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Indicador de pasos -->
                    <div class="mb-8">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <div id="paso1" class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold">1</div>
                                <div class="h-1 w-16 bg-gray-300" id="linea1"></div>
                                <div id="paso2" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-bold">2</div>
                                <div class="h-1 w-16 bg-gray-300" id="linea2"></div>
                                <div id="paso3" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-bold">3</div>
                            </div>
                        </div>
                        <div class="flex justify-center mt-2 text-sm">
                            <span class="mx-4">Información Básica</span>
                            <span class="mx-4">Preguntas</span>
                            <span class="mx-4">Configuración</span>
                        </div>
                    </div>

                    <!-- Paso 1: Información básica -->
                    <div id="paso1Content" class="space-y-6">
                        <form id="formBasico" class="space-y-4">
                            @csrf
                            <input type="hidden" name="aula_virtual_id" value="{{ $aulaVirtual->id }}">

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Título del Cuestionario *
                                    </label>
                                    <input type="text" 
                                           name="titulo" 
                                           required 
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                           placeholder="Ej: Evaluación Primer Parcial">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Descripción
                                    </label>
                                    <textarea name="descripcion" 
                                              rows="3" 
                                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                              placeholder="Instrucciones o descripción del cuestionario"></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" 
                                        onclick="siguientePaso(1)"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Siguiente
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Paso 2: Preguntas -->
                    <div id="paso2Content" class="hidden space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                            <h3 class="font-semibold mb-2">Preguntas Agregadas: <span id="contadorPreguntas">0</span></h3>
                        </div>

                        <form id="formPregunta" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Pregunta *
                                    </label>
                                    <textarea name="pregunta" 
                                              required 
                                              rows="2"
                                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                              placeholder="Escribe tu pregunta aquí"></textarea>
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
                            </div>

                            <div class="flex justify-between">
                                <button type="button" 
                                        onclick="anteriorPaso(2)"
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                    Anterior
                                </button>
                                <div>
                                    <button type="button" 
                                            onclick="agregarPregunta()"
                                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 mr-2">
                                        Agregar Pregunta
                                    </button>
                                    <button type="button" 
                                            onclick="siguientePaso(2)"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                        Siguiente
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Paso 3: Configuración -->
                    <div id="paso3Content" class="hidden space-y-6">
                        <form id="formConfiguracion" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tiempo Límite (minutos) *
                                    </label>
                                    <input type="number" 
                                           name="tiempo_limite" 
                                           required 
                                           min="1" 
                                           value="30"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Intentos Permitidos *
                                    </label>
                                    <input type="number" 
                                           name="intentos_permitidos" 
                                           required 
                                           min="1" 
                                           value="1"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="permite_revision" 
                                           id="permite_revision" 
                                           class="rounded border-gray-300 dark:border-gray-600">
                                    <label for="permite_revision" class="text-sm text-gray-700 dark:text-gray-300">
                                        Permitir revisión después de finalizar
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="aleatorio" 
                                           id="aleatorio" 
                                           class="rounded border-gray-300 dark:border-gray-600">
                                    <label for="aleatorio" class="text-sm text-gray-700 dark:text-gray-300">
                                        Mostrar preguntas en orden aleatorio
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="activo" 
                                           id="activo" 
                                           checked
                                           class="rounded border-gray-300 dark:border-gray-600">
                                    <label for="activo" class="text-sm text-gray-700 dark:text-gray-300">
                                        Activar cuestionario inmediatamente
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button type="button" 
                                        onclick="anteriorPaso(3)"
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                    Anterior
                                </button>
                                <button type="button" 
                                        onclick="guardarCuestionario()"
                                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Guardar Cuestionario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let preguntas = [];
        let opcionCount = 0;

        // Inicializar con dos opciones al cargar
        document.addEventListener('DOMContentLoaded', function() {
            agregarOpcion();
            agregarOpcion();
        });

        function siguientePaso(pasoActual) {
            // Validar antes de avanzar
            if (pasoActual === 1) {
                if (!validarPaso1()) return;
            } else if (pasoActual === 2) {
                if (preguntas.length === 0) {
                    alert('Debe agregar al menos una pregunta');
                    return;
                }
            }

            document.getElementById(`paso${pasoActual}Content`).classList.add('hidden');
            document.getElementById(`paso${pasoActual + 1}Content`).classList.remove('hidden');
            
            // Actualizar indicadores
            document.getElementById(`paso${pasoActual + 1}`).classList.remove('bg-gray-300', 'text-gray-600');
            document.getElementById(`paso${pasoActual + 1}`).classList.add('bg-blue-500', 'text-white');
            document.getElementById(`linea${pasoActual}`).classList.remove('bg-gray-300');
            document.getElementById(`linea${pasoActual}`).classList.add('bg-blue-500');
        }

        function anteriorPaso(pasoActual) {
            document.getElementById(`paso${pasoActual}Content`).classList.add('hidden');
            document.getElementById(`paso${pasoActual - 1}Content`).classList.remove('hidden');
        }

        function validarPaso1() {
            const titulo = document.querySelector('input[name="titulo"]').value.trim();
            if (!titulo) {
                alert('El título es obligatorio');
                return false;
            }
            return true;
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

        function agregarPregunta() {
            const form = document.getElementById('formPregunta');
            const preguntaData = {
                pregunta: form.pregunta.value.trim(),
                tipo: form.tipo.value,
            };

            if (!preguntaData.pregunta) {
                mostrarError('La pregunta es obligatoria');
                return;
            }

            if (preguntaData.tipo === 'verdadero_falso') {
                const respuestaCorrecta = form.querySelector('input[name="respuesta_correcta"]:checked');
                if (!respuestaCorrecta) {
                    mostrarError('Debe seleccionar una respuesta correcta');
                    return;
                }
                preguntaData.respuesta_correcta = respuestaCorrecta.value;
            } else {
                const opciones = [];
                let tieneRespuestaCorrecta = false;
                const opcionesInputs = form.querySelectorAll('[name^="opciones["]');
                const respuestaCorrecta = form.querySelector('input[name="opciones_correcta"]:checked');

                if (!respuestaCorrecta) {
                    mostrarError('Debe seleccionar una opción correcta');
                    return;
                }

                opcionesInputs.forEach(input => {
                    if (input.type === 'text') {
                        const match = input.name.match(/\[(\d+)\]/);
                        if (match) {
                            const index = match[1];
                            const texto = input.value.trim();
                            if (!texto) {
                                mostrarError('Todas las opciones deben tener texto');
                                return;
                            }
                            opciones[index] = { texto: texto };
                        }
                    }
                });

                if (opciones.length < 2) {
                    mostrarError('Debe agregar al menos dos opciones');
                    return;
                }

                // Filtrar cualquier elemento null o undefined del array
                preguntaData.opciones = opciones.filter(opcion => opcion !== null && opcion !== undefined);
                preguntaData.opciones_correcta = respuestaCorrecta.value;
            }

            console.log('Pregunta a agregar:', preguntaData); // Para depuración

            preguntas.push(preguntaData);
            document.getElementById('contadorPreguntas').textContent = preguntas.length;
            
            // Limpiar formulario
            form.reset();
            document.getElementById('opcionesContainer').innerHTML = '';
            agregarOpcion();
            agregarOpcion();

            // Mostrar mensaje de éxito
            mostrarMensaje('Pregunta agregada exitosamente');
        }

        async function guardarCuestionario() {
            if (preguntas.length === 0) {
                mostrarError('Debe agregar al menos una pregunta');
                return;
            }

            const formBasico = document.getElementById('formBasico');
            const formConfig = document.getElementById('formConfiguracion');

            try {
                const formData = new FormData();
                formData.append('titulo', formBasico.titulo.value);
                formData.append('descripcion', formBasico.descripcion.value);
                formData.append('tiempo_limite', formConfig.tiempo_limite.value);
                formData.append('intentos_permitidos', formConfig.intentos_permitidos.value);
                formData.append('permite_revision', formConfig.permite_revision.checked ? '1' : '0');
                formData.append('activo', formConfig.activo.checked ? '1' : '0');
                formData.append('aula_virtual_id', '{{ $aulaVirtual->id }}');

                // Convertir el array de preguntas a JSON y verificar que sea válido
                const preguntasJSON = JSON.stringify(preguntas);
                console.log('JSON de preguntas:', preguntasJSON); // Para depuración

                formData.append('preguntas', preguntasJSON);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const response = await fetch('{{ route("cuestionarios.store", $aulaVirtual) }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error al guardar el cuestionario');
                }

                mostrarMensaje('Cuestionario guardado exitosamente');
                setTimeout(() => {
                    window.location.href = '/aulas_virtuales/{{ $aulaVirtual->id }}';
                }, 1500);

            } catch (error) {
                mostrarError(error.message);
                console.error('Error completo:', error); // Para depuración
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