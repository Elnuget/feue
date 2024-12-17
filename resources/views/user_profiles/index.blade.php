<x-app-layout>
    @section('page_title', 'Perfil')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Profiles') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container mx-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Lista de Elementos</h1>
                <a href="{{ route('user_profiles.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                    Agregar Nuevo Perfil de Usuario
                </a>
            </div>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="px-4 py-3 text-left">Nombre</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Teléfono</th>
                                <th class="px-4 py-3 text-left">Fecha de Nacimiento</th>
                                <th class="px-4 py-3 text-left">Género</th>
                                <th class="px-4 py-3 text-left">Cédula</th>
                                <th class="px-4 py-3 text-left">Dirección</th>
                                <th class="px-4 py-3 text-left">Ciudad</th>
                                <th class="px-4 py-3 text-left">Provincia</th>
                                <th class="px-4 py-3 text-left">Código Postal</th>
                                <th class="px-4 py-3 text-left">Número de Referencia</th>
                                <th class="px-4 py-3 text-left">Último Inicio de Sesión</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $profile->user->name }}</td>
                                    <td class="px-4 py-3">{{ $profile->user->email }}</td>
                                    <td class="px-4 py-3">{{ $profile->phone }}</td>
                                    <td class="px-4 py-3">{{ $profile->birth_date }}</td>
                                    <td class="px-4 py-3">{{ $profile->gender }}</td>
                                    <td class="px-4 py-3">{{ $profile->cedula }}</td>
                                    <td class="px-4 py-3">{{ $profile->direccion_calle }}</td>
                                    <td class="px-4 py-3">{{ $profile->direccion_ciudad }}</td>
                                    <td class="px-4 py-3">{{ $profile->direccion_provincia }}</td>
                                    <td class="px-4 py-3">{{ $profile->codigo_postal }}</td>
                                    <td class="px-4 py-3">{{ $profile->numero_referencia }}</td>
                                    <td class="px-4 py-3">{{ $profile->last_login_at }}</td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <a href="{{ route('user_profiles.show', $profile->user_id) }}" class="text-blue-500 hover:underline">Ver</a>
                                        <a href="{{ route('user_profiles.edit', $profile->user_id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                        <form action="{{ route('user_profiles.destroy', $profile->user_id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este perfil de usuario?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>