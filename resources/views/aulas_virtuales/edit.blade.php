<x-app-layout>
    @section('page_title', 'Editar Aula Virtual')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-semibold mb-4">Editar Aula Virtual</h2>

                    <form action="{{ route('aulas_virtuales.update', $aulaVirtual) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600"
                                   value="{{ old('nombre', $aulaVirtual->nombre) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Descripción
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600">{{ old('descripcion', $aulaVirtual->descripcion) }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cursos Asociados
                            </label>
                            <div class="max-h-96 overflow-y-auto border rounded-lg dark:border-gray-600">
                                @foreach($cursos->chunk(2) as $chunk)
                                    <div class="grid grid-cols-2 gap-4 p-4 border-b dark:border-gray-600 last:border-b-0">
                                        @foreach($chunk as $curso)
                                            <div class="relative flex items-start p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" 
                                                           name="cursos[]" 
                                                           value="{{ $curso->id }}"
                                                           id="curso_{{ $curso->id }}"
                                                           {{ in_array($curso->id, $aulaVirtual->cursos->pluck('id')->toArray()) ? 'checked' : '' }}
                                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                </div>
                                                <div class="ml-3 flex-grow">
                                                    <label for="curso_{{ $curso->id }}" 
                                                           class="font-medium text-gray-700 dark:text-gray-200 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition">
                                                        {{ $curso->nombre }}
                                                    </label>
                                                    @if($curso->tipo_curso)
                                                        <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $curso->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $curso->tipo_curso->nombre }}
                                                        </span>
                                                    @endif
                                                    @if($curso->descripcion)
                                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ Str::limit($curso->descripcion, 100) }}
                                                        </p>
                                                    @endif
                                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        ID: {{ $curso->id }} | Cupos: {{ $curso->cupos ?? 'No definido' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('cursos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <a href="{{ route('aulas_virtuales.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
