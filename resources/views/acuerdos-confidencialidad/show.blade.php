<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Acuerdo de Confidencialidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <a href="{{ route('acuerdos-confidencialidad.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Volver') }}
                        </a>
                        <a href="{{ route('acuerdos-confidencialidad.edit', $acuerdoConfidencialidad->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Editar') }}
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Información del Usuario') }}</h3>
                            <div class="mb-2">
                                <span class="font-bold">{{ __('Nombre:') }}</span>
                                <span>{{ $acuerdoConfidencialidad->user->name }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-bold">{{ __('Email:') }}</span>
                                <span>{{ $acuerdoConfidencialidad->user->email }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Información del Curso') }}</h3>
                            <div class="mb-2">
                                <span class="font-bold">{{ __('Nombre del Curso:') }}</span>
                                <span>{{ $acuerdoConfidencialidad->curso->nombre }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-bold">{{ __('Código:') }}</span>
                                <span>{{ $acuerdoConfidencialidad->curso->codigo }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Estado del Acuerdo') }}</h3>
                        <div class="mb-2">
                            <span class="font-bold">{{ __('Estado:') }}</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $acuerdoConfidencialidad->estado === 'Entregado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $acuerdoConfidencialidad->estado }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="font-bold">{{ __('Fecha de Creación:') }}</span>
                            <span>{{ $acuerdoConfidencialidad->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-bold">{{ __('Última Actualización:') }}</span>
                            <span>{{ $acuerdoConfidencialidad->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Documento del Acuerdo') }}</h3>
                        @if($acuerdoConfidencialidad->acuerdo)
                            <div class="mb-4">
                                <a href="{{ Storage::url($acuerdoConfidencialidad->acuerdo) }}" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-file-pdf mr-2"></i>{{ __('Ver Documento PDF') }}
                                </a>
                            </div>
                        @else
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ __('No hay documento adjunto.') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end">
                        <form action="{{ route('acuerdos-confidencialidad.destroy', $acuerdoConfidencialidad->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar este acuerdo? Esta acción no se puede deshacer.') }}')">
                                <i class="fas fa-trash-alt mr-2"></i>{{ __('Eliminar') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
