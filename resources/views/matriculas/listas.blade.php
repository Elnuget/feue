<x-app-layout>
    @section('page_title', 'Listas')
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Opciones Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg mb-6">
                <div class="p-6">
                    <div x-data="{ openOptions: false }" class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <button @click="openOptions = !openOptions" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Opciones de Fondo') }}
                            </button>
                            @if($matriculas->isNotEmpty())
                                <button id="print-credentials" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Imprimir Credenciales') }}
                                </button>
                                <a href="{{ route('matriculas.exportPdf', ['curso_id' => $cursoId]) }}" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Exportar PDF') }}
                                </a>
                                <a href="{{ route('matriculas.exportExcel', ['curso_id' => $cursoId]) }}" class="btn btn-primary bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Exportar Excel') }}
                                </a>
                            @endif
                        </div>
                        <div x-show="openOptions" class="w-full mt-4">
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('matriculas.uploadBackground') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="file" name="background" accept="image/*" class="rounded-md border-gray-300" />
                                    <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Subir Fondo') }}
                                    </button>
                                </form>
                                <a href="{{ asset('storage/imagenes_de_fondo_permanentes/background.jpg') }}" download class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Descargar Fondo Actual') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('matriculas.listas') }}" id="cursoForm" class="mb-6">
                        <div class="flex gap-4 mb-4">
                            <select id="tipo_curso" name="tipo_curso" class="w-1/3 rounded-md border-gray-300">
                                <option value="">Seleccione un tipo de curso</option>
                                @foreach($tiposCursos as $tipoCurso)
                                    <option value="{{ $tipoCurso->id }}" {{ $tipoCursoId == $tipoCurso->id ? 'selected' : '' }}>
                                        {{ $tipoCurso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="curso_id" name="curso_id" class="w-1/3 rounded-md border-gray-300">
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ $cursoId == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }} ({{ $curso->horario }})
                                    </option>
                                @endforeach
                            </select>
                            <select id="per_page" name="per_page" class="w-1/3 rounded-md border-gray-300">
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 registros por página</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 registros por página</option>
                                <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200 registros por página</option>
                            </select>
                        </div>
                    </form>
                    @if($matriculas->isNotEmpty())
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
                                            {{ __('Valor Pendiente en Moneda') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Carnet') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            {{ __('Celular') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($matriculas as $index => $matricula)
                                        <tr class="{{ ($matricula->estado_matricula == 'Entregado' || ($matricula->usuario->profile && $matricula->usuario->profile->carnet == 'Entregado')) ? 'bg-pastel-orange' : ($loop->even ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800') }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                <input type="checkbox" class="select-row" value="{{ $matricula->id }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ ($matriculas->currentPage() - 1) * $matriculas->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                @if($matricula->usuario->profile && $matricula->usuario->profile->photo)
                                                    <img loading="lazy" 
                                                         src="{{ asset('storage/' . $matricula->usuario->profile->photo) }}" 
                                                         alt="Profile Photo" 
                                                         class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div class="w-10 h-10 flex items-center justify-center bg-gray-200 rounded-full text-gray-500">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ ($matricula->estado_matricula == 'Entregado' || ($matricula->usuario->profile && $matricula->usuario->profile->carnet == 'Entregado')) ? 'text-blue-500' : 'text-gray-900 dark:text-gray-100' }} border-b border-gray-300 dark:border-gray-700">
                                                {{ $matricula->usuario->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                <span class="{{ $matricula->valor_pendiente == 0 ? 'text-green-500' : 'text-red-500' }}">
                                                    ${{ number_format($matricula->valor_pendiente, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ ($matricula->usuario->profile && $matricula->usuario->profile->carnet == 'Entregado') ? 'Entregado' : 'NO' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                @if($matricula->usuario->profile && $matricula->usuario->profile->phone)
                                                    {{ $matricula->usuario->profile->phone }}
                                                    @php
                                                        $phone = $matricula->usuario->profile->phone;
                                                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                                        if (!str_starts_with($cleanPhone, '593')) {
                                                            $cleanPhone = ltrim($cleanPhone, '0');
                                                            $cleanPhone = '593' . $cleanPhone;
                                                        }
                                                    @endphp
                                                    <a href="https://wa.me/{{ $cleanPhone }}" 
                                                       target="_blank"
                                                       class="inline-block ml-2 text-green-500 hover:text-green-600 transition-colors duration-200">
                                                        <i class="fab fa-whatsapp text-lg"></i>
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700">
                                                {{ $matricula->estado_matricula }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $matriculas->withQueryString()->links() }}
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

<style>
.bg-pastel-orange {
    background-color: #FFCC99 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Usar constantes para elementos que se usan múltiples veces
    const form = document.getElementById('cursoForm');
    const tipoCursoSelect = document.getElementById('tipo_curso');
    const cursoSelect = document.getElementById('curso_id');
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('tbody .select-row');
    const printCredentialsButton = document.getElementById('print-credentials');

    // Debounce function para optimizar eventos
    const debounce = (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };

    // Optimizar el manejo de cursos con memoización
    const cursosPorTipo = {};
    const cursos = @json($cursos);

    const actualizarCursos = (tipoCursoId) => {
        if (!cursosPorTipo[tipoCursoId]) {
            cursosPorTipo[tipoCursoId] = cursos.filter(curso => curso.tipo_curso_id == tipoCursoId);
        }
        return cursosPorTipo[tipoCursoId];
    };

    // Event Listeners optimizados
    tipoCursoSelect.addEventListener('change', debounce(function() {
        if (tipoCursoSelect.value) {
            cursoSelect.disabled = false;
            const cursosFiltrados = actualizarCursos(tipoCursoSelect.value);
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>' +
                cursosFiltrados.map(curso => 
                    `<option value="${curso.id}">${curso.nombre} (${curso.horario})</option>`
                ).join('');
        } else {
            cursoSelect.disabled = true;
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
        }
        form.submit();
    }, 300));

    cursoSelect.addEventListener('change', debounce(function() {
        form.submit();
    }, 300));

    // Agregar evento para el selector de registros por página
    document.getElementById('per_page')?.addEventListener('change', debounce(function() {
        form.submit();
    }, 300));

    // Optimizar selección de checkboxes
    selectAllCheckbox?.addEventListener('change', function() {
        const isChecked = this.checked;
        rowCheckboxes.forEach(checkbox => checkbox.checked = isChecked);
    });

    // Optimizar impresión de credenciales
    printCredentialsButton?.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .reduce((acc, checkbox) => {
                if (checkbox.checked) acc.push(checkbox.value);
                return acc;
            }, []);

        if (selectedIds.length > 0) {
            const url = `{{ route('matriculas.printCredentials') }}?ids=${selectedIds.join(',')}`;
            window.open(url, '_blank');
            
            // Recargar usando fetch para evitar recarga completa
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    document.querySelector('table').innerHTML = newDoc.querySelector('table').innerHTML;
                })
                .catch(error => console.error('Error al recargar:', error));
        } else {
            alert('Por favor, seleccione al menos una fila.');
        }
    });
});
</script>

