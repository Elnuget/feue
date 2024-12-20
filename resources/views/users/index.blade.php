<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Users</h1>
        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">Create User</a>
        </div>
        <div class="flex gap-4 mb-4">
            <select id="tipo_curso" class="w-1/4 rounded-md border-gray-300">
                <option value="">Seleccione un tipo de curso</option>
                @foreach($tiposCursos as $tipoCurso)
                    <option value="{{ $tipoCurso->id }}">{{ $tipoCurso->nombre }}</option>
                @endforeach
            </select>
            <select id="curso" class="w-1/4 rounded-md border-gray-300" disabled>
                <option value="">Seleccione un curso</option>
            </select>
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
    const tipoCursoSelect = document.getElementById('tipo_curso');
    const cursoSelect = document.getElementById('curso');
    const userTableBody = document.getElementById('user-table-body');

    const cursos = @json($cursos);
    const users = @json($users);

    tipoCursoSelect.addEventListener('change', function() {
        if (tipoCursoSelect.value) {
            cursoSelect.disabled = false;
            const cursosFiltrados = cursos.filter(curso => curso.tipo_curso_id == tipoCursoSelect.value);
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
            cursosFiltrados.forEach(curso => {
                cursoSelect.innerHTML += `<option value="${curso.id}">${curso.nombre} (${curso.horario})</option>`;
            });
        } else {
            cursoSelect.disabled = true;
            cursoSelect.value = '';
            userTableBody.innerHTML = renderUsers(users);
        }
    });

    cursoSelect.addEventListener('change', function() {
        if (cursoSelect.value) {
            const filteredUsers = users.filter(user => user.cursos.some(curso => curso.id == cursoSelect.value));
            userTableBody.innerHTML = renderUsers(filteredUsers);
        } else {
            userTableBody.innerHTML = renderUsers(users);
        }
    });

    function renderUsers(users) {
        return users.map(user => `
            <tr>
                <td class="py-2 px-4 border-b">
                    ${user.profile && user.profile.photo ? `<img src="{{ asset('storage/') }}/${user.profile.photo}" alt="Profile Photo" class="w-10 h-10 rounded-full">` : '<span class="text-gray-500">No Photo</span>'}
                </td>
                <td class="py-2 px-4 border-b">${user.name}</td>
                <td class="py-2 px-4 border-b">${user.email}</td>
                <td class="py-2 px-4 border-b">${user.role_name ?? 'No Role'}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('users.edit', '') }}/${user.id}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">Edit</a>
                    <form action="{{ route('users.destroy', '') }}/${user.id}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Delete</button>
                    </form>
                </td>
            </tr>
        `).join('');
    }
});
</script>