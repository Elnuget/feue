<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pago Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Matricula ID:</strong> {{ $pago->matricula_id }}</p>
                    <p><strong>Metodo Pago ID:</strong> {{ $pago->metodo_pago_id }}</p>
                    <p><strong>Monto:</strong> {{ $pago->monto }}</p>
                    <p><strong>Fecha Pago:</strong> {{ $pago->fecha_pago }}</p>
                    <a href="{{ route('pagos.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Back to Pagos
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>