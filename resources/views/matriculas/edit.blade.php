<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Matricula') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('matriculas.update', $matricula) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                            <input type="text" name="usuario_id" id="usuario_id" value="{{ $matricula->usuario_id }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                            <input type="text" name="curso_id" id="curso_id" value="{{ $matricula->curso_id }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Matricula</label>
                        <input type="date" name="fecha_matricula" id="fecha_matricula" value="{{ $matricula->fecha_matricula }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="monto_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Total</label>
                            <input type="number" name="monto_total" id="monto_total" value="{{ $matricula->monto_total }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="valor_pendiente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Pendiente</label>
                            <input type="number" name="valor_pendiente" id="valor_pendiente" value="{{ $matricula->valor_pendiente }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label for="estado_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado de Matricula</label>
                        <select name="estado_matricula" id="estado_matricula" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            <option value="Pendiente" {{ $matricula->estado_matricula == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Aprobada" {{ $matricula->estado_matricula == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                            <option value="Completada" {{ $matricula->estado_matricula == 'Completada' ? 'selected' : '' }}>Completada</option>
                            <option value="Rechazada" {{ $matricula->estado_matricula == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 active:bg-blue-700 dark:active:bg-blue-600 disabled:opacity-25 transition">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>