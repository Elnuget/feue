<x-app-layout>
    {{-- Estilos y scripts de TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-gray-900 dark:text-gray-100">
                    {{-- Encabezado del formulario --}}
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold">Editar Pago</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Modifique los campos necesarios para actualizar el pago.</p>
                    </div>

                    {{-- Mostrar errores si los hay --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                            <div class="font-medium text-red-700 dark:text-red-300">
                                <i class="fa fa-exclamation-triangle"></i> {{ __('Whoops! Algo salio mal.') }}
                            </div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }} <i class="fa fa-frown-open"></i></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pagos.update', $pago) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Seleccionar matrícula --}}
                            <div>
                                <label for="matricula_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-graduation-cap"></i> Matricula:
                                </label>
                                <select name="matricula_id" id="matricula_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 focus:ring-indigo-500 transition"
                                        autocomplete="off"
                                        {{ auth()->user()->hasRole(1) ? '' : 'disabled' }}>
                                    @foreach($matriculas as $matricula)
                                        <option value="{{ $matricula->id }}"
                                                data-pendiente="{{ number_format($matricula->valor_pendiente, 2) }}"
                                                {{ $pago->matricula_id == $matricula->id ? 'selected' : '' }}>
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
                                    <i class="fa fa-credit-card"></i> Metodo de Pago:
                                </label>
                                <select name="metodo_pago_id" id="metodo_pago_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 focus:ring-indigo-500 transition">
                                    @foreach($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}" {{ $pago->metodo_pago_id == $metodo->id ? 'selected' : '' }}>
                                            {{ $metodo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Comprobante de pago --}}
                            <div class="col-span-1 md:col-span-2" id="comprobante_pago_container">
                                <label for="comprobante_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-paperclip"></i> Comprobante de Pago:
                                </label>
                                @if($pago->comprobante_pago)
                                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <p class="text-sm mb-2">Comprobante actual:</p>
                                        @php
                                            $extension = pathinfo($pago->comprobante_pago, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
                                        @endphp
                                        
                                        @if($isPdf)
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                                <a href="{{ Storage::url($pago->comprobante_pago) }}" 
                                                target="_blank"
                                                class="text-blue-500 hover:text-blue-700 underline">
                                                    Ver PDF
                                                </a>
                                            </div>
                                        @else
                                            <img src="{{ Storage::url($pago->comprobante_pago) }}" 
                                                alt="Comprobante actual" 
                                                class="max-w-xs rounded-lg shadow-md">
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    <input type="file" 
                                        name="comprobante_pago" 
                                        id="comprobante_pago"
                                        accept=".png, .jpg, .jpeg, .pdf"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 focus:ring-indigo-500 transition"
                                        onchange="previewFile(this)">
                                </div>
                                
                                {{-- Preview container --}}
                                <div id="preview-container" class="mt-4 hidden">
                                    <p class="text-sm mb-2">Vista previa:</p>
                                    <div id="preview-content"></div>
                                </div>
                            </div>

                            {{-- Monto --}}
                            <div>
                                <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-dollar-sign"></i> Pago:
                                </label>
                                <input type="number" name="monto" id="monto" value="{{ $pago->monto }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 focus:ring-indigo-500 transition"
                                       step="0.01"
                                       {{ auth()->user()->hasRole(1) ? '' : 'disabled' }}>
                            </div>

                            {{-- Fecha de pago --}}
                            <div>
                                <label for="fecha_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-calendar"></i> Fecha de Pago:
                                </label>
                                <input type="date" name="fecha_pago" id="fecha_pago" value="{{ $pago->fecha_pago }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 focus:ring-indigo-500 transition"
                                       {{ auth()->user()->hasRole(1) ? '' : 'disabled' }}>
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-sync"></i> Estado:
                                </label>
                                <select name="estado" id="estado"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 focus:ring-indigo-500 transition"
                                        {{ auth()->user()->hasRole(1) ? '' : 'disabled' }}>
                                    <option value="Pendiente" {{ $pago->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Aprobado" {{ $pago->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Rechazado" {{ $pago->estado == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </div>
                        </div>

                        {{-- Botón de actualizar --}}
                        <button type="submit"
                                class="w-full mt-6 bg-gradient-to-r from-indigo-500 to-purple-500
                                       hover:from-indigo-600 hover:to-purple-600
                                       text-white font-bold py-2 px-4 rounded-md shadow-lg focus:ring-4 focus:ring-indigo-500 transition">
                            <i class="fa fa-save"></i> Actualizar Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializa TomSelect sobre el combo de matrículas
        new TomSelect('#matricula_id', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        function previewFile(input) {
            const previewContainer = document.getElementById('preview-container');
            const previewContent = document.getElementById('preview-content');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const file = input.files[0];
                
                reader.onload = function(e) {
                    previewContainer.classList.remove('hidden');
                    
                    if (file.type === 'application/pdf') {
                        previewContent.innerHTML = `
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                <span class="text-sm">PDF seleccionado: ${file.name}</span>
                            </div>
                        `;
                    } else {
                        previewContent.innerHTML = `
                            <img src="${e.target.result}" 
                                 alt="Vista previa" 
                                 class="max-w-xs rounded-lg shadow-md">
                        `;
                    }
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
                previewContent.innerHTML = '';
            }
        }
    </script>
</x-app-layout>