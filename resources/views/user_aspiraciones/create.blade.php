<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Aspiracion') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('user_aspiraciones.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="universidad_id" class="block text-sm font-medium text-gray-700">Universidad:</label>
                        <select name="universidad_id" id="universidad_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($universidades as $universidad)
                                <option value="{{ $universidad->id }}">{{ $universidad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Aspiracion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>