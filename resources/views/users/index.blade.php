<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
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
                <tbody id="user-table-body">
                    @foreach ($users as $user)
                        <tr>
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
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const userTableBody = document.getElementById('user-table-body');
    const users = @json($users);

    // Helper to replace :id in route placeholders
    function routeReplace(route, id) {
        return route.replace(':id', id);
    }

    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();
        const filteredUsers = users.filter(user => 
            user.name.toLowerCase().includes(searchTerm) || 
            user.email.toLowerCase().includes(searchTerm)
        );
        userTableBody.innerHTML = renderUsers(filteredUsers);
    });

    function renderUsers(users) {
        return users.map(user => {
            const editUrl = routeReplace("{{ route('users.edit', ':id') }}", user.id);
            const deleteUrl = routeReplace("{{ route('users.destroy', ':id') }}", user.id);
            return `
                <tr>
                    <td class="py-2 px-4 border-b">
                        ${user.profile && user.profile.photo ? `<img src="{{ asset('storage/') }}/${user.profile.photo}" alt="Profile Photo" class="w-10 h-10 rounded-full">` : '<span class="text-gray-500">No Photo</span>'}
                    </td>
                    <td class="py-2 px-4 border-b">${user.name}</td>
                    <td class="py-2 px-4 border-b">${user.email}</td>
                    <td class="py-2 px-4 border-b">${user.role_name ?? 'No Role'}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="${editUrl}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">Edit</a>
                        <form action="${deleteUrl}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
            `;
        }).join('');
    }
});
</script>