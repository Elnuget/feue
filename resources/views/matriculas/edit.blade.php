<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Matricula') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('matriculas.update', $matricula) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                            <input type="text" name="usuario_id" id="usuario_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ $matricula->usuario_id }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                            <input type="text" name="curso_id" id="curso_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ $matricula->curso_id }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Matricula</label>
                            <input type="date" name="fecha_matricula" id="fecha_matricula" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ $matricula->fecha_matricula }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="monto_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Total</label>
                            <input type="number" name="monto_total" id="monto_total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ $matricula->monto_total }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="valor_pendiente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Pendiente</label>
                            <input type="number" name="valor_pendiente" id="valor_pendiente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ $matricula->valor_pendiente }}">
                        </div>
                        <div class="mb-4">
                            <label for="estado_matricula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado de Matricula</label>
                            <select name="estado_matricula" id="estado_matricula" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                                <option value="Pendiente" {{ $matricula->estado_matricula == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Aprobada" {{ $matricula->estado_matricula == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                                <option value="Completada" {{ $matricula->estado_matricula == 'Completada' ? 'selected' : '' }}>Completada</option>
                                <option value="Rechazada" {{ $matricula->estado_matricula == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>