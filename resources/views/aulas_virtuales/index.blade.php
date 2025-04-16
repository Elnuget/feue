<x-app-layout>
    @section('page_title', 'Aulas Virtuales')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes de alerta -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                    <p class="mt-2 text-sm">
                        💡 Sugerencia: Para archivos grandes, puedes:
                        <ul class="list-disc list-inside ml-4">
                            <li>Subirlos a Google Drive y compartir el enlace</li>
                            <li>Comprimir el archivo antes de subirlo</li>
                            <li>Dividir el contenido en archivos más pequeños</li>
                        </ul>
                    </p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <!-- Encabezado de la sección -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-200">
                            Aulas Virtuales
                        </h2>
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <a href="{{ route('aulas_virtuales.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                Crear Aula Virtual
                            </a>
                        @endif
                    </div>

                    <!-- Campo de búsqueda -->
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" 
                                   id="searchInput" 
                                   class="w-full px-4 py-2 pl-10 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                   placeholder="Buscar aulas virtuales..."
                                   oninput="filterCards()">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de tarjetas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="aulasGrid">
                        @foreach ($aulasVirtuales as $aula)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center gap-3">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $aula->nombre }}
                                            </h3>
                                            <a href="{{ route('aulas_virtuales.show', $aula) }}" 
                                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                                📓 Entrar
                                            </a>
                                        </div>
                                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('aulas_virtuales.edit', $aula) }}"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('aulas_virtuales.destroy', $aula) }}"
                                                      method="POST"
                                                      class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                                        {{ $aula->descripcion ?? 'Sin descripción' }}
                                    </p>
                                    
                                    @if($aula->cursos->count() > 0)
                                        <div class="mt-4">
                                            <button onclick="openModal('modal-{{ $aula->id }}')" 
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2">
                                                <span class="bg-white text-blue-500 rounded-full w-6 h-6 flex items-center justify-center">
                                                    {{ $aula->cursos->count() }}
                                                </span>
                                                Ver Cursos
                                            </button>
                                        </div>

                                        <!-- Modal para mostrar cursos -->
                                        <div id="modal-{{ $aula->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 max-w-2xl w-full mx-4 my-8 max-h-[90vh] overflow-y-auto">
                                                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-gray-800 py-2">
                                                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-200">
                                                        Cursos Asociados
                                                    </h3>
                                                    <button onclick="closeModal('modal-{{ $aula->id }}')" 
                                                            class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="space-y-3 sm:space-y-4">
                                                    @foreach($aula->cursos as $curso)
                                                        <div class="bg-gray-100 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm sm:text-base">
                                                                {{ $curso->nombre }}
                                                            </h4>
                                                            <div class="mt-2 space-y-1">
                                                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                                                                    <span class="font-medium">Tipo:</span> {{ $curso->tipoCurso->nombre ?? 'Sin sede' }}
                                                                </p>
                                                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                                                                    <span class="font-medium">Horario:</span> {{ $curso->horario ?? 'Sin horario' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para el filtrado y modales -->
    <script>
        function filterCards() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('#aulasGrid > div');
            
            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const cursos = Array.from(card.querySelectorAll('.bg-blue-100')).map(span => span.textContent.toLowerCase()).join(' ');
                
                const matchesSearch = title.includes(searchTerm) || 
                                    description.includes(searchTerm) ||
                                    cursos.includes(searchTerm);
                
                card.style.display = matchesSearch ? 'block' : 'none';
            });
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
                event.target.classList.remove('flex');
            }
        }
    </script>
</x-app-layout>
