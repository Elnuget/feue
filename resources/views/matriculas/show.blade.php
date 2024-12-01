<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de Matricula') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <strong>ID:</strong> {{ $matricula->id }}
                    </div>
                    <div class="mb-4">
                        <strong>Usuario:</strong> {{ $matricula->usuario->name }}
                    </div>
                    <div class="mb-4">
                        <strong>Curso:</strong> {{ $matricula->curso->nombre }}
                    </div>
                    <div class="mb-4">
                        <strong>Fecha de Matricula:</strong> {{ $matricula->fecha_matricula }}
                    </div>
                    <div class="mb-4">
                        <strong>Monto Total:</strong> {{ $matricula->monto_total }}
                    </div>
                    <div class="mb-4">
                        <strong>Valor Pendiente:</strong> {{ $matricula->valor_pendiente }}
                    </div>
                    <div class="mb-4">
                        <strong>Estado de Matricula:</strong> {{ $matricula->estado_matricula }}
                    </div>
                    <a href="{{ route('matriculas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Volver</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>