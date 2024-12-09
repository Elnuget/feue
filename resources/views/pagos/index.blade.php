<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pagos') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('pagos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Pago</a>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Matricula</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Método de Pago</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Monto</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha de Pago</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($pagos as $pago)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $pago->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->matricula->usuario->name }} ({{ $pago->matricula->curso->nombre }})</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->metodoPago->nombre }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->monto }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->fecha_pago }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->estado }}</td>
                                        <td class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2">
                                            @if(auth()->user()->hasRole(1))
                                                @if($pago->estado == 'Pendiente')
                                                    <form action="{{ route('pagos.aprobar', $pago) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Aprobar</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('pagos.edit', $pago) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Editar</a>
                                                <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('pagos.show', $pago) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Ver</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>