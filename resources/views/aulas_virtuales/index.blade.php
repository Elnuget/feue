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
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Cursos asociados:
                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($aula->cursos as $curso)
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $curso->nombre }} | {{ $curso->tipoCurso->nombre ?? 'Sin sede' }} | {{ $curso->horario ?? 'Sin horario' }}
                                                    </span>
                                                @endforeach
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

    <!-- Script para el filtrado -->
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
    </script>
</x-app-layout>
