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
    </script>
    @endpush
</x-app-layout>