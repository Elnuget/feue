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
                    <div class="mb-4">
                        <a href="{{ route('acuerdos-confidencialidad.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Nuevo Acuerdo') }}
                        </a>
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
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $acuerdo->user->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $acuerdo->curso->nombre }}
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
                                            <td class="px-6 py-4 text-sm font-medium space-x-3">
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
</x-app-layout> 