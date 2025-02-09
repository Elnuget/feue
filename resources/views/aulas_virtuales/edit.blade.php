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

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                Cursos Asociados
                            </label>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($cursos as $curso)
                                    <div class="relative">
                                        <label for="curso_{{ $curso->id }}" 
                                               class="flex flex-col p-6 cursor-pointer bg-white dark:bg-gray-700 border rounded-lg 
                                                      hover:border-blue-500 hover:shadow-md transition-all
                                                      {{ $aulaVirtual->cursos->contains($curso->id) ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-200' }}">
                                            <div class="flex items-center justify-between mb-4">
                                                <input type="checkbox" 
                                                       name="cursos[]" 
                                                       id="curso_{{ $curso->id }}" 
                                                       value="{{ $curso->id }}"
                                                       class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                       {{ $aulaVirtual->cursos->contains($curso->id) ? 'checked' : '' }}>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $curso->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    ID: {{ $curso->id }}
                                                </span>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $curso->nombre_completo }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                                    {{ Str::limit($curso->descripcion_completa, 100) }}
                                                </p>
                                                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                                    <span>Sede: {{ $curso->tipoCurso->nombre }}</span>
                                                    <span>Horario: {{ $curso->horario }}</span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                                    <span>ID: {{ $curso->id }}</span>
                                                    <span>Cupos: {{ $curso->cupos ?? 'Ilimitado' }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
