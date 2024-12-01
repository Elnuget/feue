<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Pago') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('pagos.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="matricula_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricula ID:</label>
                            <input type="text" name="matricula_id" id="matricula_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label for="metodo_pago_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metodo Pago ID:</label>
                            <input type="text" name="metodo_pago_id" id="metodo_pago_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto:</label>
                            <input type="text" name="monto" id="monto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label for="fecha_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Pago:</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>