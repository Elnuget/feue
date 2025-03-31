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

                        <!-- Botones para importar y descargar formato -->
                        <div class="flex flex-wrap gap-3 mb-4">
                            <button type="button" 
                                    onclick="mostrarModalImportar()"
                                    class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Importar Preguntas
                            </button>
                            <button type="button" 
                                    onclick="descargarFormato()"
                                    class="px-4 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Descargar Formato
                            </button>
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

                        <!-- Contenedor para el listado de preguntas -->
                        <div id="listadoPreguntas" class="mt-8 space-y-4">
                            <!-- Las preguntas agregadas se mostrarán aquí -->
                        </div>
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
                // Manejo de preguntas de opción múltiple
                const opciones = [];
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

                preguntaData.opciones = opciones.filter(opcion => opcion !== null && opcion !== undefined);
                preguntaData.opciones_correcta = respuestaCorrecta.value;
            }

            console.log('Pregunta a agregar:', preguntaData);
            preguntas.push(preguntaData);
            document.getElementById('contadorPreguntas').textContent = preguntas.length;
            
            // Limpiar completamente el formulario
            form.reset();
            document.getElementById('opcionesContainer').innerHTML = '';
            opcionCount = 0; // Reiniciar el contador de opciones
            agregarOpcion();
            agregarOpcion();

            // Actualizar la lista de preguntas mostradas
            actualizarListaPreguntas();

            mostrarMensaje('Pregunta agregada exitosamente');
        }

        function actualizarListaPreguntas() {
            const container = document.getElementById('listadoPreguntas');
            container.innerHTML = '';

            preguntas.forEach((pregunta, index) => {
                const preguntaDiv = document.createElement('div');
                preguntaDiv.className = 'bg-white dark:bg-gray-700 p-4 rounded-lg shadow mb-4';
                
                let opcionesHtml = '';
                if (pregunta.tipo === 'verdadero_falso') {
                    opcionesHtml = `
                        <div class="ml-4 mt-2">
                            <div class="flex items-center">
                                <span class="${pregunta.respuesta_correcta === 'verdadero' ? 'text-green-500 font-bold' : ''}">• Verdadero</span>
                            </div>
                            <div class="flex items-center">
                                <span class="${pregunta.respuesta_correcta === 'falso' ? 'text-green-500 font-bold' : ''}">• Falso</span>
                            </div>
                        </div>
                    `;
                } else {
                    opcionesHtml = `
                        <div class="ml-4 mt-2">
                            ${pregunta.opciones.map((opcion, opIndex) => `
                                <div class="flex items-center">
                                    <span class="${opIndex.toString() === pregunta.opciones_correcta ? 'text-green-500 font-bold' : ''}">
                                        • ${opcion.texto}
                                    </span>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }

                preguntaDiv.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">${index + 1}. ${pregunta.pregunta}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tipo: ${pregunta.tipo === 'verdadero_falso' ? 'Verdadero/Falso' : 'Opción Múltiple'}</p>
                            ${opcionesHtml}
                        </div>
                        <button onclick="eliminarPregunta(${index})" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                `;
                
                container.appendChild(preguntaDiv);
            });
        }

        function eliminarPregunta(index) {
            preguntas.splice(index, 1);
            document.getElementById('contadorPreguntas').textContent = preguntas.length;
            actualizarListaPreguntas();
            mostrarMensaje('Pregunta eliminada exitosamente');
        }

        // Inicializar el contenedor de listado de preguntas
        document.addEventListener('DOMContentLoaded', function() {
            const listadoContainer = document.createElement('div');
            listadoContainer.id = 'listadoPreguntas';
            listadoContainer.className = 'mt-8 space-y-4';
            document.querySelector('#paso2Content').appendChild(listadoContainer);
        });

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
        
        // Funciones para importar y descargar formato de preguntas
        function mostrarModalImportar() {
            // Crear y mostrar el modal
            const modalHTML = `
                <div id="modalImportar" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6 relative">
                        <button type="button" onclick="cerrarModalImportar()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Importar Preguntas</h3>
                        <p class="mb-4 text-gray-700 dark:text-gray-300">Selecciona un archivo .txt con las preguntas en el formato correcto.</p>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Archivo de preguntas (.txt)
                            </label>
                            <input type="file" 
                                id="archivoPreguntas" 
                                accept=".txt" 
                                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                onclick="cerrarModalImportar()"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                Cancelar
                            </button>
                            <button type="button" 
                                onclick="importarPreguntas()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Importar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
        
        function cerrarModalImportar() {
            const modal = document.getElementById('modalImportar');
            if (modal) {
                modal.remove();
            }
        }
        
        function importarPreguntas() {
            const fileInput = document.getElementById('archivoPreguntas');
            if (!fileInput.files || fileInput.files.length === 0) {
                mostrarError('Por favor selecciona un archivo.');
                return;
            }
            
            const file = fileInput.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                try {
                    const contenido = e.target.result;
                    const preguntasImportadas = procesarArchivoPreguntas(contenido);
                    
                    // Añadir las preguntas importadas a nuestro array
                    preguntasImportadas.forEach(pregunta => {
                        preguntas.push(pregunta);
                    });
                    
                    // Actualizar contador
                    document.getElementById('contadorPreguntas').textContent = preguntas.length;
                    
                    // Cerrar modal y mostrar mensaje
                    cerrarModalImportar();
                    mostrarMensaje(`Se importaron ${preguntasImportadas.length} preguntas correctamente`);
                } catch (error) {
                    mostrarError('Error al procesar el archivo: ' + error.message);
                }
            };
            
            reader.onerror = function() {
                mostrarError('Error al leer el archivo');
            };
            
            reader.readAsText(file);
        }
        
        function procesarArchivoPreguntas(contenido) {
            const lineas = contenido.split('\n');
            const preguntasImportadas = [];
            let preguntaActual = null;
            
            for (let i = 0; i < lineas.length; i++) {
                const linea = lineas[i].trim();
                
                // Saltamos líneas vacías y comentarios
                if (!linea || linea.startsWith('#')) continue;
                
                // Si la línea comienza con "P:", es una nueva pregunta
                if (linea.startsWith('P:')) {
                    // Si ya teníamos una pregunta en proceso, la guardamos
                    if (preguntaActual) {
                        preguntasImportadas.push(preguntaActual);
                    }
                    
                    // Iniciar nueva pregunta
                    preguntaActual = {
                        pregunta: linea.substring(2).trim(),
                        tipo: 'opcion_multiple',
                        opciones: [],
                        opciones_correcta: null // Propiedad que espera el servidor
                    };
                } 
                // Si la línea comienza con "O:", es una opción
                else if (linea.startsWith('O:') && preguntaActual) {
                    const textoOpcion = linea.substring(2).trim();
                    const esCorrecta = textoOpcion.endsWith('*');
                    const textoLimpio = esCorrecta ? textoOpcion.slice(0, -1).trim() : textoOpcion;
                    
                    // Añadir opción al array de opciones
                    const indiceOpcion = preguntaActual.opciones.length;
                    preguntaActual.opciones.push({
                        texto: textoLimpio
                    });
                    
                    // Si es correcta, guardar su índice
                    if (esCorrecta) {
                        preguntaActual.opciones_correcta = indiceOpcion.toString();
                    }
                }
            }
            
            // Añadir la última pregunta si existe
            if (preguntaActual) {
                preguntasImportadas.push(preguntaActual);
            }
            
            // Validar preguntas
            preguntasImportadas.forEach((pregunta, index) => {
                if (pregunta.opciones.length < 2) {
                    throw new Error(`La pregunta #${index + 1} debe tener al menos 2 opciones`);
                }
                
                if (pregunta.opciones_correcta === null) {
                    throw new Error(`La pregunta #${index + 1} debe tener al menos 1 opción correcta (marcada con *)`);
                }
            });
            
            return preguntasImportadas;
        }
        
        function descargarFormato() {
            // Contenido del archivo de formato
            const formatoContenido = `FORMATO PARA IMPORTAR PREGUNTAS DE OPCIÓN MÚLTIPLE

# INSTRUCCIONES:
# Cada pregunta debe comenzar con "P:" seguido del texto de la pregunta
# Cada opción debe comenzar con "O:" seguido del texto de la opción
# Para marcar una opción como correcta, añade un asterisco (*) al final del texto
# Debe haber al menos 2 opciones por pregunta y al menos 1 debe ser correcta
# Ejemplo:

P: ¿Cuál es la capital de Francia?
O: Madrid
O: París*
O: Londres
O: Berlín

P: ¿Cuál de los siguientes NO es un lenguaje de programación?
O: Python
O: Java
O: HTML*
O: C++

# Puede añadir tantas preguntas como desee siguiendo este formato
`;

            // Crear un blob con el contenido
            const blob = new Blob([formatoContenido], { type: 'text/plain' });
            
            // Crear un enlace para descargar
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'formato_preguntas.txt';
            
            // Simular clic en el enlace
            document.body.appendChild(a);
            a.click();
            
            // Limpiar
            document.body.removeChild(a);
            URL.revokeObjectURL(a.href);
            
            mostrarMensaje('Formato descargado correctamente');
        }
    </script>
    @endpush
</x-app-layout>