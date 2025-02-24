<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Sesiones de Docentes') }}
            </h2>
            <button @click="$dispatch('open-modal', 'crear-sesion')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Nueva Sesión
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filtro de mes -->
            <div class="mb-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <form action="{{ route('sesiones-docentes.index') }}" method="GET" class="flex items-center space-x-4">
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
                        <a href="{{ route('sesiones-docentes.export') }}?mes={{ request('mes', now()->format('Y-m')) }}" class="mb-1 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-file-excel mr-2"></i>{{ __('Exportar a Excel') }}
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Docente</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Curso</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horas</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aula</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Materia</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/6">Tema Impartido</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/6">Observaciones</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($sesiones as $sesion)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm">{{ $sesion->docente->name }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $sesion->curso->nombre }}
                                            <br>
                                            <span class="text-xs text-gray-500">
                                                {{ $sesion->curso->tipo_curso }} | {{ $sesion->curso->horario }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">{{ $sesion->fecha->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $horaInicio = \Carbon\Carbon::createFromFormat('H:i', $sesion->hora_inicio);
                                                $horaFin = \Carbon\Carbon::createFromFormat('H:i', $sesion->hora_fin);
                                                $diferencia = $horaFin->diffInHours($horaInicio) . ':' . str_pad($horaFin->diffInMinutes($horaInicio) % 60, 2, '0', STR_PAD_LEFT);
                                            @endphp
                                            {{ $diferencia }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">{{ $sesion->aula ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $sesion->materia ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $sesion->tema_impartido ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $sesion->observaciones ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <button @click="$dispatch('open-modal', 'editar-sesion-{{ $sesion->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button @click="$dispatch('open-modal', 'eliminar-sesion-{{ $sesion->id }}')" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

    <!-- Modal Crear -->
    <x-modal name="crear-sesion" focusable>
        <form method="POST" action="{{ route('sesiones-docentes.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Crear Nueva Sesión') }}
            </h2>

            @if(!Auth::user()->hasRole('Docente'))
            <div class="mt-6">
                <x-input-label for="user_id" value="{{ __('Docente') }}" />
                <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mt-6">
                <x-input-label for="curso_id" value="{{ __('Curso') }}" />
                <select name="curso_id" id="curso_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">
                            {{ $curso->nombre }} - {{ $curso->tipoCurso->nombre ?? 'Sin tipo' }} | {{ $curso->horario }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6">
                <x-input-label for="fecha" value="{{ __('Fecha') }}" />
                <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" value="{{ now()->format('Y-m-d') }}" required />
            </div>

            <div class="mt-6">
                <x-input-label for="hora_inicio" value="{{ __('Hora de Inicio') }}" />
                <x-text-input id="hora_inicio" name="hora_inicio" type="time" class="mt-1 block w-full" value="{{ now()->format('H:i') }}" required />
            </div>

            <div class="mt-6">
                <x-input-label for="hora_fin" value="{{ __('Hora de Fin') }}" />
                <x-text-input id="hora_fin" name="hora_fin" type="time" class="mt-1 block w-full" value="{{ now()->addHour()->format('H:i') }}" required />
            </div>

            <div class="mt-6">
                <x-input-label for="aula" value="{{ __('Aula (Opcional)') }}" />
                <x-text-input id="aula" name="aula" type="text" class="mt-1 block w-full" />
            </div>

            <div class="mt-6">
                <x-input-label for="materia" value="{{ __('Materia') }}" />
                <x-text-input id="materia" name="materia" type="text" class="mt-1 block w-full" />
            </div>

            <div class="mt-6">
                <x-input-label for="tema_impartido" value="{{ __('Tema Impartido') }}" />
                <textarea id="tema_impartido" name="tema_impartido" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
            </div>

            <div class="mt-6">
                <x-input-label for="observaciones" value="{{ __('Observaciones') }}" />
                <textarea id="observaciones" name="observaciones" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Guardar') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Modales de Edición -->
    @foreach($sesiones as $sesion)
        <x-modal name="editar-sesion-{{ $sesion->id }}" focusable>
            <form method="POST" action="{{ route('sesiones-docentes.update', $sesion) }}" class="p-6">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Editar Sesión') }}
                </h2>

                @if(!Auth::user()->hasRole('Docente'))
                <div class="mt-6">
                    <x-input-label for="user_id" value="{{ __('Docente') }}" />
                    <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ $sesion->user_id == $docente->id ? 'selected' : '' }}>
                                {{ $docente->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="mt-6">
                    <x-input-label for="curso_id" value="{{ __('Curso') }}" />
                    <select name="curso_id" id="curso_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ $sesion->curso_id == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nombre }} - {{ $curso->tipoCurso->nombre ?? 'Sin tipo' }} | {{ $curso->horario }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6">
                    <x-input-label for="fecha" value="{{ __('Fecha') }}" />
                    <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" value="{{ $sesion->fecha->format('Y-m-d') }}" required />
                </div>

                <div class="mt-6">
                    <x-input-label for="hora_inicio" value="{{ __('Hora de Inicio') }}" />
                    <x-text-input id="hora_inicio" name="hora_inicio" type="time" class="mt-1 block w-full" value="{{ $sesion->hora_inicio }}" required />
                </div>

                <div class="mt-6">
                    <x-input-label for="hora_fin" value="{{ __('Hora de Fin') }}" />
                    <x-text-input id="hora_fin" name="hora_fin" type="time" class="mt-1 block w-full" value="{{ $sesion->hora_fin }}" required />
                </div>

                <div class="mt-6">
                    <x-input-label for="aula" value="{{ __('Aula (Opcional)') }}" />
                    <x-text-input id="aula" name="aula" type="text" class="mt-1 block w-full" value="{{ $sesion->aula }}" />
                </div>

                <div class="mt-6">
                    <x-input-label for="materia" value="{{ __('Materia') }}" />
                    <x-text-input id="materia" name="materia" type="text" class="mt-1 block w-full" value="{{ $sesion->materia }}" />
                </div>

                <div class="mt-6">
                    <x-input-label for="tema_impartido" value="{{ __('Tema Impartido') }}" />
                    <textarea id="tema_impartido" name="tema_impartido" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $sesion->tema_impartido }}</textarea>
                </div>

                <div class="mt-6">
                    <x-input-label for="observaciones" value="{{ __('Observaciones') }}" />
                    <textarea id="observaciones" name="observaciones" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $sesion->observaciones }}</textarea>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="ml-3">
                        {{ __('Actualizar') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal de Eliminación -->
        <x-modal name="eliminar-sesion-{{ $sesion->id }}" focusable>
            <form method="POST" action="{{ route('sesiones-docentes.destroy', $sesion) }}" class="p-6">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('¿Estás seguro de que quieres eliminar esta sesión?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Esta acción no se puede deshacer.') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __('Eliminar Sesión') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach

    @push('scripts')
    <script>
        // Aquí puedes agregar cualquier JavaScript adicional que necesites
    </script>
    @endpush
</x-app-layout>