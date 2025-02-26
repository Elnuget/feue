<x-app-layout>
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Asistencia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('asistencias.update', $asistencia) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                        <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $asistencia->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="fecha_hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_hora" id="fecha_hora" 
                               value="{{ date('Y-m-d\TH:i', strtotime($asistencia->fecha_hora)) }}"
                               required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label for="hora_entrada" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora de Entrada</label>
                        <input type="time" 
                               name="hora_entrada" 
                               id="hora_entrada"
                               value="{{ $asistencia->hora_entrada ? date('H:i', strtotime($asistencia->hora_entrada)) : '' }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label for="hora_salida" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora de Salida</label>
                        <input type="time" 
                               name="hora_salida" 
                               id="hora_salida"
                               value="{{ $asistencia->hora_salida ? date('H:i', strtotime($asistencia->hora_salida)) : '' }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                        <select name="estado" 
                                id="estado" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            <option value="presente" {{ $asistencia->estado == 'presente' ? 'selected' : '' }}>Presente</option>
                            <option value="ausente" {{ $asistencia->estado == 'ausente' ? 'selected' : '' }}>Ausente</option>
                            <option value="tardanza" {{ $asistencia->estado == 'tardanza' ? 'selected' : '' }}>Tardanza</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>