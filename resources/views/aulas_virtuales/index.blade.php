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

            <!-- Alerta de vista actual -->
            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas {{ $showAll ? 'fa-globe' : 'fa-user-circle' }} mr-2"></i>
                        <span class="block sm:inline font-medium">
                            {{ $showAll ? 'Mostrando todas las aulas virtuales' : 'Mostrando solo mis aulas virtuales' }}
                        </span>
                    </div>
                    <a href="{{ route('aulas_virtuales.index', ['show_all' => !$showAll]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded transition">
                        @if($showAll)
                            <i class="fas fa-user-circle mr-2"></i> Mostrar solo mis aulas
                        @else
                            <i class="fas fa-globe mr-2"></i> Mostrar todas las aulas
                        @endif
                    </a>
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

                    <!-- Filtros y búsqueda -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1">
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
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                                 data-is-associated="{{ $aula->usuarios->contains(auth()->id()) ? 'true' : 'false' }}">
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

                                    <!-- Botones en una sola fila -->
                                    <div class="grid grid-cols-3 gap-2">
                                        <!-- Botón de docentes -->
                                        @if($aula->usuarios->isNotEmpty())
                                        <button onclick="openModal('modal-docentes-{{ $aula->id }}')" 
                                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition flex items-center justify-center">
                                            <i class="fas fa-users mr-1"></i> 
                                            <span>{{ $aula->usuarios->count() }}</span>
                                        </button>

                                        <!-- Modal para mostrar docentes/administradores -->
                                        <div id="modal-docentes-{{ $aula->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 max-w-2xl w-full mx-4 my-8 max-h-[90vh] overflow-y-auto">
                                                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-gray-800 py-2">
                                                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-200">
                                                        Docentes y Administradores Asociados
                                                    </h3>
                                                    <button onclick="closeModal('modal-docentes-{{ $aula->id }}')" 
                                                            class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="space-y-3 sm:space-y-4">
                                                    @foreach($aula->usuarios as $usuario)
                                                        <div class="bg-gray-100 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0">
                                                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                                        {{ substr($usuario->name, 0, 1) }}
                                                                    </div>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm sm:text-base">
                                                                        {{ $usuario->name }}
                                                                    </h4>
                                                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                                                                        {{ $usuario->email }}
                                                                    </p>
                                                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                                                                        <span class="font-medium">Rol:</span> {{ $usuario->getRoleNameAttribute() }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition flex items-center justify-center opacity-50 cursor-not-allowed">
                                            <i class="fas fa-users mr-1"></i> 
                                            <span>0</span>
                                        </button>
                                        @endif
                                        
                                        <!-- Botón de asociar/desasociar -->
                                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                            @if($aula->usuarios->contains(auth()->id()))
                                                <form action="{{ route('aulas_virtuales.usuarios.disassociate', $aula) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="user_ids[]" value="{{ auth()->id() }}">
                                                    <button type="submit" 
                                                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition flex items-center justify-center">
                                                        <i class="fas fa-user-minus mr-1"></i> Dejar
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('aulas_virtuales.usuarios.associate', $aula) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="user_ids[]" value="{{ auth()->id() }}">
                                                    <button type="submit" 
                                                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex items-center justify-center">
                                                        <i class="fas fa-user-plus mr-1"></i> Tomar
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <div class="col-span-1"></div>
                                        @endif

                                        <!-- Botón de cursos -->
                                        @if($aula->cursos->count() > 0)
                                            <button onclick="openModal('modal-{{ $aula->id }}')" 
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex items-center justify-center">
                                                <i class="fas fa-book-open mr-1"></i>
                                                <span>{{ $aula->cursos->count() }}</span>
                                            </button>

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
                                        @else
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex items-center justify-center opacity-50 cursor-not-allowed">
                                                <i class="fas fa-book-open mr-1"></i>
                                                <span>0</span>
                                            </button>
                                        @endif
                                    </div>
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
            const showAll = {{ $showAll ? 'true' : 'false' }};
            const cards = document.querySelectorAll('#aulasGrid > div');
            
            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const isAssociated = card.dataset.isAssociated === 'true';
                
                const matchesSearch = title.includes(searchTerm) || 
                                    description.includes(searchTerm);
                const matchesFilter = showAll || isAssociated;
                
                card.style.display = (matchesSearch && matchesFilter) ? 'block' : 'none';
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
