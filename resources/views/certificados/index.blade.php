<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Certificados') }}
            </h2>
            <a href="{{ route('certificados.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Nuevo Certificado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <form method="GET" action="{{ route('certificados.index') }}" id="cursoForm" class="mb-6">
                            <div class="flex gap-4 mb-4">
                                <select id="tipo_curso" name="tipo_curso" class="w-1/3 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Seleccione un tipo de curso</option>
                                    @foreach($tiposCursos as $tipoCurso)
                                        <option value="{{ $tipoCurso->id }}" {{ request('tipo_curso') == $tipoCurso->id ? 'selected' : '' }}>
                                            {{ $tipoCurso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <select id="curso_id" name="curso_id" class="w-1/3 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" {{ !request('tipo_curso') ? 'disabled' : '' }}>
                                    <option value="">Seleccione un curso</option>
                                    @foreach($cursosMatriculados->where('tipo_curso_id', request('tipo_curso')) as $curso)
                                        <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }} ({{ $curso->horario }})
                                        </option>
                                    @endforeach
                                </select>
                                <select id="per_page" name="per_page" class="w-1/3 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    <option value="10" {{ request('per_page', 50) == 10 ? 'selected' : '' }}>10 registros por página</option>
                                    <option value="25" {{ request('per_page', 50) == 25 ? 'selected' : '' }}>25 registros por página</option>
                                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 registros por página</option>
                                    <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100 registros por página</option>
                                </select>
                            </div>
                        </form>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($certificados as $index => $certificado)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ ($certificados->currentPage() - 1) * $certificados->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $certificado->usuario->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $certificado->nombre_curso }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('certificados.show', $certificado) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">Ver</a>
                                                <a href="{{ route('certificados.edit', $certificado) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600">Editar</a>
                                                <form action="{{ route('certificados.destroy', $certificado) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600" onclick="return confirm('¿Estás seguro de que deseas eliminar este certificado?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $certificados->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cursoForm');
    const tipoCursoSelect = document.getElementById('tipo_curso');
    const cursoSelect = document.getElementById('curso_id');
    const perPageSelect = document.getElementById('per_page');

    // Función debounce para optimizar eventos
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
    const cursosMatriculados = @json($cursosMatriculados->groupBy('tipo_curso_id'));

    const actualizarCursos = (tipoCursoId) => {
        cursoSelect.disabled = !tipoCursoId;
        cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
        
        if (tipoCursoId && cursosMatriculados[tipoCursoId]) {
            cursosMatriculados[tipoCursoId].forEach(curso => {
                const option = document.createElement('option');
                option.value = curso.id;
                option.textContent = `${curso.nombre} (${curso.horario})`;
                if (curso.id == {{ request('curso_id', 'null') }}) {
                    option.selected = true;
                }
                cursoSelect.appendChild(option);
            });
        }
    };

    // Event Listeners optimizados
    tipoCursoSelect.addEventListener('change', debounce(function() {
        cursoSelect.value = '';  // Resetear la selección del curso
        actualizarCursos(this.value);
        form.submit();
    }, 300));

    cursoSelect.addEventListener('change', debounce(function() {
        form.submit();
    }, 300));

    perPageSelect.addEventListener('change', debounce(function() {
        form.submit();
    }, 300));

    // Inicializar el estado del selector de cursos
    if (tipoCursoSelect.value) {
        actualizarCursos(tipoCursoSelect.value);
    }
});
</script> 