<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Pago') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-gray-900 dark:text-gray-100 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Matrícula -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">🎓 Matrícula:</label>
                            <span class="block text-sm font-semibold mt-1">{{ $pago->matricula->curso->nombre }}</span>
                        </div>

                        <!-- Método de Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">💳 Método de Pago:</label>
                            <span class="block text-sm font-semibold mt-1">{{ $pago->metodoPago->nombre }}</span>
                        </div>

                        <!-- Comprobante de Pago (ocupa 2 columnas) -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">📎 Comprobante de Pago:</label>
                            @if($pago->comprobante_pago)
                                <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="block text-sm font-semibold text-blue-500 dark:text-blue-300 mt-1">
                                    Ver Comprobante
                                </a>
                            @else
                                <span class="block text-sm font-semibold mt-1">No disponible</span>
                            @endif
                        </div>

                        <!-- Valor Pendiente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">💰 Valor Pendiente:</label>
                            <span class="block text-sm font-semibold mt-1">{{ $pago->matricula->valor_pendiente }}</span>
                        </div>

                        <!-- Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">💲 Pago:</label>
                            <span class="block text-sm font-semibold mt-1">{{ $pago->monto }}</span>
                        </div>

                        <!-- Fecha de Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">📅 Fecha de Pago:</label>
                            <span class="block text-sm font-semibold mt-1">{{ $pago->fecha_pago }}</span>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">🔄 Estado:</label>
                            <span class="block text-sm font-semibold mt-1">{{ $pago->estado }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        @if($pago->estado == 'Pendiente')
                            <form action="{{ route('pagos.aprobar', $pago) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center w-40 bg-green-500 hover:bg-green-600 text-white font-semibold text-sm py-2 px-4 rounded-md shadow-md">
                                    Aprobar Pago
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('pagos.index') }}" class="inline-flex items-center justify-center w-40 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-semibold text-sm py-2 px-4 rounded-md shadow-md text-center">
                            Volver a Pagos
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
