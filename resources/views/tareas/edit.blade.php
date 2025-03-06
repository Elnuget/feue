<x-app-layout>
    @section('page_title', 'Editar Tarea')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Editar Tarea</h2>

                    <form action="{{ route('tareas.update', $tarea) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                            <input type="text" 
                                   name="titulo" 
                                   value="{{ old('titulo', $tarea->titulo) }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                            <textarea name="descripcion" 
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">{{ old('descripcion', $tarea->descripcion) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Límite</label>
                                <input type="date" 
                                       name="fecha_limite" 
                                       value="{{ old('fecha_limite', $tarea->fecha_limite->format('Y-m-d')) }}"
                                       required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Puntos Máximos</label>
                                <input type="number" 
                                       name="puntos_maximos" 
                                       value="{{ old('puntos_maximos', $tarea->puntos_maximos) }}"
                                       required
                                       min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select name="estado" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                                <option value="activo" {{ $tarea->estado === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ $tarea->estado === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enlaces</label>
                            <div id="enlaces-container">
                                @if($tarea->enlaces && count($tarea->enlaces) > 0)
                                    @foreach($tarea->enlaces as $enlace)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <input type="url" 
                                                 name="enlaces[]" 
                                                 value="{{ $enlace }}"
                                                 placeholder="https://ejemplo.com"
                                                 class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                                            <button type="button" onclick="addEnlaceField(this)" class="px-2 py-1 bg-blue-500 text-white rounded">+</button>
                                            <button type="button" onclick="removeEnlaceField(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center space-x-2 mb-2">
                                        <input type="url" 
                                             name="enlaces[]" 
                                             placeholder="https://ejemplo.com"
                                             class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                                        <button type="button" onclick="addEnlaceField(this)" class="px-2 py-1 bg-blue-500 text-white rounded">+</button>
                                        <button type="button" onclick="removeEnlaceField(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addNewEnlaceField()" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                                + Añadir otro enlace
                            </button>
                        </div>

                        <div class="flex justify-end space-x-2 mt-6">
                            <a href="{{ route('aulas_virtuales.show', $tarea->aulaVirtual) }}"
                               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                Actualizar Tarea
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Funciones para manejo de enlaces
        function addEnlaceField(button) {
            const container = button.closest('div');
            const newField = container.cloneNode(true);
            newField.querySelector('input').value = '';
            container.parentNode.insertBefore(newField, container.nextSibling);
        }

        function removeEnlaceField(button) {
            const container = button.closest('div');
            const parent = container.parentNode;
            
            // No eliminar si es el único campo
            if (parent.children.length > 1) {
                parent.removeChild(container);
            } else {
                container.querySelector('input').value = '';
            }
        }

        function addNewEnlaceField() {
            const container = document.getElementById('enlaces-container');
            const newField = document.createElement('div');
            newField.className = 'flex items-center space-x-2 mb-2';
            newField.innerHTML = `
                <input type="url" 
                     name="enlaces[]" 
                     placeholder="https://ejemplo.com"
                     class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                <button type="button" onclick="addEnlaceField(this)" class="px-2 py-1 bg-blue-500 text-white rounded">+</button>
                <button type="button" onclick="removeEnlaceField(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
            `;
            container.appendChild(newField);
        }
    </script>
    @endpush
</x-app-layout> 