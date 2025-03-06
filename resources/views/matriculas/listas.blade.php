<x-app-layout>
    @section('page_title', 'Listas')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Opciones Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg mb-6">
                <div class="p-6">
                    <div x-data="{ openOptions: false }" class="space-y-4">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 flex-wrap">
                            @if(auth()->user()->hasRole(1))
                                <button @click="openOptions = !openOptions" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white text-sm py-1.5 px-3 rounded">
                                    {{ __('Opciones de Fondo') }}
                                </button>
                            @endif
                            @if($matriculas->isNotEmpty())
                                @if(auth()->user()->hasRole(1))
                                    <button id="print-credentials" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white text-sm py-1.5 px-3 rounded">
                                        {{ __('Imprimir Credenciales') }}
                                    </button>
                                    <button id="print-certificates" class="btn btn-primary bg-purple-500 hover:bg-purple-700 text-white text-sm py-1.5 px-3 rounded">
                                        {{ __('Imprimir Certificados') }}
                                    </button>
                                @endif
                                <button id="register-attendance" class="btn btn-primary bg-green-500 hover:bg-green-700 text-white text-sm py-1.5 px-3 rounded">
                                    {{ __('Registrar Asistencia') }}
                                </button>
                                @if(auth()->user()->hasRole(1))
                                    <a href="{{ route('matriculas.exportPdf', ['curso_id' => $cursoId]) }}" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white text-sm py-1.5 px-3 rounded">
                                        {{ __('Exportar PDF') }}
                                    </a>
                                    <a href="{{ route('matriculas.exportExcel', ['curso_id' => $cursoId]) }}" class="btn btn-primary bg-green-500 hover:bg-green-700 text-white text-sm py-1.5 px-3 rounded">
                                        {{ __('Exportar Excel') }}
                                    </a>
                                    <a href="{{ route('matriculas.exportPendientesExcel', ['curso_id' => $cursoId, 'sort' => 'name', 'order' => 'asc']) }}" class="btn btn-primary bg-yellow-500 hover:bg-yellow-700 text-white text-sm py-1.5 px-3 rounded">
                                        {{ __('Exportar Pendientes') }}
                                    </a>
                                @endif
                            @endif
                        </div>
                        
                        <div x-show="openOptions" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="w-full">
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('matriculas.uploadBackground') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="file" name="background" accept="image/*" class="rounded-md border-gray-300" />
                                    <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white text-sm py-1.5 px-3 rounded">
                                        {{ __('Subir Fondo') }}
                                    </button>
                                </form>
                                <a href="{{ asset('storage/imagenes_de_fondo_permanentes/background.jpg') }}" download class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white text-sm py-1.5 px-3 rounded">
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
                            <table class="w-full table-auto text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="p-2 text-left">
                                            <input type="checkbox" id="select-all" class="w-4 h-4">
                                        </th>
                                        <th class="p-2 text-left">#</th>
                                        <th class="p-2 text-left">{{ __('Photo') }}</th>
                                        <th class="p-2 text-left">{{ __('Nombre') }}</th>
                                        <th class="p-2 text-left">{{ __('Valor') }}</th>
                                        <th class="p-2 text-left">{{ __('Carnet') }}</th>
                                        <th class="p-2 text-left">{{ __('Celular') }}</th>
                                        <th class="p-2 text-left">{{ __('Cédula') }}</th>
                                        <th class="p-2 text-left">Estado</th>
                                        <th class="p-2 text-left">{{ __('Asistencias') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matriculas as $index => $matricula)
                                        <tr class="{{ ($matricula->estado_matricula == 'Entregado' || ($matricula->usuario->profile && $matricula->usuario->profile->carnet == 'Entregado')) ? 'bg-pastel-orange' : ($loop->even ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800') }}">
                                            <td class="p-2">
                                                <input type="checkbox" class="select-row w-4 h-4" value="{{ $matricula->id }}">
                                            </td>
                                            <td class="p-2">{{ ($matriculas->currentPage() - 1) * $matriculas->perPage() + $loop->iteration }}</td>
                                            <td class="p-2">
                                                @if($matricula->usuario->profile && $matricula->usuario->profile->photo)
                                                    <img loading="lazy" src="{{ asset('storage/' . $matricula->usuario->profile->photo) }}" 
                                                         alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full">
                                                        <i class="fa fa-user text-sm"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="p-2 font-medium {{ ($matricula->estado_matricula == 'Entregado' || ($matricula->usuario->profile && $matricula->usuario->profile->carnet == 'Entregado')) ? 'text-blue-500' : 'text-gray-900 dark:text-gray-100' }}" data-user-id="{{ $matricula->usuario_id }}">
                                                {{ $matricula->usuario->name }}
                                            </td>
                                            <td class="p-2">
                                                <span class="{{ $matricula->valor_pendiente == 0 ? 'text-green-500' : 'text-red-500' }}">
                                                    ${{ number_format($matricula->valor_pendiente, 2) }}
                                                </span>
                                            </td>
                                            <td class="p-2">{{ ($matricula->usuario->profile && $matricula->usuario->profile->carnet == 'Entregado') ? 'Entregado' : 'NO' }}</td>
                                            <td class="p-2">
                                                @if($matricula->usuario->profile && $matricula->usuario->profile->phone)
                                                    <div class="flex items-center space-x-1">
                                                        <span>{{ $matricula->usuario->profile->phone }}</span>
                                                        @php
                                                            $phone = $matricula->usuario->profile->phone;
                                                            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                                            if (!str_starts_with($cleanPhone, '593')) {
                                                                $cleanPhone = ltrim($cleanPhone, '0');
                                                                $cleanPhone = '593' . $cleanPhone;
                                                            }
                                                        @endphp
                                                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                                           class="text-green-500 hover:text-green-600">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    </div>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="p-2">
                                                @if($matricula->usuario->profile && $matricula->usuario->profile->cedula)
                                                    {{ $matricula->usuario->profile->cedula }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="p-2">{{ $matricula->estado_matricula }}</td>
                                            <td class="p-2">{{ $matricula->asistencias }}</td>
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

/* Estilos adicionales para mejorar la visualización */
.btn {
    white-space: nowrap;
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
}

table {
    border-collapse: collapse;
    font-size: 0.875rem;
}

th {
    font-size: 0.75rem;
    text-transform: uppercase;
    font-weight: 600;
    color: #6B7280;
}

td, th {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 1024px) {
    .btn {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
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
    const printCertificatesButton = document.getElementById('print-certificates');
    const registerAttendanceButton = document.getElementById('register-attendance');

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

    // Manejo de impresión de certificados
    printCertificatesButton?.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .reduce((acc, checkbox) => {
                if (checkbox.checked) acc.push(checkbox.value);
                return acc;
            }, []);

        if (selectedIds.length > 0) {
            const cursoId = document.getElementById('curso_id').value;
            if (!cursoId) {
                alert('Por favor, seleccione un curso.');
                return;
            }
            const url = `{{ route('matriculas.printCertificates') }}?ids=${selectedIds.join(',')}&curso_id=${cursoId}`;
            window.open(url, '_blank');
        } else {
            alert('Por favor, seleccione al menos una fila.');
        }
    });

    // Manejo de registro de asistencia
    registerAttendanceButton?.addEventListener('click', async function() {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => {
                const row = checkbox.closest('tr');
                const userId = row.querySelector('td[data-user-id]').getAttribute('data-user-id');
                return userId;
            });

        if (selectedIds.length === 0) {
            alert('Por favor, seleccione al menos un estudiante.');
            return;
        }

        try {
            const response = await fetch('{{ route("asistencias.registerMultiple") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    user_ids: selectedIds
                })
            });

            const data = await response.json();

            if (response.ok) {
                alert('Asistencia registrada exitosamente');
                // Recargar la página para actualizar el contador de asistencias
                window.location.reload();
            } else {
                throw new Error(data.message || 'Error al registrar la asistencia');
            }
        } catch (error) {
            alert('Error al registrar la asistencia: ' + error.message);
        }
    });
});
</script>

