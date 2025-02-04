<x-app-layout>
    @section('page_title', 'Pagos')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pagos') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('pagos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">➕</a>
                    </div>

                    @if(auth()->user()->hasRole(1))
                        <form method="GET" action="{{ route('pagos.index') }}" id="cursoForm" class="mb-6">
                            <div class="flex gap-4 mb-4">
                                <select id="tipo_curso" name="tipo_curso" class="w-1/2 rounded-md border-gray-300">
                                    <option value="">Seleccione un tipo de curso</option>
                                    @foreach($tiposCursos as $tipoCurso)
                                        <option value="{{ $tipoCurso->id }}" {{ request('tipo_curso') == $tipoCurso->id ? 'selected' : '' }}>
                                            {{ $tipoCurso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <select id="curso_id" name="curso_id" class="w-1/2 rounded-md border-gray-300" {{ $cursos->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">Seleccione un curso</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }} ({{ $curso->horario }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    @endif

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-500">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Usuario</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Matricula</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Método de Pago</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Monto</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha de Pago</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($pagos->sortByDesc('fecha_pago') as $pago)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">
                                            <span class="{{ $pago->estado == 'Pendiente' ? 'text-orange-500' : ($pago->estado == 'Rechazado' ? 'text-red-500' : 'text-green-500') }}">
                                                {{ $pago->estado }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $pago->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->matricula->usuario->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->matricula->curso->nombre }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->metodoPago->nombre }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->monto }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ $pago->fecha_pago }}</td>
                                        <td class="px-4 py-2 text-sm font-medium flex items-center justify-center space-x-2">
                                            <a href="{{ route('pagos.show', $pago) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">👁️</a>
                                            <a href="{{ route('pagos.recibo', $pago) }}" target="_blank" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded">📄</a>
                                            @if(auth()->user()->hasRole(1))
                                                @if($pago->estado == 'Pendiente')
                                                    <form action="{{ route('pagos.aprobar', $pago) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">✔️</button>
                                                    </form>
                                                    <form action="{{ route('pagos.rechazar', $pago) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">❌</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('pagos.edit', $pago) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">✏️</a>
                                                <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">🗑️</button>
                                                </form>
                                            @endif
                                        </td>
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
        });
        </script>
    @endif
</x-app-layout>