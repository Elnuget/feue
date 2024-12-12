<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }} 🖥️
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-200 mb-6">Cursos disponibles 📚</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                @foreach ($cursos as $curso)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                            <img src="{{ asset('storage/' . $curso->imagen) }}" alt="{{ $curso->nombre }}" class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                <i class="fas fa-book text-6xl text-gray-500 dark:text-gray-300"></i>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-200 flex items-center">
                                <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                                {{ $curso->nombre }} 🎓
                            </h3>
                            <p class="text-gray-500 dark:text-gray-300">{{ $curso->descripcion }}</p>
                            <p class="text-gray-900 dark:text-gray-200 font-bold">{{ $curso->precio }} $</p>
                            <p class="text-gray-500 dark:text-gray-300"><i class="fas fa-clock mr-1"></i> {{ $curso->horario }}</p>
                            <a href="{{ route('matriculas.create', ['curso_id' => $curso->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">
                                <i class="fas fa-shopping-cart mr-2"></i> Comprar 🛒
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
