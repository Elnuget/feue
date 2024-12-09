<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center space-x-2">
            <span>💰</span>
            <span>{{ __('Pagar') }}</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                            <div class="font-medium text-red-700 dark:text-red-300">⚠️ {{ __('Whoops! Algo salió mal.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }} 😞</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}"> <!-- Hidden input to store the authenticated user's ID -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="matricula_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🎓 Matricula:</label>
                                <select name="matricula_id" id="matricula_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out">
                                    @foreach($matriculas->where('usuario_id', auth()->id()) as $matricula)
                                        <option value="{{ $matricula->id }}" data-pendiente="{{ $matricula->valor_pendiente }}">{{ $matricula->curso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="metodo_pago_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">💳 Metodo Pago:</label>
                                <select name="metodo_pago_id" id="metodo_pago_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out">
                                    @foreach($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-2 hidden" id="comprobante_pago_container">
                                <label for="comprobante_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📎 Comprobante de Pago:</label>
                                <input type="file" name="comprobante_pago" id="comprobante_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out" accept=".png, .jpg, .jpeg, .pdf">
                            </div>

                            <div>
                                <label for="valor_pendiente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">💰 Valor Pendiente:</label>
                                <span id="valor_pendiente" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1"></span>
                            </div>

                            <div>
                                <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">💲 Pago:</label>
                                <input type="number" name="monto" id="monto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out">
                            </div>

                            <div>
                                <label for="fecha_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📅 Fecha Pago:</label>
                                <input type="date" name="fecha_pago" id="fecha_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out" value="{{ now()->timezone('America/Guayaquil')->toDateString() }}">
                            </div>

                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🔄 Estado:</label>
                                <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out" {{ auth()->user()->hasRole(1) ? '' : 'disabled' }}>
                                    <option value="Pendiente" {{ auth()->user()->hasRole(1) ? '' : 'selected' }}>Pendiente</option>
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-2 px-4 rounded-md shadow-lg transition-transform transform hover:scale-105 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            💾 Pagar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('matricula_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var pendiente = selectedOption.getAttribute('data-pendiente');
        var montoInput = document.getElementById('monto');
        var valorPendienteLabel = document.getElementById('valor_pendiente');
        montoInput.value = pendiente;
        montoInput.max = pendiente;
        valorPendienteLabel.textContent = pendiente;
    });

    document.getElementById('metodo_pago_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var comprobanteContainer = document.getElementById('comprobante_pago_container');
        if (selectedOption.value == 2) {
            comprobanteContainer.classList.remove('hidden');
        } else {
            comprobanteContainer.classList.add('hidden');
        }
    });

    // Trigger change events on page load
    document.getElementById('matricula_id').dispatchEvent(new Event('change'));
    document.getElementById('metodo_pago_id').dispatchEvent(new Event('change'));
</script>
