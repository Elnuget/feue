<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Acuerdos de Confidencialidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex-1 mr-4">
                            @if(auth()->user()->hasRole('admin'))
                            <label for="filter_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Filtrar por Usuario') }}
                            </label>
                            <select name="filter_user_id" id="filter_user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm select2">
                                <option value="">{{ __('Todos los usuarios') }}</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('acuerdos-confidencialidad.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Nuevo Acuerdo') }}
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @php
                        $acuerdosToShow = auth()->user()->hasRole(1) ? $acuerdos : $acuerdos->where('user_id', auth()->id());
                    @endphp

                    @if($acuerdosToShow->isEmpty())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ __('No hay acuerdos de confidencialidad registrados.') }}</span>
                        </div>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            {{ __('Usuario') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            {{ __('Curso') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            {{ __('Estado') }}
                                        </th>
                                        @if(auth()->user()->hasRole(1))
                                        <th scope="col" class="px-6 py-3">
                                            {{ __('Acciones') }}
                                        </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acuerdosToShow as $acuerdo)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-user-id="{{ $acuerdo->user_id }}">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $acuerdo->user->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($acuerdo->curso)
                                                    {{ $acuerdo->curso->nombre }}
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Docente') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $acuerdo->estado === 'Entregado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $acuerdo->estado }}
                                                </span>
                                            </td>
                                            @if(auth()->user()->hasRole(1))
                                            <td class="px-6 py-4 text-sm font-medium space-x-3">
                                                @if($acuerdo->estado !== 'Entregado')
                                                <form action="{{ route('acuerdos-confidencialidad.aprobar', $acuerdo->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                        title="{{ __('Aprobar acuerdo') }}">
                                                        <i class="fas fa-check"></i> {{ __('Aprobar') }}
                                                    </button>
                                                </form>
                                                @endif
                                                @if($acuerdo->acuerdo)
                                                <a href="{{ Storage::url($acuerdo->acuerdo) }}" 
                                                   target="_blank"
                                                   class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300"
                                                   title="{{ __('Ver PDF') }}">
                                                    <i class="fas fa-file-pdf"></i> {{ __('PDF') }}
                                                </a>
                                                @endif
                                                <a href="{{ route('acuerdos-confidencialidad.show', $acuerdo->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                   title="{{ __('Ver detalles') }}">
                                                    <i class="fas fa-eye"></i> {{ __('Ver') }}
                                                </a>
                                                <a href="{{ route('acuerdos-confidencialidad.edit', $acuerdo->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                   title="{{ __('Editar acuerdo') }}">
                                                    <i class="fas fa-edit"></i> {{ __('Editar') }}
                                                </a>
                                                <form action="{{ route('acuerdos-confidencialidad.destroy', $acuerdo->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                            onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar este acuerdo? Esta acción no se puede deshacer.') }}')"
                                                            title="{{ __('Eliminar acuerdo') }}">
                                                        <i class="fas fa-trash-alt"></i> {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                            </td>
                                            @else
                                            <td class="px-6 py-4 text-sm font-medium">
                                                @if($acuerdo->acuerdo)
                                                <a href="{{ Storage::url($acuerdo->acuerdo) }}" 
                                                   target="_blank"
                                                   class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300"
                                                   title="{{ __('Ver PDF') }}">
                                                    <i class="fas fa-file-pdf"></i> {{ __('PDF') }}
                                                </a>
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
                placeholder: "{{ __('Seleccionar usuario...') }}",
                allowClear: true,
                width: '100%'
            });

            $('#filter_user_id').on('change', function() {
                const selectedUserId = $(this).val();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const userCell = row.querySelector('td:first-child');
                    const userId = userCell.closest('tr').getAttribute('data-user-id');
                    
                    if (!selectedUserId || userId === selectedUserId) {
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