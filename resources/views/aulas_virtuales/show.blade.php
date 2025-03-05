<x-app-layout>
    @section('page_title', $aulasVirtuale->nombre)
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-200">
                            {{ $aulasVirtuale->nombre }}
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
                        @foreach($aulasVirtuale->contenidos as $contenido)
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

                    <!-- Lista de tareas -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            Tareas
                        </h3>
                        
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <div class="mb-4">
                                <button onclick="toggleTareaModal()" 
                                        class="inline-flex items-center bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition">
                                    <span class="mr-2">📚</span> Crear Tarea
                                </button>
                            </div>
                        @endif

                        <div class="space-y-4">
                            @forelse($aulasVirtuale->tareas as $tarea)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $tarea->titulo }}
                                            </h4>
                                            @if($tarea->descripcion)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $tarea->descripcion }}
                                                </p>
                                            @endif

                                            @if($tarea->imagenes && count($tarea->imagenes) > 0)
                                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                                                    @foreach($tarea->imagenes as $imagen)
                                                        <div class="relative group">
                                                            <img src="{{ asset('storage/' . $imagen) }}" 
                                                                 alt="Imagen de la tarea"
                                                                 class="w-full h-32 object-cover rounded-lg shadow-sm">
                                                            <a href="{{ asset('storage/' . $imagen) }}" 
                                                               target="_blank"
                                                               class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center">
                                                                <span class="text-white opacity-0 group-hover:opacity-100">
                                                                    Ver imagen
                                                                </span>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($tarea->archivos && count($tarea->archivos) > 0)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach($tarea->archivos as $archivo)
                                                        <a href="{{ asset('storage/' . $archivo) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition-colors">
                                                            <span class="mr-1">📎</span>
                                                            {{ basename($archivo) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    📅 Fecha límite: {{ $tarea->fecha_limite->format('d/m/Y') }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    💯 Puntos máximos: {{ $tarea->puntos_maximos }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                                <a href="{{ route('tareas.edit', $tarea) }}" 
                                                   class="text-yellow-500 hover:text-yellow-700 transition-colors">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('tareas.destroy', $tarea) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="redirect_to" value="{{ route('aulas_virtuales.show', $aulasVirtuale) }}">
                                                    <button type="submit" 
                                                            class="text-red-500 hover:text-red-700 transition-colors">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif
                                            @if($tarea->fecha_limite->isFuture())
                                                <button onclick="toggleEntregaModal({{ $tarea->id }})"
                                                        class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                    Entregar 📤
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if($tarea->entregas->count() > 0)
                                        <div class="mt-4 border-t pt-4">
                                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Entregas:</h5>
                                            <div class="space-y-2">
                                                @foreach($tarea->entregas as $entrega)
                                                    <div class="flex items-center justify-between bg-white dark:bg-gray-600 p-2 rounded">
                                                        <div class="flex items-center">
                                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                                {{ $entrega->user->name }} - 
                                                                {{ $entrega->created_at->format('d/m/Y H:i') }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            @if($entrega->archivo)
                                                                <a href="{{ Storage::url($entrega->archivo) }}" 
                                                                   target="_blank"
                                                                   class="text-blue-500 hover:text-blue-700">
                                                                    📎 Ver archivo
                                                                </a>
                                                            @endif
                                                            @if($entrega->enlace)
                                                                <a href="{{ $entrega->enlace }}" 
                                                                   target="_blank"
                                                                   class="text-blue-500 hover:text-blue-700">
                                                                    🔗 Ver enlace
                                                                </a>
                                                            @endif
                                                            @if($entrega->calificacion)
                                                                <span class="text-green-500 font-semibold">
                                                                    {{ $entrega->calificacion }}/{{ $tarea->puntos_maximos }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay tareas disponibles en esta aula virtual.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Lista de cuestionarios -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            Cuestionarios
                        </h3>
                        
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <div class="mb-4">
                                <a href="{{ route('cuestionarios.create', $aulasVirtuale) }}" 
                                   class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                    <span class="mr-2">📝</span> Crear Cuestionario
                                </a>
                            </div>
                        @endif

                        <div class="space-y-4">
                            @forelse($aulasVirtuale->cuestionarios as $cuestionario)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $cuestionario->titulo }}
                                            </h4>
                                            @if($cuestionario->descripcion)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $cuestionario->descripcion }}
                                                </p>
                                            @endif
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    ⏱️ {{ $cuestionario->tiempo_limite }} minutos
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    🔄 {{ $cuestionario->intentos_permitidos }} intentos permitidos
                                                </span>
                                                @if($cuestionario->activo)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        ✅ Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        ❌ Inactivo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                                <a href="{{ route('cuestionarios.edit', $cuestionario) }}" 
                                                   class="text-yellow-500 hover:text-yellow-700 transition-colors">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('cuestionarios.destroy', $cuestionario) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cuestionario? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="redirect_to" value="{{ route('aulas_virtuales.show', $aulasVirtuale) }}">
                                                    <button type="submit" 
                                                            class="text-red-500 hover:text-red-700 transition-colors">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif
                                            @if($cuestionario->activo)
                                                <a href="{{ route('cuestionarios.show', $cuestionario) }}" 
                                                   class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                    Realizar 📝
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay cuestionarios disponibles en esta aula virtual.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Modal para crear tarea -->
                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                        <div id="tareaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl my-8 mx-auto max-h-[90vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-gray-800 z-10 py-2">
                                    <h3 class="text-lg font-bold">Crear Nueva Tarea</h3>
                                    <button onclick="toggleTareaModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ route('tareas.store', $aulasVirtuale) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="tareaForm">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Título</label>
                                        <input type="text" 
                                               name="titulo" 
                                               required
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Descripción</label>
                                        <textarea name="descripcion" 
                                                  rows="4"
                                                  class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Imágenes (máx. 10MB por imagen)</label>
                                        <div class="flex items-center">
                                            <input type="file" 
                                                   id="imagenes-input"
                                                   accept="image/*"
                                                   class="hidden"
                                                   onchange="handleFileSelection(this, 'imagenes')">
                                            <button type="button" 
                                                    onclick="document.getElementById('imagenes-input').click()"
                                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                                Seleccionar imágenes
                                            </button>
                                            <span id="imagenes-count" class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                                0 imágenes seleccionadas
                                            </span>
                                        </div>
                                        <div id="imagenes-error" class="hidden mt-2 text-sm text-red-600"></div>
                                        <div id="imagenes-preview" class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2"></div>
                                        <div id="imagenes-container"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Archivos adjuntos (máx. 10MB por archivo)</label>
                                        <div class="flex items-center">
                                            <input type="file" 
                                                   id="archivos-input"
                                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
                                                   class="hidden"
                                                   onchange="handleFileSelection(this, 'archivos')">
                                            <button type="button" 
                                                    onclick="document.getElementById('archivos-input').click()"
                                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                                Seleccionar archivos
                                            </button>
                                            <span id="archivos-count" class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                                0 archivos seleccionados
                                            </span>
                                        </div>
                                        <div id="archivos-error" class="hidden mt-2 text-sm text-red-600"></div>
                                        <div id="archivos-preview" class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2"></div>
                                        <div id="archivos-container"></div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Formatos permitidos: PDF, Word, Excel, PowerPoint, ZIP, RAR
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Fecha Límite</label>
                                            <input type="date" 
                                                   name="fecha_limite" 
                                                   required
                                                   value="{{ date('Y-m-d') }}"
                                                   min="{{ date('Y-m-d') }}"
                                                   class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-2">Puntos Máximos</label>
                                            <input type="number" 
                                                   name="puntos_maximos" 
                                                   required
                                                   min="0"
                                                   value="10"
                                                   class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-2 mt-6 sticky bottom-0 bg-white dark:bg-gray-800 py-2">
                                        <button type="button" 
                                                onclick="toggleTareaModal()"
                                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                id="submit-tarea-btn"
                                                class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition-colors">
                                            Crear Tarea
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Modal para entregar tarea -->
                    <div id="entregaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold">Entregar Tarea</h3>
                                <button onclick="toggleEntregaModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <form id="entregaForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Archivo (máximo 10MB)</label>
                                    <input type="file" 
                                           name="archivo"
                                           id="entrega-archivo"
                                           onchange="validarTamanoArchivoEntrega(this)"
                                           class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    <div id="entrega-archivo-error" class="hidden mt-2 text-sm text-red-600"></div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">O enlace de Google Drive</label>
                                    <input type="url" 
                                           name="enlace"
                                           placeholder="https://drive.google.com/..."
                                           class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" 
                                            onclick="toggleEntregaModal()"
                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                                        Entregar
                                    </button>
                                </div>
                            </form>
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
                                
                                <form action="{{ route('aulas_virtuales.contenidos.store', $aulasVirtuale) }}" 
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
                                               id="archivo-input"
                                               onchange="validarArchivos(this, 'archivo')"
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                        <div id="archivo-error" class="hidden mt-2 text-sm text-red-600"></div>
                                        <div id="archivo-warning" class="hidden mt-2 p-4 bg-yellow-100 text-yellow-700 rounded-lg">
                                            El archivo es demasiado grande (más de 10MB). Por favor:
                                            <ol class="list-decimal ml-4 mt-2">
                                                <li>Sube tu archivo a Google Drive</li>
                                                <li>Copia el enlace de compartir</li>
                                                <li>Usa la pestaña "Enlace" para compartir el archivo</li>
                                            </ol>
                                        </div>
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

        function validarTamanoArchivo(input) {
            const archivo = input.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB en bytes
            const errorDiv = document.getElementById('archivo-error');
            const warningDiv = document.getElementById('archivo-warning');
            const submitButton = input.closest('form').querySelector('button[type="submit"]');

            if (archivo && archivo.size > maxSize) {
                input.value = ''; // Limpiar el input
                errorDiv.textContent = 'El archivo excede el límite de 10MB.';
                errorDiv.classList.remove('hidden');
                warningDiv.classList.remove('hidden');
                submitButton.disabled = true;
            } else {
                errorDiv.classList.add('hidden');
                warningDiv.classList.add('hidden');
                submitButton.disabled = false;
            }
        }

        function toggleTareaModal() {
            const modal = document.getElementById('tareaModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
            
            // Limpiar formulario al cerrar
            if (modal.classList.contains('hidden')) {
                resetTareaForm();
            }
        }

        function resetTareaForm() {
            document.getElementById('tareaForm').reset();
            document.getElementById('imagenes-preview').innerHTML = '';
            document.getElementById('archivos-preview').innerHTML = '';
            document.getElementById('imagenes-container').innerHTML = '';
            document.getElementById('archivos-container').innerHTML = '';
            document.getElementById('imagenes-count').textContent = '0 imágenes seleccionadas';
            document.getElementById('archivos-count').textContent = '0 archivos seleccionados';
            document.getElementById('imagenes-error').classList.add('hidden');
            document.getElementById('archivos-error').classList.add('hidden');
        }

        function toggleEntregaModal(tareaId = null) {
            const modal = document.getElementById('entregaModal');
            const form = document.getElementById('entregaForm');
            
            if (tareaId) {
                form.action = `/tareas/${tareaId}/entregar`;
            }
            
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function validarTamanoArchivoEntrega(input) {
            const archivo = input.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB en bytes
            const errorDiv = document.getElementById('entrega-archivo-error');
            const submitButton = input.closest('form').querySelector('button[type="submit"]');

            if (archivo && archivo.size > maxSize) {
                input.value = ''; // Limpiar el input
                errorDiv.textContent = 'El archivo excede el límite de 10MB. Por favor, usa un enlace de Google Drive.';
                errorDiv.classList.remove('hidden');
                submitButton.disabled = true;
            } else {
                errorDiv.classList.add('hidden');
                submitButton.disabled = false;
            }
        }

        // Sistema mejorado para manejar múltiples archivos
        const filesData = {
            imagenes: [],
            archivos: []
        };

        function handleFileSelection(input, tipo) {
            const maxSize = 10 * 1024 * 1024; // 10MB por archivo
            const maxFiles = 5; // Máximo 5 archivos
            const errorDiv = document.getElementById(`${tipo}-error`);
            const previewDiv = document.getElementById(`${tipo}-preview`);
            const countSpan = document.getElementById(`${tipo}-count`);
            const containerDiv = document.getElementById(`${tipo}-container`);
            
            errorDiv.classList.add('hidden');
            
            const tiposPermitidos = tipo === 'imagenes' 
                ? ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
                : ['application/pdf', 
                   'application/msword', 
                   'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                   'application/vnd.ms-powerpoint',
                   'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                   'application/vnd.ms-excel',
                   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                   'application/zip',
                   'application/x-rar-compressed',
                   'application/octet-stream'];
            
            // Verificar límite de archivos
            if (filesData[tipo].length + input.files.length > maxFiles) {
                errorDiv.textContent = `Solo puedes subir un máximo de ${maxFiles} archivos.`;
                errorDiv.classList.remove('hidden');
                return;
            }
            
            // Procesar cada archivo seleccionado
            let validFilesCount = 0;
            Array.from(input.files).forEach(file => {
                // Verificar si el archivo ya existe en nuestra colección
                const isDuplicate = filesData[tipo].some(existingFile => 
                    existingFile.name === file.name && existingFile.size === file.size
                );
                
                if (isDuplicate) {
                    return; // Saltar archivos duplicados
                }
                
                // Verificar tipo de archivo
                const extension = file.name.split('.').pop().toLowerCase();
                const extensionesPermitidas = tipo === 'imagenes' 
                    ? ['jpg', 'jpeg', 'png', 'gif', 'webp']
                    : ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar'];
                
                if (!extensionesPermitidas.includes(extension) && !tiposPermitidos.includes(file.type)) {
                    errorDiv.textContent = `El archivo "${file.name}" no es de un formato permitido.`;
                    errorDiv.classList.remove('hidden');
                    return;
                }
                
                // Verificar tamaño de archivo
                if (file.size > maxSize) {
                    errorDiv.textContent = `El archivo "${file.name}" excede el límite de 10MB.`;
                    errorDiv.classList.remove('hidden');
                    return;
                }
                
                // Agregar a nuestra colección
                filesData[tipo].push(file);
                validFilesCount++;
                
                // Crear vista previa
                createFilePreview(file, tipo);
            });
            
            // Actualizar contador
            updateFileCount(tipo);
            
            // Limpiar input para permitir seleccionar el mismo archivo nuevamente
            input.value = '';
            
            // Regenerar los input fields ocultos para el envío del formulario
            regenerateFileInputs(tipo);
        }
        
        function createFilePreview(file, tipo) {
            const previewDiv = document.getElementById(`${tipo}-preview`);
            const fileId = `${tipo}-${Date.now()}-${file.name}`;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative border rounded-lg p-3 bg-gray-50 dark:bg-gray-700';
                div.dataset.fileId = fileId;
                
                let iconHTML = '';
                if (tipo === 'imagenes') {
                    iconHTML = `<img src="${e.target.result}" class="w-full h-32 object-contain rounded-lg mb-2">`;
                } else {
                    const extension = file.name.split('.').pop().toLowerCase();
                    const iconClass = getFileIconClass(extension);
                    iconHTML = `<div class="w-12 h-12 mx-auto mb-2 ${iconClass}"></div>`;
                }
                
                div.innerHTML = `
                    ${iconHTML}
                    <div class="text-center text-sm truncate font-medium mb-1">${file.name}</div>
                    <div class="text-center text-xs text-gray-500">${formatFileSize(file.size)}</div>
                    <button type="button" 
                            onclick="removeFile('${fileId}', '${tipo}')" 
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-600 text-xs">
                        ×
                    </button>
                `;
                previewDiv.appendChild(div);
            };
            reader.readAsDataURL(file);
            
            // Agregar un atributo de ID único al archivo para referencia
            file.fileId = fileId;
        }
        
        function regenerateFileInputs(tipo) {
            const containerDiv = document.getElementById(`${tipo}-container`);
            containerDiv.innerHTML = ''; // Limpiar contenedor
            
            // Crear un FormData para cada archivo
            filesData[tipo].forEach((file, index) => {
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = `${tipo}[]`;
                fileInput.classList.add('hidden');
                fileInput.dataset.fileId = file.fileId;
                
                // Crear un nuevo FileList con solo este archivo
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                
                containerDiv.appendChild(fileInput);
            });
        }
        
        function updateFileCount(tipo) {
            const countSpan = document.getElementById(`${tipo}-count`);
            const count = filesData[tipo].length;
            countSpan.textContent = count === 1 
                ? `1 ${tipo === 'imagenes' ? 'imagen' : 'archivo'} seleccionado` 
                : `${count} ${tipo === 'imagenes' ? 'imágenes' : 'archivos'} seleccionados`;
        }
        
        function removeFile(fileId, tipo) {
            // Eliminar el archivo de nuestra colección
            const fileIndex = filesData[tipo].findIndex(file => file.fileId === fileId);
            if (fileIndex > -1) {
                filesData[tipo].splice(fileIndex, 1);
            }
            
            // Eliminar el elemento de vista previa
            const previewDiv = document.getElementById(`${tipo}-preview`);
            const filePreview = previewDiv.querySelector(`[data-file-id="${fileId}"]`);
            if (filePreview) {
                filePreview.remove();
            }
            
            // Actualizar contador y regenerar inputs
            updateFileCount(tipo);
            regenerateFileInputs(tipo);
        }

        function getFileIconClass(extension) {
            const iconMap = {
                'pdf': 'text-red-500',
                'doc': 'text-blue-500',
                'docx': 'text-blue-500',
                'xls': 'text-green-500',
                'xlsx': 'text-green-500',
                'ppt': 'text-orange-500',
                'pptx': 'text-orange-500',
                'zip': 'text-yellow-500',
                'rar': 'text-yellow-500'
            };
            return `text-4xl ${iconMap[extension] || 'text-gray-500'}`;
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' bytes';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
    </script>
    @endpush
</x-app-layout>