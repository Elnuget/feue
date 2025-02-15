<x-app-layout>
    @section('page_title', $aula->nombre)
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-200">
                            {{ $aula->nombre }}
                        </h2>
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                Agregar Contenido
                            </button>
                        @endif
                    </div>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Lista de contenidos -->
                    <div class="space-y-6">
                        @foreach($aula->contenidos as $contenido)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                                        {{ $contenido->titulo }}
                                    </h3>
                                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                        <form action="{{ route('aulas_virtuales.contenidos.destroy', $contenido->id) }}" 
                                              method="POST" 
                                              class="ml-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-500 hover:text-red-700 transition-colors">
                                                <span class="sr-only">Eliminar</span>
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                @if($contenido->contenido)
                                    <div class="prose dark:prose-invert max-w-none mb-4 text-gray-600 dark:text-gray-300">
                                        {!! nl2br(e($contenido->contenido)) !!}
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-4 mt-4">
                                    @if($contenido->enlace)
                                        <a href="{{ $contenido->enlace }}" 
                                           target="_blank" 
                                           class="inline-flex items-center text-blue-500 hover:text-blue-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Abrir enlace
                                        </a>
                                    @endif

                                    @if($contenido->archivo)
                                        <a href="{{ Storage::url($contenido->archivo) }}" 
                                           target="_blank"
                                           class="inline-flex items-center text-green-500 hover:text-green-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Descargar archivo
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección de Cuestionarios -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                <span class="mr-2">📝</span>Cuestionarios
                            </h3>
                            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                <a href="{{ route('cuestionarios.create', $aula) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg text-sm transition-colors duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear Cuestionario
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($aula->cuestionarios as $cuestionario)
                                @php
                                    $intentosRealizados = $cuestionario->intentos()
                                        ->where('usuario_id', auth()->id())
                                        ->count();
                                    $puedeIniciar = $intentosRealizados < $cuestionario->intentos_permitidos;
                                @endphp

                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
                                    <!-- Encabezado con estado -->
                                    <div class="relative">
                                        <div class="absolute top-0 right-0 mt-4 mr-4">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                {{ $cuestionario->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $cuestionario->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Contenido principal -->
                                    <div class="p-6">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $cuestionario->titulo }}
                                        </h3>
                                        
                                        @if($cuestionario->descripcion)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                                {{ Str::limit($cuestionario->descripcion, 100) }}
                                            </p>
                                        @endif

                                        <!-- Información del cuestionario -->
                                        <div class="space-y-3 mb-6">
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $cuestionario->tiempo_limite }} minutos
                                            </div>
                                            
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Intentos: {{ $intentosRealizados }}/{{ $cuestionario->intentos_permitidos }}
                                            </div>
                                            
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ $cuestionario->permite_revision ? 'Revisión permitida' : 'Sin revisión' }}
                                            </div>

                                            @if($cuestionario->fecha_inicio && $cuestionario->fecha_fin)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($cuestionario->fecha_inicio)->format('d/m/Y H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($cuestionario->fecha_fin)->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="space-y-3">
                                            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                                <div class="flex gap-2">
                                                    <button onclick="editarCuestionario('{{ $cuestionario->id }}')"
                                                            class="flex-1 px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg transition-colors duration-300">
                                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Editar
                                                    </button>
                                                    
                                                    <button onclick="mostrarModalProgramacion('{{ $cuestionario->id }}')"
                                                            class="flex-1 px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm rounded-lg transition-colors duration-300">
                                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Programar
                                                    </button>
                                                    
                                                    <button onclick="confirmarEliminacion('{{ $cuestionario->id }}')"
                                                            class="flex-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors duration-300">
                                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </div>
                                            @endif

                                            <div class="flex justify-center">
                                                @if($puedeIniciar && $cuestionario->activo)
                                                    <a href="{{ route('cuestionarios.show', $cuestionario) }}" 
                                                       class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-center rounded-lg transition-colors duration-300">
                                                        Iniciar Cuestionario
                                                    </a>
                                                @elseif(!$puedeIniciar && $cuestionario->permite_revision)
                                                    <a href="{{ route('cuestionarios.revision', $cuestionario) }}" 
                                                       class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-center rounded-lg transition-colors duration-300">
                                                        Ver Revisión
                                                    </a>
                                                @else
                                                    <span class="w-full px-4 py-2 bg-gray-300 text-gray-600 text-center rounded-lg cursor-not-allowed">
                                                        No disponible
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full">
                                    <div class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                        <div class="rounded-full bg-gray-100 dark:bg-gray-700 p-6 mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            No hay cuestionarios disponibles
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm">
                                            Aún no se han creado cuestionarios para esta aula virtual.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Modal para agregar contenido -->
                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                        <div id="contenidoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold">Agregar Contenido</h3>
                                    <button onclick="toggleModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Tabs -->
                                <div class="mb-4 border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px" role="tablist">
                                        <li class="mr-2">
                                            <button onclick="switchTab('texto')" 
                                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-button active"
                                                    id="texto-tab">
                                                📝 Texto
                                            </button>
                                        </li>
                                        <li class="mr-2">
                                            <button onclick="switchTab('enlace')"
                                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-button"
                                                    id="enlace-tab">
                                                🔗 Enlace
                                            </button>
                                        </li>
                                        <li class="mr-2">
                                            <button onclick="switchTab('archivo')"
                                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-button"
                                                    id="archivo-tab">
                                                📎 Archivo
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                
                                <form action="{{ route('aulas_virtuales.contenidos.store', $aula) }}" 
                                      method="POST" 
                                      enctype="multipart/form-data"
                                      class="space-y-4">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Título</label>
                                        <input type="text" 
                                               name="titulo" 
                                               required
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div id="texto-content" class="tab-content">
                                        <label class="block text-sm font-medium mb-2">Contenido</label>
                                        <textarea name="contenido" 
                                                  rows="4"
                                                  class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>

                                    <div id="enlace-content" class="tab-content hidden">
                                        <label class="block text-sm font-medium mb-2">Enlace</label>
                                        <input type="url" 
                                               name="enlace"
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div id="archivo-content" class="tab-content hidden">
                                        <label class="block text-sm font-medium mb-2">Archivo</label>
                                        <input type="file" 
                                               name="archivo"
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div class="flex justify-end space-x-2 mt-6">
                                        <button type="button" 
                                                onclick="toggleModal()"
                                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                            Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal() {
            const modal = document.getElementById('contenidoModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function switchTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Mostrar el contenido seleccionado
            document.getElementById(`${tabName}-content`).classList.remove('hidden');
            
            // Actualizar estados de los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-500');
                button.classList.add('border-transparent');
            });
            
            // Activar el botón seleccionado
            const activeButton = document.getElementById(`${tabName}-tab`);
            activeButton.classList.add('border-blue-500', 'text-blue-500');
            activeButton.classList.remove('border-transparent');
        }

        function editarCuestionario(cuestionarioId) {
            window.location.href = `/cuestionarios/${cuestionarioId}/edit`;
        }

        function mostrarModalProgramacion(cuestionarioId) {
            // Crear o actualizar el modal
            let modal = document.getElementById('modalProgramacion');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'modalProgramacion';
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50';
                document.body.appendChild(modal);
            }

            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 relative">
                    <button onclick="cerrarModalProgramacion()" 
                            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        Programar Cuestionario
                    </h3>
                    
                    <form onsubmit="guardarProgramacion(event, '${cuestionarioId}')" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fecha de inicio
                            </label>
                            <input type="datetime-local" 
                                   name="fecha_inicio" 
                                   required
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fecha de fin
                            </label>
                            <input type="datetime-local" 
                                   name="fecha_fin" 
                                   required
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" 
                                    onclick="cerrarModalProgramacion()"
                                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModalProgramacion() {
            const modal = document.getElementById('modalProgramacion');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        async function guardarProgramacion(event, cuestionarioId) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch(`/cuestionarios/${cuestionarioId}/programar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        fecha_inicio: formData.get('fecha_inicio'),
                        fecha_fin: formData.get('fecha_fin')
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al programar el cuestionario');
                }

                // Mostrar mensaje de éxito
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
                toast.textContent = 'Cuestionario programado exitosamente';
                document.body.appendChild(toast);

                // Cerrar modal y recargar después de un momento
                cerrarModalProgramacion();
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

        function confirmarEliminacion(cuestionarioId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 text-center">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
                        ¿Eliminar cuestionario?
                    </h3>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Esta acción no se puede deshacer. ¿Estás seguro?
                    </p>
                    
                    <div class="flex justify-center gap-4">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button onclick="eliminarCuestionario('${cuestionarioId}', this)" 
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function eliminarCuestionario(cuestionarioId, button) {
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            
            try {
                const response = await fetch(`/cuestionarios/${cuestionarioId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al eliminar el cuestionario');
                }

                // Mostrar mensaje de éxito y recargar
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
                toast.textContent = 'Cuestionario eliminado exitosamente';
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
                button.parentElement.parentElement.parentElement.remove();
            }
        }
    </script>
    @endpush
</x-app-layout>