<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center space-x-2">
            <span>💰</span>
            <span>{{ __('Pagar') }}</span>
        </h2>
    </x-slot>

    {{-- Estilos y scripts de TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-gray-900 dark:text-gray-100">

                    {{-- Mostrar errores si los hay --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                            <div class="font-medium text-red-700 dark:text-red-300">
                                ⚠️ {{ __('Whoops! Algo salió mal.') }}
                            </div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }} 😞</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario de búsqueda --}}
                    <form method="GET" action="{{ route('pagos.create') }}" class="mb-6">
                        <div class="flex gap-4 mb-4">
                            <input type="text" name="search" id="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Buscar matrícula..." 
                                   class="w-full rounded-md border-gray-300">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buscar
                            </button>
                        </div>
                    </form>

                    {{-- Formulario de pagos --}}
                    <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Seleccionar matrícula --}}
                            <div>
                                <label for="matricula_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    🎓 Matricula:
                                </label>
                                <select name="matricula_id" id="matricula_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700"
                                        autocomplete="off">
                                    <option value="" data-pendiente="0">Buscar matrícula...</option>
                                    @foreach($matriculas as $matricula)
                                        <option
                                            value="{{ $matricula->id }}"
                                            data-pendiente="{{ number_format($matricula->valor_pendiente, 2) }}"
                                            {{ $selectedMatricula && $selectedMatricula->id == $matricula->id ? 'selected' : '' }}>
                                            {{-- Organiza la info en una sola línea: --}}
                                            M{{ $matricula->id }}&nbsp;|&nbsp;
                                            {{ $matricula->curso->nombre }}&nbsp;|&nbsp;
                                            {{ $matricula->usuario->name }}&nbsp;|&nbsp;
                                            Pendiente: ${{ number_format($matricula->valor_pendiente, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Método de pago --}}
                            <div>
                                <label for="metodo_pago_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    💳 Método de Pago:
                                </label>
                                <select name="metodo_pago_id" id="metodo_pago_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700">
                                    @foreach($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Comprobante de pago (mostrar/ocultar según método) --}}
                            <div class="col-span-1 md:col-span-2 hidden" id="comprobante_pago_container">
                                <label for="comprobante_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    📎 Comprobante de Pago:
                                </label>
                                <input type="file" name="comprobante_pago" id="comprobante_pago"
                                       accept=".png, .jpg, .jpeg, .pdf"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700">
                            </div>

                            {{-- Valor Pendiente (combobox) --}}
                            <div>
                                <label for="valor_pendiente_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    💰 Valor Pendiente:
                                </label>
                                <select id="valor_pendiente_select" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700">
                                    <option value="">Seleccione un valor...</option>
                                    @foreach($matriculas as $matricula)
                                        <option value="{{ $matricula->valor_pendiente }}"
                                                data-matricula-id="{{ $matricula->id }}"
                                                {{ $selectedMatricula && $selectedMatricula->id == $matricula->id ? 'selected' : '' }}>
                                            M{{ $matricula->id }} | {{ $matricula->curso->nombre }} | ${{ number_format($matricula->valor_pendiente, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Input de Pago (monto) --}}
                            <div>
                                <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    💲 Pago:
                                </label>
                                <input type="number" name="monto" id="monto" value="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700"
                                       step="0.01">
                            </div>

                            {{-- Fecha de pago --}}
                            <div>
                                <label for="fecha_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    📅 Fecha de Pago:
                                </label>
                                <input type="date" name="fecha_pago" id="fecha_pago"
                                       value="{{ now()->timezone('America/Guayaquil')->toDateString() }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700">
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    🔄 Estado:
                                </label>
                                <select name="estado" id="estado"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700"
                                        {{ auth()->user()->hasRole(1) ? '' : 'disabled' }}>
                                    <option value="Pendiente" {{ auth()->user()->hasRole(1) ? '' : 'selected' }}>Pendiente</option>
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>

                        </div>

                        {{-- Botón de guardar --}}
                        <button type="submit"
                                class="w-full mt-6 bg-gradient-to-r from-indigo-500 to-purple-500
                                       hover:from-indigo-600 hover:to-purple-600
                                       text-white font-bold py-2 px-4 rounded-md shadow-lg">
                            💾 Pagar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializa TomSelect sobre el combo de matrículas
        let selectMatricula = new TomSelect('#matricula_id', {
            create: false,
            allowEmptyOption: true,
            placeholder: 'Buscar matrícula...',
            sortField: {
                field: "text",
                direction: "asc"
            },
            onChange: function(value) {
                if (value) {
                    sincronizarValorPendiente(value);
                }
            }
        });

        // Inicializa TomSelect sobre el combo de valores pendientes
        let selectValorPendiente = new TomSelect('#valor_pendiente_select', {
            create: false,
            allowEmptyOption: true,
            placeholder: 'Seleccione un valor...',
            onChange: function(value) {
                if (value) {
                    const matriculaId = this.options[this.currentOption].dataset.matriculaId;
                    sincronizarMatricula(matriculaId);
                }
            }
        });

        function sincronizarValorPendiente(matriculaId) {
            const options = selectValorPendiente.options;
            for (let key in options) {
                const option = options[key];
                if (option.dataset && option.dataset.matriculaId === matriculaId) {
                    selectValorPendiente.setValue(option.value);
                    actualizarMonto(option.value);
                    break;
                }
            }
        }

        function sincronizarMatricula(matriculaId) {
            selectMatricula.setValue(matriculaId);
            const selectedOption = selectMatricula.options[matriculaId];
            if (selectedOption) {
                actualizarMonto(selectedOption.dataset.pendiente);
            }
        }

        function actualizarMonto(valor) {
            if (valor) {
                document.getElementById('monto').value = parseFloat(valor).toFixed(2);
                document.getElementById('monto').max = valor;
            } else {
                document.getElementById('monto').value = '0.00';
                document.getElementById('monto').max = '0';
            }
        }

        // Establecer valores iniciales si hay una matrícula seleccionada
        @if($selectedMatricula)
            selectMatricula.setValue('{{ $selectedMatricula->id }}');
            sincronizarValorPendiente('{{ $selectedMatricula->id }}');
        @endif

        // Limitar el valor si pasa del máximo
        document.getElementById('monto').addEventListener('input', function() {
            let max = parseFloat(this.max);
            let value = parseFloat(this.value);
            if (!isNaN(max) && value > max) {
                this.value = max.toFixed(2);
            }
        });

        // Mostrar/ocultar comprobante de pago según método
        document.getElementById('metodo_pago_id').addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let comprobanteContainer = document.getElementById('comprobante_pago_container');
            if (selectedOption.value === '1') { // Suponiendo que '1' es el ID del método que requiere comprobante
                comprobanteContainer.classList.remove('hidden');
            } else {
                comprobanteContainer.classList.add('hidden');
            }
        });

        // Dispara el evento 'change' para ajustar el comprobante si ya viene algo preseleccionado
        document.getElementById('metodo_pago_id').dispatchEvent(new Event('change'));

        // Actualizar valores cuando cambia la matrícula seleccionada
        document.getElementById('matricula_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const pendiente = selectedOption.getAttribute('data-pendiente');
            const valorPendienteSelect = document.getElementById('valor_pendiente_select');
            
            // Actualizar el valor pendiente seleccionado
            for(let option of valorPendienteSelect.options) {
                if(option.value === pendiente) {
                    option.selected = true;
                    actualizarMonto(pendiente);
                    break;
                }
            }
        });

        // Si hay una matrícula seleccionada inicialmente, actualizar el monto
        @if($selectedMatricula)
            actualizarMonto('{{ $selectedMatricula->valor_pendiente }}');
        @endif
    </script>
</x-app-layout>
