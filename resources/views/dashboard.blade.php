<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }} 🖥️
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-200 mb-6">Cursos disponibles 📚</h3>
            
            @foreach($tiposCurso as $tipo)
                <div x-data="{ open: false }" class="mb-6">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <span class="text-xl font-semibold text-gray-900 dark:text-gray-200">
                            {{ $tipo->nombre }}
                        </span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cursosPorTipo->get($tipo->id, []) as $curso)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                                    <img src="{{ asset('storage/' . $curso->imagen) }}" alt="{{ $curso->nombre }}" class="w-full h-32 object-cover">
                                @else
                                    <img src="{{ asset('CursosDefecto.jpg') }}" alt="Curso por defecto" class="w-full h-32 object-cover">
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-200 flex items-center">
                                        <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                                        {{ $curso->nombre }} 🎓
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-300">{{ $curso->descripcion }}</p>
                                    <p class="text-gray-500 dark:text-gray-300"><i class="fas fa-clock mr-1"></i> {{ $curso->horario }}</p>
                                    <a href="{{ route('matriculas.create', ['curso_id' => $curso->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">
                                        <i class="fas fa-folder-plus mr-2"></i> Matricularme
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
