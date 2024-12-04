<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de Matricula') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-id-badge"></i>
                                </span>
                                <input type="text" value="{{ $matricula->id }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" value="{{ $matricula->usuario->name }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-book"></i>
                                </span>
                                <input type="text" value="{{ $matricula->curso->nombre }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Matricula</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="text" value="{{ $matricula->fecha_matricula }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Total</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="text" value="{{ $matricula->monto_total }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Pendiente</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                <input type="text" value="{{ $matricula->valor_pendiente }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado de Matricula</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <input type="text" value="{{ $matricula->estado_matricula }}" readonly class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('matriculas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring focus:ring-gray-200 dark:focus:ring-gray-500 active:bg-gray-700 dark:active:bg-gray-600 disabled:opacity-25 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>