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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Información del Acuerdo') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Usuario') }}
                            </p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $acuerdoConfidencialidad->user->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Curso') }}
                            </p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $acuerdoConfidencialidad->curso->nombre }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Estado') }}
                            </p>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $acuerdoConfidencialidad->estado === 'Entregado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $acuerdoConfidencialidad->estado }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Fecha de Creación') }}
                            </p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $acuerdoConfidencialidad->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Archivo PDF') }}
                        </p>
                        @if($acuerdoConfidencialidad->acuerdo)
                            <div class="mt-2">
                                <a href="{{ Storage::url($acuerdoConfidencialidad->acuerdo) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Ver PDF') }}
                                </a>
                            </div>
                        @else
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('No hay archivo PDF disponible') }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('acuerdos-confidencialidad.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Volver') }}
                        </a>
                        <a href="{{ route('acuerdos-confidencialidad.edit', $acuerdoConfidencialidad) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Editar') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 