<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del Usuario -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <div class="flex items-center space-x-4">
                    @if($user->profile && $user->profile->photo)
                        <img src="{{ asset('storage/' . $user->profile->photo) }}" 
                             alt="{{ $user->name }}" 
                             class="w-16 h-16 rounded-full object-cover">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                            <span class="text-2xl text-gray-600 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $user->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Calendario y Lista de Asistencias -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Calendario -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Calendario de Asistencias</h2>
                    <div id="calendar" class="dark:bg-gray-800 dark:text-gray-200"></div>
                </div>

                <!-- Lista de Asistencias -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Registro de Asistencias</h2>
                    <div class="overflow-x-auto">
                        <div class="max-h-[400px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Entrada</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salida</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($asistencias as $asistencia)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $asistencia->fecha_hora->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $asistencia->hora_entrada ? $asistencia->hora_entrada->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $asistencia->hora_salida ? $asistencia->hora_salida->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($asistencia->estado === 'presente') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($asistencia->estado === 'tardanza') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($asistencia->estado === 'fuga') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                @endif">
                                                {{ ucfirst($asistencia->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cursos Matriculados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Cursos Matriculados</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($matriculasConHorarios as $item)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                {{ $item['matricula']->curso->nombre }}
                            </div>
                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>{{ $item['horarioFormateado'] }}</span>
                                    </div>
                                    <div class="flex items-center bg-blue-100 dark:bg-blue-900 px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                                            {{ $item['asistenciasCurso'] }} asistencias
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span>{{ $item['matricula']->curso->sede ?? 'Sede Principal' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-4 text-gray-600 dark:text-gray-400">
                            No hay cursos matriculados
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel="stylesheet" />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            themeSystem: document.documentElement.classList.contains('dark') ? 'dark' : 'standard',
            events: [
                @foreach($asistencias as $asistencia)
                {
                    title: '{{ ucfirst($asistencia->estado) }}',
                    start: '{{ $asistencia->fecha_hora->format("Y-m-d") }}',
                    backgroundColor: '{{ 
                        $asistencia->estado === "presente" ? "#10B981" : 
                        ($asistencia->estado === "tardanza" ? "#FBBF24" : 
                        ($asistencia->estado === "fuga" ? "#EF4444" : "#9CA3AF")) 
                    }}',
                    textColor: '#ffffff',
                    extendedProps: {
                        entrada: '{{ $asistencia->hora_entrada ? $asistencia->hora_entrada->format("H:i") : "-" }}',
                        salida: '{{ $asistencia->hora_salida ? $asistencia->hora_salida->format("H:i") : "-" }}'
                    }
                },
                @endforeach
            ],
            eventDidMount: function(info) {
                info.el.title = `Estado: ${info.event.title}\nEntrada: ${info.event.extendedProps.entrada}\nSalida: ${info.event.extendedProps.salida}`;
            }
        });
        calendar.render();

        // Actualizar el tema del calendario cuando cambie el modo oscuro
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    calendar.setOption('themeSystem', 
                        document.documentElement.classList.contains('dark') ? 'dark' : 'standard'
                    );
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
    </script>
    @endpush
</x-app-layout> 