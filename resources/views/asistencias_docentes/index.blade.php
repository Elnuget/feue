<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistencias Docentes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header con botón de crear -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Lista de Asistencias</h3>
                        <button onclick="openModal('createModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Nueva Asistencia
                        </button>
                    </div>

                    <!-- Tabla -->
                    <div class="overflow-x-auto relative">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Docente</th>
                                    <th scope="col" class="py-3 px-6">Fecha</th>
                                    <th scope="col" class="py-3 px-6">Hora Entrada</th>
                                    <th scope="col" class="py-3 px-6">Estado</th>
                                    <th scope="col" class="py-3 px-6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistencias as $asistencia)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">{{ $asistencia->docente->name }}</td>
                                    <td class="py-4 px-6">{{ $asistencia->fecha->format('d/m/Y') }}</td>
                                    <td class="py-4 px-6">{{ $asistencia->hora_entrada->format('H:i') }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($asistencia->estado === 'Presente') bg-green-100 text-green-800
                                            @elseif($asistencia->estado === 'Tarde') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $asistencia->estado }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex space-x-3">
                                            <button onclick="editAsistencia({{ $asistencia->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteAsistencia({{ $asistencia->id }})" 
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $asistencias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('asistencias-docentes.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Docente</label>
                            <select name="user_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Seleccione un docente</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                            <input type="date" name="fecha" required
                                value="{{ now()->format('Y-m-d') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Hora de Entrada</label>
                            <input type="time" name="hora_entrada" required
                                value="{{ now()->format('H:i') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                            <select name="estado" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Presente">Presente</option>
                                <option value="Tarde">Tarde</option>
                                <option value="Ausente">Ausente</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Observaciones</label>
                            <textarea name="observaciones"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button type="button" onclick="closeModal('createModal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Docente</label>
                            <input type="text" id="edit_docente" disabled
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                            <input type="date" name="fecha" id="edit_fecha" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Hora de Entrada</label>
                            <input type="time" name="hora_entrada" id="edit_hora_entrada" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                            <select name="estado" id="edit_estado" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Presente">Presente</option>
                                <option value="Tarde">Tarde</option>
                                <option value="Ausente">Ausente</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Observaciones</label>
                            <textarea name="observaciones" id="edit_observaciones"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Actualizar
                        </button>
                        <button type="button" onclick="closeModal('editModal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para manejar los modales -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        async function editAsistencia(id) {
            try {
                console.log('Intentando editar asistencia:', id);
                
                const response = await fetch(`/asistencias-docentes/${id}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();
                console.log('Respuesta del servidor:', data);

                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error('La asistencia no fue encontrada');
                    }
                    throw new Error(data.error || `Error del servidor: ${response.status}`);
                }

                if (!data.docente || !data.docente.name) {
                    console.error('Datos incompletos:', data);
                    throw new Error('Datos incompletos del docente');
                }

                // Actualizar el formulario con los datos
                document.getElementById('edit_docente').value = data.docente.name;
                document.getElementById('edit_fecha').value = data.fecha;
                document.getElementById('edit_hora_entrada').value = data.hora_entrada;
                document.getElementById('edit_estado').value = data.estado;
                document.getElementById('edit_observaciones').value = data.observaciones || '';
                
                // Actualizar la acción del formulario con el ID correcto
                document.getElementById('editForm').action = `/asistencias-docentes/${id}`;
                
                openModal('editModal');
            } catch (error) {
                console.error('Error completo:', error);
                alert('Error al cargar los datos de la asistencia: ' + error.message);
            }
        }

        function deleteAsistencia(id) {
            if (confirm('¿Está seguro de eliminar esta asistencia?')) {
                // Crear un formulario dinámicamente
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/asistencias-docentes/${id}`;
                
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfField);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
