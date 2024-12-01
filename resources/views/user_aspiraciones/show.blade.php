
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Aspiracion') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">User: {{ $aspiracion->user->name }}</h3>
                    <p class="mt-2 text-sm text-gray-600">Universidad: {{ $aspiracion->universidad->nombre ?? 'Unknown Universidad' }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user_aspiraciones.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>