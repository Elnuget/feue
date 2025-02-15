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
                    <form id="cuestionarioForm" class="space-y-6">
                        @csrf
                        <input type="hidden" name="aula_virtual_id" value="{{ $aulaVirtual->id }}">

                        <!-- Información básica -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Título del Cuestionario
                                </label>
                                <input type="text" 
                                       name="titulo" 
                                       required 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Descripción
                                </label>
                                <textarea name="descripcion" 
                                          rows="2" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tiempo Límite (minutos)
                                </label>
                                <input type="number" 
                                       name="tiempo_limite" 
                                       required 
                                       min="1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Intentos Permitidos
                                </label>
                                <input type="number" 
                                       name="intentos_permitidos" 
                                       required 
                                       min="1" 
                                       value="1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" 
                                   name="permite_revision" 
                                   id="permite_revision" 
                                   class="rounded border-gray-300 dark:border-gray-600">
                            <label for="permite_revision" class="text-sm text-gray-700 dark:text-gray-300">
                                Permitir revisión después de finalizar
                            </label>
                        </div>

                        <button type="button" 
                                onclick="guardarCuestionario()" 
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Guardar y Continuar
                        </button>
                    </form>

                    <!-- Sección de Preguntas (inicialmente oculta) -->
                    <div id="preguntasSection" class="hidden mt-8">
                        <h3 class="text-lg font-semibold mb-4">Agregar Preguntas</h3>
                        
                        <form id="preguntaForm" class="space-y-4">
                            <input type="hidden" id="cuestionarioId" name="cuestionario_id">
                            
                            <div class="pregunta-container p-4 border rounded-lg">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Pregunta</label>
                                    <input type="text" 
                                           name="pregunta" 
                                           required 
                                           class="w-full rounded-md">
                                    
                                    <!-- Agregar esta sección para la imagen -->
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="file" 
                                                   id="imagen" 
                                                   name="imagen" 
                                                   accept="image/*"
                                                   class="hidden"
                                                   onchange="previewImage(event)">
                                            <label for="imagen" 
                                                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-lg cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Agregar Imagen
                                            </label>
                                            <span id="imagenNombre" class="text-sm text-gray-500 dark:text-gray-400"></span>
                                        </div>
                                        <div id="previewContainer" class="hidden mt-2">
                                            <img id="preview" class="max-w-xs rounded-lg shadow-lg">
                                            <button type="button" 
                                                    onclick="removeImage()"
                                                    class="mt-2 text-red-500 hover:text-red-700">
                                                Eliminar imagen
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Tipo de Pregunta</label>
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

                                <!-- Opciones Múltiples -->
                                <div id="opcionesMultiple" class="space-y-2">
                                    <div id="opcionesContainer" class="space-y-2">
                                        <!-- Las opciones se agregarán aquí -->
                                    </div>
                                    <button type="button" 
                                            onclick="agregarOpcion()" 
                                            class="text-blue-500 hover:text-blue-700">
                                        + Agregar Opción
                                    </button>
                                </div>

                                <!-- Verdadero/Falso -->
                                <div id="opcionesVF" class="hidden space-y-2">
                                    <div class="space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                   name="respuesta_correcta" 
                                                   value="verdadero">
                                            <span class="ml-2">Verdadero</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                   name="respuesta_correcta" 
                                                   value="falso">
                                            <span class="ml-2">Falso</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button type="button" 
                                        onclick="guardarPregunta()" 
                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Guardar Pregunta
                                </button>
                                <button type="button" 
                                        onclick="finalizarCuestionario()" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Finalizar Cuestionario
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
        let opcionCount = 0;

        // Inicializar con dos opciones
        document.addEventListener('DOMContentLoaded', function() {
            agregarOpcion();
            agregarOpcion();
        });

        function agregarOpcion() {
            const container = document.getElementById('opcionesContainer');
            const opcionDiv = document.createElement('div');
            opcionDiv.className = 'flex items-center space-x-2';
            opcionDiv.innerHTML = `
                <input type="text" 
                       name="opciones[${opcionCount}][texto]" 
                       required 
                       placeholder="Opción ${opcionCount + 1}" 
                       class="flex-1 rounded-md">
                <input type="radio" 
                       name="opciones_correcta" 
                       value="${opcionCount}" 
                       required>
                ${opcionCount > 1 ? `
                    <button type="button" 
                            onclick="eliminarOpcion(this)" 
                            class="text-red-500 hover:text-red-700">
                        Eliminar
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

        async function guardarCuestionario() {
            try {
                const formData = new FormData(document.getElementById('cuestionarioForm'));
                
                const response = await fetch('{{ route("cuestionarios.store", $aulaVirtual) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ...Object.fromEntries(formData),
                        modo: 'inicial'
                    })
                });

                if (!response.ok) throw new Error('Error al guardar el cuestionario');
                
                const data = await response.json();
                document.getElementById('cuestionarioId').value = data.cuestionario_id;
                document.getElementById('preguntasSection').classList.remove('hidden');
                document.getElementById('cuestionarioForm').classList.add('hidden');
                
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('hidden');
                    document.getElementById('imagenNombre').textContent = file.name;
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('imagen').value = '';
            document.getElementById('previewContainer').classList.add('hidden');
            document.getElementById('imagenNombre').textContent = '';
        }

        async function guardarPregunta() {
            try {
                const form = document.getElementById('preguntaForm');
                const formData = new FormData(form);
                const preguntaData = {
                    pregunta: formData.get('pregunta'),
                    tipo: formData.get('tipo'),
                };

                // Procesar imagen si existe
                const imageFile = document.getElementById('imagen').files[0];
                if (imageFile) {
                    const base64Image = await new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.readAsDataURL(imageFile);
                    });
                    preguntaData.imagen = base64Image;
                }

                if (preguntaData.tipo === 'verdadero_falso') {
                    preguntaData.respuesta_correcta = formData.get('respuesta_correcta');
                } else {
                    const opciones = [];
                    const opcionesInputs = document.querySelectorAll('[name^="opciones["]');
                    opcionesInputs.forEach(input => {
                        if (input.type === 'text') {
                            const match = input.name.match(/\[(\d+)\]/);
                            if (match) {
                                const index = match[1];
                                opciones[index] = { texto: input.value };
                            }
                        }
                    });
                    preguntaData.opciones = opciones;
                    preguntaData.opciones_correcta = formData.get('opciones_correcta');
                }

                const response = await fetch('{{ route("cuestionarios.store", $aulaVirtual) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        modo: 'preguntas',
                        cuestionario_id: document.getElementById('cuestionarioId').value,
                        preguntas: [preguntaData]
                    })
                });

                if (!response.ok) throw new Error('Error al guardar la pregunta');
                
                // Limpiar el formulario
                form.reset();
                document.getElementById('opcionesContainer').innerHTML = '';
                removeImage(); // Limpiar la imagen
                agregarOpcion();
                agregarOpcion();
                
                alert('Pregunta guardada exitosamente');
                
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function finalizarCuestionario() {
            try {
                // Verificar si hay preguntas guardadas
                if (!document.getElementById('cuestionarioId').value) {
                    alert('Debe guardar al menos una pregunta antes de finalizar');
                    return;
                }

                // Redirigir directamente al aula virtual sin crear más cuestionarios
                window.location.href = '/aulas_virtuales/{{ $aulaVirtual->id }}';
            } catch (error) {
                alert('Error al finalizar el cuestionario: ' + error.message);
            }
        }
    </script>
    @endpush
</x-app-layout>