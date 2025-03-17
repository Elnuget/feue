<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Credenciales Docentes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                                        Docente
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Correo
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
                                    <tr>
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
        });
    </script>
    @endpush
</x-app-layout> 