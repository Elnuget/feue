<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listas de Matriculados') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('matriculas.listas') }}" id="cursoForm" class="mb-6">
                        <div class="mb-4">
                            <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Seleccione un curso') }}
                            </label>
                            <select 
                                name="curso_id" 
                                id="curso_id" 
                                class="block w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="document.getElementById('cursoForm').submit();"
                            >
                                <option value="" disabled selected>{{ __('Elegir curso') }}</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ $cursoId == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    @if($matriculas->isNotEmpty())
                        <div class="flex justify-end mb-4 space-x-2">
                            <a href="{{ route('matriculas.exportPdf', ['curso_id' => $cursoId]) }}" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Exportar PDF') }}
                            </a>
                            <a href="{{ route('matriculas.exportExcel', ['curso_id' => $cursoId]) }}" class="btn btn-primary bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Exportar Excel') }}
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-700 rounded">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('#') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Nombre del Matriculado') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Estado') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($matriculas as $index => $matricula)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ $matricula->usuario->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                @if($matricula->estado === 'aprobado')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Aprobado
                                                    </span>
                                                @elseif($matricula->estado === 'rechazado')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Rechazado
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                {{ __('No hay matriculados en este curso.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
