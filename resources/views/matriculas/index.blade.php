<x-app-layout>
    @section('page_title', 'Matrículas')
    
    <head>
        <!-- Font Awesome -->
        <link 
            rel="stylesheet" 
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        >
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjeta principal -->
            <div 
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg transition duration-300 hover:shadow-lg"
            >
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Encabezado de la sección -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-200">
                            Lista de Matrículas
                        </h2>
                        <a 
                            href="{{ route('matriculas.create') }}" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition"
                        >
                            Añadir Matrícula
                        </a>
                    </div>

                    <!-- Formulario para filtrar (sólo para rol 1) -->
                    @if(auth()->user()->hasRole(1))
                        <form 
                            method="GET" 
                            action="{{ route('matriculas.index') }}" 
                            id="cursoForm" 
                            class="mb-6"
                        >
                            <div class="flex gap-4 mb-4">
                                <select 
                                    id="tipo_curso" 
                                    name="tipo_curso" 
                                    class="w-1/2 rounded-md border border-gray-300 dark:border-gray-600 
                                    dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 
                                    transition"
                                >
                                    <option value="">Seleccione un tipo de curso</option>
                                    @foreach($tiposCursos as $tipoCurso)
                                        <option 
                                            value="{{ $tipoCurso->id }}" 
                                            {{ request('tipo_curso') == $tipoCurso->id ? 'selected' : '' }}
                                        >
                                            {{ $tipoCurso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <select 
                                    id="curso_id" 
                                    name="curso_id" 
                                    class="w-1/2 rounded-md border border-gray-300 dark:border-gray-600 
                                    dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 
                                    transition" 
                                    {{ $cursos->isEmpty() ? 'disabled' : '' }}
                                >
                                    <option value="">Seleccione un curso</option>
                                    @foreach($cursos as $curso)
                                        <option 
                                            value="{{ $curso->id }}" 
                                            {{ request('curso_id') == $curso->id ? 'selected' : '' }}
                                        >
                                            {{ $curso->nombre }} ({{ $curso->horario }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-4 mb-4">
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Buscar matrícula..." 
                                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 
                                    dark:bg-gray-700 dark:text-gray-300 placeholder-gray-400 
                                    focus:ring-blue-500 focus:border-blue-500 transition"
                                >
                                <button 
                                    type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded 
                                    transition"
                                >
                                    Buscar
                                </button>
                            </div>
                        </form>
                    @endif
                    
                    <!-- Tabla de Matrículas -->
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    @if(!auth()->user()->hasRole(1))
                                        <th 
                                            class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                        >
                                            Acciones
                                        </th>
                                    @endif
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        ID
                                    </th>
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        Usuario
                                    </th>
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        Curso
                                    </th>
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        Fecha
                                    </th>
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        Monto Total
                                    </th>
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        Valor Pendiente
                                    </th>
                                    <th 
                                        class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                    >
                                        Estado
                                    </th>
                                    @if(auth()->user()->hasRole(1))
                                        <th 
                                            class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider"
                                        >
                                            Acciones
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($matriculas->sortByDesc('id') as $matricula)
                                    <tr class="{{ $matricula->estado_matricula == 'Rechazada' ? 'bg-red-200' : ($matricula->estado_matricula == 'Entregado' ? 'bg-green-200' : ($loop->index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800')) }}">
                                        @if(!auth()->user()->hasRole(1))
                                            <td 
                                                class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2"
                                            >
                                                <!-- Pago -->
                                                <a 
                                                    href="{{ route('pagos.create', ['matricula_id' => $matricula->id]) }}"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded transition"
                                                    title="Realizar pago"
                                                >
                                                    💵 PAGAR
                                                </a>
                                            </td>
                                        @endif
                                        <td 
                                            class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200"
                                        >
                                            {{ $matricula->id }}
                                        </td>
                                        <td 
                                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300"
                                        >
                                            {{ $matricula->usuario->name }}
                                        </td>
                                        <td 
                                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300"
                                        >
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $matricula->curso->nombre }}</span>
                                                <span class="text-xs text-gray-400">
                                                    {{ $matricula->curso->tipoCurso->nombre ?? 'Sin sede' }} | {{ $matricula->curso->horario ?? 'Sin horario' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td 
                                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300"
                                        >
                                            {{ $matricula->fecha_matricula }}
                                        </td>
                                        <td 
                                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300"
                                        >
                                            {{ $matricula->monto_total }}
                                        </td>
                                        <td 
                                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300"
                                        >
                                            {{ $matricula->valor_pendiente }}
                                        </td>
                                        <td 
                                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300"
                                        >
                                            <span class="{{ $matricula->estado_matricula == 'Aprobada' ? 'text-green-500' : ($matricula->estado_matricula == 'Completada' ? 'text-blue-500' : ($matricula->estado_matricula == 'Rechazada' ? 'text-red-500' : 'text-gray-500')) }}">
                                                {{ $matricula->estado_matricula }}
                                            </span>
                                        </td>
                                        @if(auth()->user()->hasRole(1))
                                            <td 
                                                class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2"
                                            >
                                                <!-- Ver -->
                                                <a 
                                                    href="{{ route('matriculas.show', $matricula) }}"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded transition"
                                                >
                                                    👁️
                                                </a>
                                                <!-- Editar -->
                                                <a 
                                                    href="{{ route('matriculas.edit', $matricula) }}"
                                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded transition"
                                                >
                                                    ✏️
                                                </a>
                                                <!-- Eliminar -->
                                                <form 
                                                    action="{{ route('matriculas.destroy', $matricula) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded transition"
                                                    >
                                                        🗑️
                                                    </button>
                                                </form>
                                                <!-- Nuevo botón de calificaciones -->
                                                <a href="{{ route('matriculas.calificaciones', $matricula) }}" 
                                                   class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded transition"
                                                   title="Ver calificaciones">
                                                    📊
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole(1))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tipoCursoSelect = document.getElementById('tipo_curso');
                const cursoSelect = document.getElementById('curso_id');
                const cursos = @json($cursos);

                // Evento para filtrar cursos al cambiar el tipo de curso
                tipoCursoSelect.addEventListener('change', function() {
                    if (tipoCursoSelect.value) {
                        cursoSelect.disabled = false;
                        const cursosFiltrados = cursos.filter(curso => curso.tipo_curso_id == tipoCursoSelect.value);
                        cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
                        cursosFiltrados.forEach(curso => {
                            cursoSelect.innerHTML += `
                                <option value="${curso.id}">${curso.nombre} (${curso.horario})</option>
                            `;
                        });
                    } else {
                        cursoSelect.disabled = true;
                        cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
                    }
                    document.getElementById('cursoForm').submit();
                });

                // Evento para filtrar nuevamente al cambiar de curso
                cursoSelect.addEventListener('change', function() {
                    document.getElementById('cursoForm').submit();
                });
            });
        </script>
    @endif
</x-app-layout>
