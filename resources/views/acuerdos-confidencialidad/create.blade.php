<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Acuerdo de Confidencialidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('acuerdos-confidencialidad.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if(auth()->user()->hasRole('admin'))
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Usuario') }}
                            </label>
                            <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm select2">
                                <option value="">{{ __('Seleccione un usuario') }}</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        @else
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        @endif

                        <div class="mb-4">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Curso') }}
                            </label>
                            <select name="curso_id" id="curso_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm select2">
                                <option value="">{{ __('Seleccione un curso') }}</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }} | {{ $curso->horario }}
                                    </option>
                                @endforeach
                            </select>
                            @error('curso_id')
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
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('acuerdos-confidencialidad.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Crear') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border-color: rgb(209 213 219);
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: rgb(17 24 39);
            border-color: rgb(55 65 81);
            color: rgb(209 213 219);
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(209 213 219);
        }
        .dark .select2-dropdown {
            background-color: rgb(17 24 39);
            border-color: rgb(55 65 81);
        }
        .dark .select2-container--default .select2-results__option {
            color: rgb(209 213 219);
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(55 65 81);
        }
        .dark .select2-search__field {
            background-color: rgb(17 24 39);
            color: rgb(209 213 219);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || "{{ __('Buscar...') }}";
                },
                allowClear: true,
                width: '100%'
            });
            
            // Configurar placeholders específicos
            $('#user_id').data('placeholder', "{{ __('Buscar usuario...') }}");
            $('#curso_id').data('placeholder', "{{ __('Buscar curso...') }}");
        });
    </script>
    @endpush
</x-app-layout> 