<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pagos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('pagos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Pago
                    </a>
                    <ul class="mt-4">
                        @foreach ($pagos as $pago)
                            <li class="mb-2">
                                <div>
                                    <strong>Matricula:</strong> {{ $pago->matricula->usuario->name }} ({{ $pago->matricula->curso->nombre }})
                                </div>
                                <div>
                                    <strong>Método de Pago:</strong> {{ $pago->metodoPago->nombre }}
                                </div>
                                <div>
                                    <strong>Monto:</strong> {{ $pago->monto }}
                                </div>
                                <div>
                                    <strong>Fecha de Pago:</strong> {{ $pago->fecha_pago }}
                                </div>
                                <a href="{{ route('pagos.show', $pago) }}" class="text-blue-500 hover:underline">View</a>
                                <a href="{{ route('pagos.edit', $pago) }}" class="text-yellow-500 hover:underline ml-2">Edit</a>
                                <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>