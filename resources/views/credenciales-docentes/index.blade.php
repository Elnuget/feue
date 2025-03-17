<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Redirigir a la URL con el filtro por defecto si no hay ningún filtro aplicado
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si estamos en la URL base sin parámetros
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('mes')) {
                const currentMonth = new Date().toISOString().slice(0, 7); // YYYY-MM
                const newUrl = `{{ route('credenciales-docentes.index') }}?mes=${currentMonth}`;
                // Solo redirigir si realmente estamos en la URL base
                if (window.location.href.split('?')[0] === '{{ route('credenciales-docentes.index') }}') {
                    window.location.href = newUrl;
                }
            }
        });
    </script>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Credenciales Docentes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Opciones Card -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <div x-data="{ openOptions: false }" class="space-y-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <button @click="openOptions = !openOptions" class="rounded-md bg-blue-500 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                                {{ __('Opciones de Fondo') }}
                            </button>
                            <button id="print-credentials" class="rounded-md bg-blue-500 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                                {{ __('Imprimir Credenciales') }}
                            </button>
                        </div>
                        
                        <div x-show="openOptions" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="w-full">
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('matriculas.uploadBackground') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="file" name="background" accept="image/*" class="rounded-md border-gray-300" />
                                    <button type="submit" class="rounded-md bg-blue-500 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                                        {{ __('Subir Fondo') }}
                                    </button>
                                </form>
                                <a href="{{ asset('storage/imagenes_de_fondo_permanentes/background.jpg') }}" download class="rounded-md bg-blue-500 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                                    {{ __('Descargar Fondo Actual') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filtro de mes -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-4">
                    <form action="{{ route('credenciales-docentes.index') }}" method="GET" class="flex items-center space-x-4">
                        <div class="flex-1">
                            <x-input-label for="mes" value="{{ __('Filtrar por Mes') }}" />
                            <input 
                                type="month" 
                                id="mes" 
                                name="mes" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                value="{{ request('mes', now()->format('Y-m')) }}"
                            >
                        </div>
                        <div class="flex items-end space-x-2">
                            <x-primary-button type="submit" class="mb-1">
                                {{ __('Filtrar') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        @if($mes)
                            {{ __('Mostrando datos de:') }} {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('F Y') }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Filtros y búsqueda -->
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <h3 class="text-lg font-semibold">Lista de Docentes</h3>
                            
                            <div class="flex items-center space-x-2">
                                <input type="text" id="search" placeholder="Buscar docente..." 
                                       class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de docentes -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        <input type="checkbox" id="select-all" class="h-4 w-4">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Docente
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Correo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Credencial
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Sesiones
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Asistencias
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        % Asistencia
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Última Sesión
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($docentes as $docente)
                                    <tr class="{{ ($docente->profile && $docente->profile->carnet == 'Entregado') ? 'bg-pastel-orange' : '' }}">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <input type="checkbox" class="select-row h-4 w-4" value="{{ $docente->id }}">
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center">
                                                @if($docente->profile && $docente->profile->photo)
                                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $docente->profile->photo) }}" alt="{{ $docente->name }}">
                                                @else
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                                                        <span class="text-lg font-medium text-indigo-800 dark:text-indigo-200">{{ substr($docente->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $docente->name }}</div>
                                                    @if($docente->profile)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $docente->profile->cedula ?? 'N/A' }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $docente->email }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ ($docente->profile && $docente->profile->carnet == 'Entregado') ? 'Entregado' : 'NO' }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $docente->total_sesiones }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $docente->total_asistencias }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @php
                                                $porcentaje = $docente->porcentaje_asistencia;
                                                $colorClass = 'bg-red-500';
                                                if ($porcentaje >= 90) {
                                                    $colorClass = 'bg-green-500';
                                                } elseif ($porcentaje >= 70) {
                                                    $colorClass = 'bg-yellow-500';
                                                } elseif ($porcentaje >= 50) {
                                                    $colorClass = 'bg-orange-500';
                                                }
                                            @endphp
                                            <div class="flex items-center">
                                                <div class="mr-2 h-2.5 w-full max-w-[100px] rounded-full bg-gray-200 dark:bg-gray-700">
                                                    <div class="{{ $colorClass }} h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $porcentaje }}%</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                @if($docente->ultima_sesion)
                                                    {{ $docente->ultima_sesion->fecha->format('d/m/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                            <a href="{{ route('credenciales-docentes.show', $docente->id) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                                                Ver detalles
                                            </a>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Búsqueda de docentes
            const searchInput = document.getElementById('search');
            const rows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function () {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Selección de todos los checkboxes
            const selectAllCheckbox = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.select-row');

            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                rowCheckboxes.forEach(checkbox => checkbox.checked = isChecked);
            });

            // Filtro de mes - submit automático al cambiar
            const mesInput = document.getElementById('mes');
            if (mesInput) {
                mesInput.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            }

            // Impresión de credenciales
            const printCredentialsButton = document.getElementById('print-credentials');
            
            printCredentialsButton.addEventListener('click', function() {
                const selectedIds = Array.from(rowCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedIds.length > 0) {
                    const url = `{{ route('credenciales-docentes.print') }}?ids=${selectedIds.join(',')}`;
                    window.open(url, '_blank');
                    
                    // Actualizar el estado de las credenciales mediante AJAX
                    fetch(`{{ route('credenciales-docentes.updateStatus') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            ids: selectedIds,
                            status: 'Entregado'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recargar la página después de actualizar
                            window.location.reload();
                        } else {
                            console.error('Error al actualizar el estado:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                        // Recargar la página de todos modos
                        window.location.reload();
                    });
                } else {
                    alert('Por favor, seleccione al menos un docente.');
                }
            });
        });
    </script>
    @endpush

    <style>
    .bg-pastel-orange {
        background-color: #FFCC99 !important;
    }
    </style>
</x-app-layout> 