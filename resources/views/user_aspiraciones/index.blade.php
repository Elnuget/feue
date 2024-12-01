<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Aspiraciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <a href="{{ route('user_aspiraciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Aspiracion</a>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Universidad</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($aspiraciones as $aspiracion)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $aspiracion->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $aspiracion->universidad->nombre ?? 'Unknown Universidad' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('user_aspiraciones.show', $aspiracion->universidad_id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                    <a href="{{ route('user_aspiraciones.edit', $aspiracion->universidad_id) }}" class="text-yellow-500 hover:text-yellow-700 ml-4">Edit</a>
                                    <form action="{{ route('user_aspiraciones.destroy', $aspiracion->universidad_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>