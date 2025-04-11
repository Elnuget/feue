<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Create User</h1>
        
        <!-- Formulario con id para JavaScript -->
        <form id="createUserForm" class="mt-6 space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <select name="role_id" id="role_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl max-w-md w-full">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Confirmar Creación</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6">¿Estás seguro de que deseas crear este usuario?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button id="confirmBtn" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Error -->
    <div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl max-w-md w-full">
            <h2 class="text-xl font-bold mb-4 text-red-600">Error</h2>
            <div id="errorMessage" class="text-gray-700 dark:text-gray-300 mb-6">
                <ul class="list-disc pl-5 space-y-1"></ul>
            </div>
            <div class="flex justify-end">
                <button onclick="document.getElementById('errorModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createUserForm');
            const confirmationModal = document.getElementById('confirmationModal');
            const errorModal = document.getElementById('errorModal');
            const confirmBtn = document.getElementById('confirmBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            let formData = null;

            // Mostrar modal de confirmación al enviar el formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                formData = new FormData(form);
                confirmationModal.classList.remove('hidden');
                confirmationModal.classList.add('flex');
            });

            // Cancelar creación
            cancelBtn.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                confirmationModal.classList.remove('flex');
            });

            // Confirmar creación
            confirmBtn.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                
                fetch('{{ route('users.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route('users.index') }}';
                    } else {
                        showErrors(data);
                    }
                })
                .catch(error => {
                    showErrors({
                        message: 'Ha ocurrido un error al procesar la solicitud.',
                        errors: {}
                    });
                });
            });

            function showErrors(data) {
                const errorList = document.querySelector('#errorMessage ul');
                errorList.innerHTML = ''; // Limpiar errores anteriores
                
                // Si hay un mensaje general
                if (data.message) {
                    const li = document.createElement('li');
                    li.textContent = data.message;
                    errorList.appendChild(li);
                }
                
                // Si hay errores de validación
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        data.errors[field].forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            errorList.appendChild(li);
                        });
                    });
                }

                errorModal.classList.remove('hidden');
                errorModal.classList.add('flex');
            }

            // Limpiar errores al cerrar el modal
            document.querySelector('#errorModal button').addEventListener('click', function() {
                errorModal.classList.add('hidden');
                errorModal.classList.remove('flex');
                document.querySelector('#errorMessage ul').innerHTML = '';
            });
        });
    </script>
    @endpush
</x-app-layout>