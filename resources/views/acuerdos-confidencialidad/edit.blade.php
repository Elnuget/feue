<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Acuerdo de Confidencialidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('acuerdos-confidencialidad.update', $acuerdoConfidencialidad) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Curso') }}
                            </label>
                            <select name="curso_id" id="curso_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="">{{ __('Seleccione un curso') }}</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ old('curso_id', $acuerdoConfidencialidad->curso_id) == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('curso_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Estado') }}
                            </label>
                            <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="Pendiente" {{ old('estado', $acuerdoConfidencialidad->estado) == 'Pendiente' ? 'selected' : '' }}>{{ __('Pendiente') }}</option>
                                <option value="Entregado" {{ old('estado', $acuerdoConfidencialidad->estado) == 'Entregado' ? 'selected' : '' }}>{{ __('Entregado') }}</option>
                            </select>
                            @error('estado')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="acuerdo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Archivo PDF') }}
                            </label>
                            <input type="file" name="acuerdo" id="acuerdo" accept=".pdf" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                dark:file:bg-blue-900 dark:file:text-blue-300
                                hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            @error('acuerdo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @if($acuerdoConfidencialidad->acuerdo)
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Archivo actual:') }} {{ basename($acuerdoConfidencialidad->acuerdo) }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('acuerdos-confidencialidad.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Actualizar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 