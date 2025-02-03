<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-50"></div>

        <h1 class="text-2xl font-semibold text-gray-900">Users</h1>
        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">Create User</a>
        </div>
        <div class="flex justify-end mb-4">
            <input type="text" id="search" placeholder="Search users..." class="w-1/4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Photo</th>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Role</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr data-user-id="{{ $user->id }}">
                            <td class="py-2 px-4 border-b">
                                @if($user->profile && $user->profile->photo)
                                    <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="Profile Photo" class="w-10 h-10 rounded-full">
                                @else
                                    <span class="text-gray-500">No Photo</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->role_name ?? 'No Role' }}</td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">Edit</a>
                                <button onclick="confirmDelete({{ $user->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-4 rounded-lg shadow-lg mb-4`;
            toast.textContent = message;
            
            const container = document.getElementById('toast-container');
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function confirmDelete(userId) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                deleteUser(userId);
            }
        }

        async function deleteUser(userId) {
            try {
                const response = await fetch(`/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Eliminar la fila de la tabla sin recargar la página
                    const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                    if (row) {
                        row.remove();
                    }
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Error al eliminar usuario', 'error');
                }
            } catch (error) {
                showToast('Error al procesar la solicitud', 'error');
            }
        }

        // Función de búsqueda simple
        document.getElementById('search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>