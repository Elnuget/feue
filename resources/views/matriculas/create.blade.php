<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Matricula') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('matriculas.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                            <input type="text" name="usuario_id" id="usuario_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                            <input type="text" name="curso_id" id="curso_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Matricula</label>
                        <input type="date" name="fecha_matricula" id="fecha_matricula" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="monto_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Total</label>
                            <input type="number" name="monto_total" id="monto_total" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="valor_pendiente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Pendiente</label>
                            <input type="number" name="valor_pendiente" id="valor_pendiente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label for="estado_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado de Matricula</label>
                        <select name="estado_matricula" id="estado_matricula" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Aprobada">Aprobada</option>
                            <option value="Completada">Completada</option>
                            <option value="Rechazada">Rechazada</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 active:bg-blue-700 dark:active:bg-blue-600 disabled:opacity-25 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>