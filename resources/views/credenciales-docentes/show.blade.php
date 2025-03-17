<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Detalles del Docente') }}: {{ $docente->name }}
            </h2>
            <a href="{{ route('credenciales-docentes.index') }}" class="rounded-md bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600">
                Volver a la lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Información del perfil -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="border-b border-gray-200 bg-white px-4 py-5 dark:border-gray-700 dark:bg-gray-800 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Información personal</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col md:flex-row">
                        <div class="mb-4 flex-shrink-0 md:mb-0 md:mr-6">
                            @if($docente->profile && $docente->profile->photo)
                                <img class="h-32 w-32 rounded-full" src="{{ asset('storage/' . $docente->profile->photo) }}" alt="{{ $docente->name }}">
                            @else
                                <div class="flex h-32 w-32 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                                    <span class="text-3xl font-medium text-indigo-800 dark:text-indigo-200">{{ substr($docente->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $docente->name }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Correo</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $docente->email }}</p>
                            </div>
                            @if($docente->profile)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cédula</h4>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $docente->profile->cedula ?? 'No registrada' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</h4>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $docente->profile->telefono ?? 'No registrado' }}</p>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sesiones impartidas</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ count($sesiones) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Asistencias registradas</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ count($asistencias) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs para sesiones y asistencias -->
            <div x-data="{ activeTab: 'sesiones' }" class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button 
                            @click="activeTab = 'sesiones'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300': activeTab === 'sesiones', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'sesiones' }"
                            class="border-b-2 px-1 py-4 text-sm font-medium"
                        >
                            Sesiones
                        </button>
                        <button 
                            @click="activeTab = 'asistencias'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300': activeTab === 'asistencias', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'asistencias' }"
                            class="border-b-2 px-1 py-4 text-sm font-medium"
                        >
                            Asistencias
                        </button>
                    </nav>
                </div>
                
                <!-- Contenido de las pestañas -->
                <div class="p-6">
                    <!-- Contenido de Sesiones -->
                    <div x-show="activeTab === 'sesiones'">
                        <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100">Sesiones Impartidas</h3>
                        
                        @if(count($sesiones) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Fecha</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Horario</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Curso</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aula</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tema</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                        @foreach($sesiones as $sesion)
                                            <tr>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $sesion->fecha->format('d/m/Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $sesion->curso->nombre }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $sesion->aula }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $sesion->tema_impartido }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No hay sesiones registradas para este docente.</p>
                        @endif
                    </div>
                    
                    <!-- Contenido de Asistencias -->
                    <div x-show="activeTab === 'asistencias'" style="display: none;">
                        <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100">Registro de Asistencias</h3>
                        
                        @if(count($asistencias) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Fecha</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Hora Entrada</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Estado</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Sesión</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                        @foreach($asistencias as $asistencia)
                                            <tr>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $asistencia->fecha->format('d/m/Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $asistencia->hora_entrada->format('H:i') }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                    @if($asistencia->estado == 'Puntual')
                                                        <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            {{ $asistencia->estado }}
                                                        </span>
                                                    @elseif($asistencia->estado == 'Tardanza')
                                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                            {{ $asistencia->estado }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                            {{ $asistencia->estado }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    @if($asistencia->sesion)
                                                        {{ $asistencia->sesion->curso->nombre }} - {{ $asistencia->sesion->fecha->format('d/m/Y') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $asistencia->observaciones ?? 'Ninguna' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No hay asistencias registradas para este docente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 