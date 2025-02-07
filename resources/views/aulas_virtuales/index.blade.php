<x-app-layout>
    @section('page_title', 'Aulas Virtuales')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <!-- Encabezado de la sección -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-200">
                            Aulas Virtuales
                        </h2>
                        @if(auth()->user()->hasRole(1))
                            <a href="{{ route('aulas_virtuales.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                Crear Aula Virtual
                            </a>
                        @endif
                    </div>

                    <!-- Grid de tarjetas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($aulasVirtuales as $aula)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center gap-3">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $aula->nombre }}
                                            </h3>
                                            <a href="{{ route('aulas_virtuales.show', $aula) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                👁️
                                            </a>
                                        </div>
                                        @if(auth()->user()->hasRole(1))
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
                                                        {{ $curso->nombre }}
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
</x-app-layout>
