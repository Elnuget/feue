<x-app-layout>
    @section('page_title', 'Listas')
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('matriculas.listas') }}" id="cursoForm" class="mb-6">
                        <div class="flex gap-4 mb-4">
                            <select id="tipo_curso" name="tipo_curso" class="w-1/2 rounded-md border-gray-300">
                                <option value="">Seleccione un tipo de curso</option>
                                @foreach($tiposCursos as $tipoCurso)
                                    <option value="{{ $tipoCurso->id }}" {{ $tipoCursoId == $tipoCurso->id ? 'selected' : '' }}>
                                        {{ $tipoCurso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="curso_id" name="curso_id" class="w-1/2 rounded-md border-gray-300" {{ $cursos->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ $cursoId == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }} ({{ $curso->horario }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    @if($matriculas->isNotEmpty())
                        <div class="flex justify-end mb-4 space-x-2">
                            <button id="print-credentials" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Imprimir Credenciales') }}
                            </button>
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
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('#') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Photo') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Nombre del Matriculado') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Valor Pendiente') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Valor Pendiente en Moneda') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($matriculas as $index => $matricula)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                <input type="checkbox" class="select-row" value="{{ $matricula->id }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                @if($matricula->usuario->profile && $matricula->usuario->profile->photo)
                                                    <img src="{{ asset('storage/' . $matricula->usuario->profile->photo) }}" alt="Profile Photo" class="w-10 h-10 rounded-full">
                                                @else
                                                    <span class="text-gray-500">No Photo</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ $matricula->usuario->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ $matricula->valor_pendiente > 0 ? 'Sí' : 'No' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                ${{ number_format($matricula->valor_pendiente, 2) }}
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoCursoSelect = document.getElementById('tipo_curso');
    const cursoSelect = document.getElementById('curso_id');
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.select-row');
    const printCredentialsButton = document.getElementById('print-credentials');

    const cursos = @json($cursos);

    tipoCursoSelect.addEventListener('change', function() {
        if (tipoCursoSelect.value) {
            cursoSelect.disabled = false;
            const cursosFiltrados = cursos.filter(curso => curso.tipo_curso_id == tipoCursoSelect.value);
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
            cursosFiltrados.forEach(curso => {
                cursoSelect.innerHTML += `<option value="${curso.id}">${curso.nombre} (${curso.horario})</option>`;
            });
        } else {
            cursoSelect.disabled = true;
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
        }
        document.getElementById('cursoForm').submit();
    });

    cursoSelect.addEventListener('change', function() {
        document.getElementById('cursoForm').submit();
    });

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    printCredentialsButton.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedIds.length > 0) {
            const url = "{{ route('matriculas.printCredentials') }}?ids=" + selectedIds.join(',');
            window.open(url, '_blank');
        } else {
            alert('Por favor, seleccione al menos una fila.');
        }
    });
});
</script>
