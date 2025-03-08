<x-app-layout>
    @section('page_title', $aulasVirtuale->nombre)
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-200">
                            {{ $aulasVirtuale->nombre }}
                        </h2>
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                Agregar Contenido
                            </button>
                        @endif
                    </div>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Lista de contenidos -->
                    <div class="space-y-6">
                        @foreach($aulasVirtuale->contenidos as $contenido)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                                        {{ $contenido->titulo }}
                                    </h3>
                                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                        <form action="{{ route('aulas_virtuales.contenidos.destroy', $contenido->id) }}" 
                                              method="POST" 
                                              class="ml-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-500 hover:text-red-700 transition-colors">
                                                <span class="sr-only">Eliminar</span>
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                @if($contenido->contenido)
                                    <div class="prose dark:prose-invert max-w-none mb-4 text-gray-600 dark:text-gray-300">
                                        {!! nl2br(e($contenido->contenido)) !!}
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-4 mt-4">
                                    @if($contenido->enlace)
                                        <a href="{{ $contenido->enlace }}" 
                                           target="_blank" 
                                           class="inline-flex items-center text-blue-500 hover:text-blue-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Abrir enlace
                                        </a>
                                    @endif

                                    @if($contenido->archivo)
                                        <a href="{{ Storage::url($contenido->archivo) }}" 
                                           target="_blank"
                                           class="inline-flex items-center text-green-500 hover:text-green-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Descargar archivo
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Lista de tareas -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            Tareas
                        </h3>
                        
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <div class="mb-4">
                                <button onclick="toggleTareaModal()" 
                                        class="inline-flex items-center bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition">
                                    <span class="mr-2">📚</span> Crear Tarea
                                </button>
                            </div>
                        @endif

                        <div class="space-y-4">
                            @forelse($aulasVirtuale->tareas as $tarea)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-4 {{ $tarea->estado === 'activo' ? 'border-green-500' : 'border-red-500' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                                {{ $tarea->titulo }}
                                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $tarea->estado === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ $tarea->estado === 'activo' ? 'Activa' : 'Inactiva' }}
                                                </span>
                                            </h4>
                                            @if($tarea->descripcion)
                                                <div class="mt-2">
                                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Descripción:</h5>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $tarea->descripcion }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if($tarea->imagenes && count($tarea->imagenes) > 0)
                                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                                                    @foreach($tarea->imagenes as $imagen)
                                                        <div class="relative group">
                                                            <img src="{{ asset('storage/' . $imagen) }}" 
                                                                 alt="Imagen de la tarea"
                                                                 class="w-full h-32 object-cover rounded-lg shadow-sm">
                                                            <a href="{{ asset('storage/' . $imagen) }}" 
                                                               target="_blank"
                                                               class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center">
                                                                <span class="text-white opacity-0 group-hover:opacity-100">
                                                                    Ver imagen
                                                                </span>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($tarea->archivos && count($tarea->archivos) > 0)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <h6 class="w-full text-sm font-medium text-gray-700 dark:text-gray-300">Archivos:</h6>
                                                    @foreach($tarea->archivos as $archivo)
                                                        <a href="{{ asset('storage/' . $archivo) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition-colors">
                                                            <span class="mr-1">📎</span>
                                                            {{ basename($archivo) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($tarea->enlaces && count($tarea->enlaces) > 0)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <h6 class="w-full text-sm font-medium text-gray-700 dark:text-gray-300">Enlaces:</h6>
                                                    @foreach($tarea->enlaces as $enlace)
                                                        <a href="{{ $enlace }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 transition-colors">
                                                            <span class="mr-1">🔗</span>
                                                            {{ parse_url($enlace, PHP_URL_HOST) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Fecha límite:</span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        📅 {{ $tarea->fecha_limite->format('d/m/Y H:i') }}
                                                    </span>
                                                    @php
                                                        $tiempoRestante = now()->diff($tarea->fecha_limite);
                                                        $estado = now()->gt($tarea->fecha_limite) ? 'Vencida' : 'Tiempo restante';
                                                        $claseEstado = now()->gt($tarea->fecha_limite) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                                                    @endphp
                                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $claseEstado }}">
                                                        ⏱️ {{ $estado }}: 
                                                        @if(!now()->gt($tarea->fecha_limite))
                                                            @if($tiempoRestante->days > 0)
                                                                {{ $tiempoRestante->days }} días
                                                            @elseif($tiempoRestante->h > 0)
                                                                {{ $tiempoRestante->h }} horas
                                                            @elseif($tiempoRestante->i > 0)
                                                                {{ $tiempoRestante->i }} minutos
                                                            @else
                                                                {{ $tiempoRestante->s }} segundos
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Puntuación:</span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        💯 {{ $tarea->puntos_maximos }} puntos
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                                <a href="{{ route('tareas.edit', $tarea) }}" 
                                                   class="text-yellow-500 hover:text-yellow-700 transition-colors">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('tareas.destroy', $tarea) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="redirect_to" value="{{ route('aulas_virtuales.show', $aulasVirtuale) }}">
                                                    <button type="submit" 
                                                            class="text-red-500 hover:text-red-700 transition-colors">
                                                        🗑️
                                                    </button>
                                                </form>
                                                <form action="{{ route('tareas.toggle-estado', $tarea) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="{{ $tarea->estado === 'activo' ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }} px-2 py-1 rounded-md flex items-center transition-colors">
                                                        <span class="mr-1">{{ $tarea->estado === 'activo' ? '🚫' : '✓' }}</span>
                                                        {{ $tarea->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                                <button onclick="toggleCalificarModal({{ $tarea->id }}, '{{ $tarea->titulo }}')"
                                                        class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                    Calificar 📝 ({{ $tarea->entregas()->whereNotNull('calificacion')->count() }}/{{ $tarea->entregas()->count() }})
                                                </button>
                                            @endif
                                            @if(!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente'))
                                                @php
                                                    $entregaExistente = $tarea->entregas->where('user_id', auth()->id())->first();
                                                @endphp
                                                @if(!$entregaExistente && $tarea->fecha_limite->isFuture() && $tarea->estado === 'activo')
                                                    <button onclick="toggleEntregaModal({{ $tarea->id }}, '{{ $tarea->titulo }}')"
                                                            class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                        Entregar 📤
                                                    </button>
                                                @elseif($entregaExistente)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-sm bg-gray-100 text-gray-700">
                                                        ✓ Entregada
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @if($tarea->entregas->count() > 0 && (auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente')))
                                        <div class="mt-4 border-t pt-4">
                                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Entregas:</h5>
                                            <div class="space-y-2">
                                                @foreach($tarea->entregas as $entrega)
                                                    <div class="flex items-center justify-between bg-white dark:bg-gray-600 p-2 rounded">
                                                        <div class="flex items-center">
                                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                                {{ $entrega->user->name }} - 
                                                                {{ $entrega->created_at->format('d/m/Y H:i') }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            @if($entrega->archivo)
                                                                <a href="{{ Storage::url($entrega->archivo) }}" 
                                                                   target="_blank"
                                                                   class="text-blue-500 hover:text-blue-700">
                                                                    📎 Ver archivo
                                                                </a>
                                                            @endif
                                                            @if($entrega->enlace)
                                                                <a href="{{ $entrega->enlace }}" 
                                                                   target="_blank"
                                                                   class="text-blue-500 hover:text-blue-700">
                                                                    🔗 Ver enlace
                                                                </a>
                                                            @endif
                                                            @if($entrega->calificacion)
                                                                <span class="text-green-500 font-semibold">
                                                                    {{ $entrega->calificacion }}/{{ $tarea->puntos_maximos }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay tareas disponibles en esta aula virtual.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Lista de cuestionarios -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            Cuestionarios
                        </h3>
                        
                        @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                            <div class="mb-4">
                                <a href="{{ route('cuestionarios.create', $aulasVirtuale) }}" 
                                   class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                    <span class="mr-2">📝</span> Crear Cuestionario
                                </a>
                            </div>
                        @endif

                        <div class="space-y-4">
                            @forelse($aulasVirtuale->cuestionarios as $cuestionario)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $cuestionario->titulo }}
                                            </h4>
                                            @if($cuestionario->descripcion)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $cuestionario->descripcion }}
                                                </p>
                                            @endif
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    ⏱️ {{ $cuestionario->tiempo_limite }} minutos
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    🔄 {{ $cuestionario->intentos_permitidos }} intentos permitidos
                                                </span>
                                                @if($cuestionario->activo)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        ✅ Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        ❌ Inactivo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                                                <a href="{{ route('cuestionarios.edit', $cuestionario) }}" 
                                                   class="text-yellow-500 hover:text-yellow-700 transition-colors">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('cuestionarios.destroy', $cuestionario) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cuestionario? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="redirect_to" value="{{ route('aulas_virtuales.show', $aulasVirtuale) }}">
                                                    <button type="submit" 
                                                            class="text-red-500 hover:text-red-700 transition-colors">
                                                        🗑️
                                                    </button>
                                                </form>
                                                <form action="{{ route('cuestionarios.toggle', $cuestionario) }}" 
                                                      method="POST" 
                                                      class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="{{ $cuestionario->activo ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }} px-2 py-1 rounded-md flex items-center transition-colors">
                                                        <span class="mr-1">{{ $cuestionario->activo ? '🚫' : '✓' }}</span>
                                                        {{ $cuestionario->activo ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                                <button onclick="mostrarResultadosCuestionario({{ $cuestionario->id }}, '{{ $cuestionario->titulo }}')"
                                                        class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                    Ver Resultados 📊
                                                </button>
                                            @endif
                                            @if(!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente'))
                                                @php
                                                    $intentoExistente = DB::table('respuestas_usuario')
                                                        ->join('intentos_cuestionario', 'respuestas_usuario.intento_id', '=', 'intentos_cuestionario.id')
                                                        ->where('intentos_cuestionario.cuestionario_id', $cuestionario->id)
                                                        ->where('intentos_cuestionario.usuario_id', auth()->id())
                                                        ->latest('intentos_cuestionario.created_at')
                                                        ->first();
                                                @endphp
                                                
                                                @if(!$intentoExistente && $cuestionario->activo)
                                                    <a href="{{ route('cuestionarios.show', $cuestionario) }}" 
                                                       class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                        Realizar 📝
                                                    </a>
                                                @elseif($intentoExistente && $cuestionario->permite_revision)
                                                    <div class="flex flex-col items-end gap-2">
                                                        @php
                                                            $totalPreguntas = $cuestionario->preguntas()->count();
                                                            $respuestasCorrectas = DB::table('respuestas_usuario')
                                                                ->join('opciones', 'respuestas_usuario.opcion_id', '=', 'opciones.id')
                                                                ->join('preguntas', 'respuestas_usuario.pregunta_id', '=', 'preguntas.id')
                                                                ->where('respuestas_usuario.intento_id', $intentoExistente->id)
                                                                ->where('preguntas.cuestionario_id', $cuestionario->id)
                                                                ->where('opciones.es_correcta', true)
                                                                ->count();
                                                            $calificacion = $intentoExistente->calificacion ?? (($totalPreguntas > 0) ? ($respuestasCorrectas / $totalPreguntas) * 100 : 0);
                                                        @endphp
                                                        <span class="text-sm {{ $calificacion >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                            Calificación: {{ number_format($calificacion, 2) }}%
                                                        </span>
                                                        <a href="{{ route('cuestionarios.revision', ['cuestionario' => $cuestionario, 'intento' => $intentoExistente->id]) }}" 
                                                           class="inline-flex items-center bg-purple-500 hover:bg-purple-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                            Ver Revisión 📋
                                                        </a>
                                                        @php
                                                            $intentosRealizados = DB::table('intentos_cuestionario')
                                                                ->where('cuestionario_id', $cuestionario->id)
                                                                ->where('usuario_id', auth()->id())
                                                                ->count();
                                                        @endphp
                                                        @if($cuestionario->activo && $intentosRealizados < $cuestionario->intentos_permitidos)
                                                            <a href="{{ route('cuestionarios.show', $cuestionario) }}" 
                                                               class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                                Intentar Nuevamente 🔄
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay cuestionarios disponibles en esta aula virtual.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Modal para crear tarea -->
                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                        <div id="tareaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl my-8 mx-auto max-h-[90vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-gray-800 z-10 py-2">
                                    <h3 class="text-lg font-bold">Crear Nueva Tarea</h3>
                                    <button onclick="toggleTareaModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ route('tareas.store', $aulasVirtuale) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="tareaForm">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Título</label>
                                        <input type="text" 
                                               name="titulo" 
                                               required
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Descripción</label>
                                        <textarea name="descripcion" 
                                                  rows="4"
                                                  class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Imágenes (máx. 10MB por imagen)</label>
                                        <div class="flex items-center">
                                            <input type="file" 
                                                   id="imagenes-input"
                                                   accept="image/*"
                                                   class="hidden"
                                                   onchange="handleFileSelection(this, 'imagenes')">
                                            <button type="button" 
                                                    onclick="document.getElementById('imagenes-input').click()"
                                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                                Seleccionar imágenes
                                            </button>
                                            <span id="imagenes-count" class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                                0 imágenes seleccionadas
                                            </span>
                                        </div>
                                        <div id="imagenes-error" class="hidden mt-2 text-sm text-red-600"></div>
                                        <div id="imagenes-preview" class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2"></div>
                                        <div id="imagenes-container"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Archivos adjuntos (máx. 10MB por archivo)</label>
                                        <div class="flex items-center">
                                            <input type="file" 
                                                   id="archivos-input"
                                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
                                                   class="hidden"
                                                   onchange="handleFileSelection(this, 'archivos')">
                                            <button type="button" 
                                                    onclick="document.getElementById('archivos-input').click()"
                                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                                Seleccionar archivos
                                            </button>
                                            <span id="archivos-count" class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                                0 archivos seleccionados
                                            </span>
                                        </div>
                                        <div id="archivos-error" class="hidden mt-2 text-sm text-red-600"></div>
                                        <div id="archivos-preview" class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2"></div>
                                        <div id="archivos-container"></div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Formatos permitidos: PDF, Word, Excel, PowerPoint, ZIP, RAR
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Fecha Límite</label>
                                            <input type="datetime-local" 
                                                   name="fecha_limite" 
                                                   required
                                                   value="{{ date('Y-m-d\TH:i') }}"
                                                   min="{{ date('Y-m-d\TH:i') }}"
                                                   class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-2">Puntos Máximos</label>
                                            <input type="number" 
                                                   name="puntos_maximos" 
                                                   required
                                                   min="0"
                                                   value="10"
                                                   class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Estado</label>
                                        <select name="estado" 
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                            <option value="activo" selected>Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Enlaces</label>
                                        <div id="enlaces-container">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <input type="url" 
                                                     name="enlaces[]" 
                                                     placeholder="https://ejemplo.com"
                                                     class="flex-1 rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                                <button type="button" onclick="addEnlaceField(this)" class="px-2 py-1 bg-blue-500 text-white rounded">+</button>
                                                <button type="button" onclick="removeEnlaceField(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                            </div>
                                        </div>
                                        <button type="button" onclick="addNewEnlaceField()" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                                            + Añadir otro enlace
                                        </button>
                                    </div>

                                    <div class="flex justify-end space-x-2 mt-6 sticky bottom-0 bg-white dark:bg-gray-800 py-2">
                                        <button type="button" 
                                                onclick="toggleTareaModal()"
                                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                id="submit-tarea-btn"
                                                class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition-colors">
                                            Crear Tarea
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Modal para entregar tarea -->
                    <div id="entregaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-bold">Entregar Tarea</h3>
                                    <p class="text-sm text-gray-500" id="entrega-tarea-titulo"></p>
                                </div>
                                <button onclick="toggleEntregaModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <form id="entregaForm" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <span class="font-medium">Importante:</span> Puedes entregar un archivo, un enlace o ambos. 
                                                Una vez entregada la tarea, no podrás modificarla a menos que el docente la elimine.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="entrega-archivo" class="block text-sm font-medium mb-2">Archivo (opcional)</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="entrega-archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                    <span>Subir un archivo</span>
                                                    <input id="entrega-archivo" name="archivo" type="file" class="sr-only" onchange="updateFilePreview()">
                                                </label>
                                                <p class="pl-1">o arrastra y suelta</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Documentos, imágenes o archivos ZIP (máx. 10MB)
                                            </p>
                                            <div id="archivo-preview" class="mt-2 text-sm"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="entrega-enlace" class="block text-sm font-medium mb-2">Enlace (opcional)</label>
                                    <input type="url" 
                                           id="entrega-enlace"
                                           name="enlace" 
                                           placeholder="https://ejemplo.com"
                                           class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Puedes incluir un enlace a Google Drive, Dropbox u otro servicio.
                                    </p>
                                </div>
                                
                                <div id="entrega-error" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                                    Debes proporcionar al menos un archivo o un enlace.
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" 
                                            onclick="toggleEntregaModal()"
                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="button"
                                            onclick="validarYEnviarEntrega()"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                                        Entregar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal para agregar contenido -->
                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                        <div id="contenidoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold">Agregar Contenido</h3>
                                    <button onclick="toggleModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Tabs -->
                                <div class="mb-4 border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px" role="tablist">
                                        <li class="mr-2">
                                            <button onclick="switchTab('texto')" 
                                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-button active"
                                                    id="texto-tab">
                                                📝 Texto
                                            </button>
                                        </li>
                                        <li class="mr-2">
                                            <button onclick="switchTab('enlace')"
                                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-button"
                                                    id="enlace-tab">
                                                🔗 Enlace
                                            </button>
                                        </li>
                                        <li class="mr-2">
                                            <button onclick="switchTab('archivo')"
                                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-button"
                                                    id="archivo-tab">
                                                📎 Archivo
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                
                                <form action="{{ route('aulas_virtuales.contenidos.store', $aulasVirtuale) }}" 
                                      method="POST" 
                                      enctype="multipart/form-data"
                                      class="space-y-4">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Título</label>
                                        <input type="text" 
                                               name="titulo" 
                                               required
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div id="texto-content" class="tab-content">
                                        <label class="block text-sm font-medium mb-2">Contenido</label>
                                        <textarea name="contenido" 
                                                  rows="4"
                                                  class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>

                                    <div id="enlace-content" class="tab-content hidden">
                                        <label class="block text-sm font-medium mb-2">Enlace</label>
                                        <input type="url" 
                                               name="enlace"
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div id="archivo-content" class="tab-content hidden">
                                        <label class="block text-sm font-medium mb-2">Archivo</label>
                                        <input type="file" 
                                               name="archivo"
                                               id="archivo-input"
                                               onchange="validarArchivos(this, 'archivo')"
                                               class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                        <div id="archivo-error" class="hidden mt-2 text-sm text-red-600"></div>
                                        <div id="archivo-warning" class="hidden mt-2 p-4 bg-yellow-100 text-yellow-700 rounded-lg">
                                            El archivo es demasiado grande (más de 10MB). Por favor:
                                            <ol class="list-decimal ml-4 mt-2">
                                                <li>Sube tu archivo a Google Drive</li>
                                                <li>Copia el enlace de compartir</li>
                                                <li>Usa la pestaña "Enlace" para compartir el archivo</li>
                                            </ol>
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-2 mt-6">
                                        <button type="button" 
                                                onclick="toggleModal()"
                                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                            Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Modal para calificar tarea -->
                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                        <div id="calificarModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold">Calificar Tarea</h3>
                                        <p class="text-sm text-gray-500" id="tarea-titulo"></p>
                                    </div>
                                    <button onclick="toggleCalificarModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div id="calificarContent" class="space-y-4">
                                    <!-- Aquí se cargarán las entregas -->
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Modal para resultados del cuestionario -->
                    @if(auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente'))
                        <div id="resultadosModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-4xl">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold">Resultados del Cuestionario</h3>
                                        <p class="text-sm text-gray-500" id="resultados-cuestionario-titulo"></p>
                                    </div>
                                    <button onclick="toggleResultadosModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div id="resultadosContent" class="mt-4">
                                    <!-- Aquí se cargarán los resultados -->
                                </div>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            function toggleResultadosModal() {
                                const modal = document.getElementById('resultadosModal');
                                modal.classList.toggle('hidden');
                                modal.classList.toggle('flex');
                            }

                            function mostrarResultadosCuestionario(cuestionarioId, titulo) {
                                const modal = document.getElementById('resultadosModal');
                                const content = document.getElementById('resultadosContent');
                                const tituloElement = document.getElementById('resultados-cuestionario-titulo');
                                
                                // Mostrar modal y mensaje de carga
                                modal.classList.remove('hidden');
                                modal.classList.add('flex');
                                content.innerHTML = '<p class="text-center text-gray-500">Cargando resultados...</p>';
                                tituloElement.textContent = titulo;
                                
                                // Obtener resultados
                                fetch(`/cuestionarios/${cuestionarioId}/resultados`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (!data.success) {
                                        throw new Error(data.message || 'Error al cargar los resultados');
                                    }

                                    if (!data.resultados || data.resultados.length === 0) {
                                        content.innerHTML = `
                                            <div class="text-center text-gray-500 py-4">
                                                No hay intentos registrados para este cuestionario.
                                            </div>
                                        `;
                                        return;
                                    }

                                    let html = `
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Estudiante
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Mejor Calificación
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Intentos Realizados
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Último Intento
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    `;

                                    data.resultados.forEach(resultado => {
                                        html += `
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        ${resultado.nombre}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        ${resultado.email}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        ${parseFloat(resultado.mejor_calificacion) >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                                        ${resultado.mejor_calificacion}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    ${resultado.intentos_realizados}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    ${new Date(resultado.ultimo_intento).toLocaleString()}
                                                </td>
                                            </tr>
                                        `;
                                    });

                                    html += `
                                                </tbody>
                                            </table>
                                        </div>
                                    `;

                                    content.innerHTML = html;
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    content.innerHTML = `
                                        <div class="bg-red-50 border-l-4 border-red-500 p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-red-700">
                                                        ${error.message}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                        </script>
                        @endpush
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal() {
            const modal = document.getElementById('contenidoModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function switchTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Mostrar el contenido seleccionado
            document.getElementById(`${tabName}-content`).classList.remove('hidden');
            
            // Actualizar estados de los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-500');
                button.classList.add('border-transparent');
            });
            
            // Activar el botón seleccionado
            const activeButton = document.getElementById(`${tabName}-tab`);
            activeButton.classList.add('border-blue-500', 'text-blue-500');
            activeButton.classList.remove('border-transparent');
        }

        function validarTamanoArchivo(input) {
            const archivo = input.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB en bytes
            const errorDiv = document.getElementById('archivo-error');
            const warningDiv = document.getElementById('archivo-warning');
            const submitButton = input.closest('form').querySelector('button[type="submit"]');

            if (archivo && archivo.size > maxSize) {
                input.value = ''; // Limpiar el input
                errorDiv.textContent = 'El archivo excede el límite de 10MB.';
                errorDiv.classList.remove('hidden');
                warningDiv.classList.remove('hidden');
                submitButton.disabled = true;
            } else {
                errorDiv.classList.add('hidden');
                warningDiv.classList.add('hidden');
                submitButton.disabled = false;
            }
        }

        function toggleTareaModal() {
            const modal = document.getElementById('tareaModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
            
            // Limpiar formulario al cerrar
            if (modal.classList.contains('hidden')) {
                resetTareaForm();
            }
        }

        function resetTareaForm() {
            document.getElementById('tareaForm').reset();
            document.getElementById('imagenes-preview').innerHTML = '';
            document.getElementById('archivos-preview').innerHTML = '';
            document.getElementById('imagenes-container').innerHTML = '';
            document.getElementById('archivos-container').innerHTML = '';
            document.getElementById('imagenes-count').textContent = '0 imágenes seleccionadas';
            document.getElementById('archivos-count').textContent = '0 archivos seleccionados';
            document.getElementById('imagenes-error').classList.add('hidden');
            document.getElementById('archivos-error').classList.add('hidden');
            
            // Reiniciar enlaces
            const enlacesContainer = document.getElementById('enlaces-container');
            enlacesContainer.innerHTML = `
                <div class="flex items-center space-x-2 mb-2">
                    <input type="url" 
                         name="enlaces[]" 
                         placeholder="https://ejemplo.com"
                         class="flex-1 rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <button type="button" onclick="addEnlaceField(this)" class="px-2 py-1 bg-blue-500 text-white rounded">+</button>
                    <button type="button" onclick="removeEnlaceField(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                </div>
            `;
        }

        function toggleEntregaModal(tareaId = null, tareaTitulo = null) {
            const modal = document.getElementById('entregaModal');
            const form = document.getElementById('entregaForm');
            const tituloElement = document.getElementById('entrega-tarea-titulo');
            
            // Resetear el formulario
            form.reset();
            document.getElementById('archivo-preview').innerHTML = '';
            document.getElementById('entrega-error').classList.add('hidden');
            
            if (tareaId) {
                form.action = `/tareas/${tareaId}/entregar`;
                if (tareaTitulo) {
                    tituloElement.textContent = tareaTitulo;
                }
            }
            
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
        
        function updateFilePreview() {
            const input = document.getElementById('entrega-archivo');
            const preview = document.getElementById('archivo-preview');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSizeInMB = file.size / (1024 * 1024);
                
                // Comprobar el tamaño del archivo
                if (fileSizeInMB > 10) {
                    preview.innerHTML = `<span class="text-red-500">El archivo excede el límite de 10MB (${fileSizeInMB.toFixed(2)}MB)</span>`;
                    input.value = '';
                    return;
                }
                
                preview.innerHTML = `<span class="text-green-500">Archivo seleccionado: ${file.name} (${fileSizeInMB.toFixed(2)}MB)</span>`;
            } else {
                preview.innerHTML = '';
            }
        }
        
        function validarYEnviarEntrega() {
            const archivo = document.getElementById('entrega-archivo').files[0];
            const enlace = document.getElementById('entrega-enlace').value;
            const errorElement = document.getElementById('entrega-error');
            
            if (!archivo && !enlace) {
                errorElement.classList.remove('hidden');
                return;
            }
            
            errorElement.classList.add('hidden');
            document.getElementById('entregaForm').submit();
        }

        function toggleCalificarModal(tareaId = null, tareaTitulo = null) {
            const modal = document.getElementById('calificarModal');
            const content = document.getElementById('calificarContent');
            const tituloElement = document.getElementById('tarea-titulo');
            
            if (!tareaId) {
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
                return;
            }
            
            // Mostrar mensaje de carga
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            content.innerHTML = '<p class="text-center text-gray-500">Cargando entregas...</p>';
            
            if (tareaTitulo) {
                tituloElement.textContent = tareaTitulo;
            }
            
            // Obtener las entregas
            fetch(`/tareas/${tareaId}/entregas`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Error HTTP:', response.status, response.statusText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data); // Para depuración
                
                if (!data.success) {
                    throw new Error(data.error || 'Error desconocido');
                }
                
                const entregas = data.entregas;
                const tarea = data.tarea;
                const estadisticas = data.estadisticas;
                
                // Si no hay entregas
                if (entregas.length === 0) {
                    content.innerHTML = `
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        No hay entregas para calificar en esta tarea.
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    return;
                }
                
                // Resumen de entregas
                let htmlContent = `
                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-4">
                        <div>
                            <p class="text-sm font-semibold">Resumen de Entregas</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                Entregas totales: ${estadisticas.total_entregas} | 
                                Calificadas: ${estadisticas.entregas_calificadas} | 
                                Pendientes: ${estadisticas.entregas_pendientes}
                            </p>
                        </div>
                    </div>
                `;
                
                // Listar entregas
                entregas.forEach(entrega => {
                    const calificacionHTML = entrega.esta_calificada ? 
                        `<span class="text-green-600">${entrega.calificacion}/${tarea.puntos_maximos}</span>` : 
                        '<span class="text-yellow-600">Pendiente</span>';
                    
                    const entregadaATiempo = entrega.entregada_a_tiempo ? 
                        '<span class="text-green-600">✓ A tiempo</span>' : 
                        '<span class="text-red-600">✗ Tarde</span>';
                    
                    let archivoHTML = '';
                    if (entrega.archivo) {
                        archivoHTML = `
                            <p class="text-sm mb-1">
                                <strong>Archivo:</strong> 
                                <a href="${entrega.archivo_url}" target="_blank" class="text-blue-500 hover:underline">
                                    ${entrega.archivo_nombre || 'Ver archivo'}
                                </a>
                            </p>
                        `;
                    }
                    
                    let enlaceHTML = '';
                    if (entrega.enlace) {
                        enlaceHTML = `
                            <p class="text-sm mb-1">
                                <strong>Enlace:</strong> 
                                <a href="${entrega.enlace}" target="_blank" class="text-blue-500 hover:underline">
                                    ${entrega.enlace}
                                </a>
                            </p>
                        `;
                    }
                    
                    htmlContent += `
                        <div class="border dark:border-gray-600 rounded-md p-4 mb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold">${entrega.user.name}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">${entrega.user.email}</p>
                                    <p class="text-xs mt-1">
                                        Entregada: ${new Date(entrega.fecha_entrega).toLocaleString()} 
                                        ${entregadaATiempo}
                                    </p>
                                </div>
                                <div>
                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full text-sm">
                                        Calificación: ${calificacionHTML}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                ${archivoHTML}
                                ${enlaceHTML}
                            </div>
                            
                            <form id="form-calificar-${entrega.id}" class="mt-4 border-t pt-3 dark:border-gray-600">
                                <div class="flex items-center">
                                    <label class="block text-sm font-medium mr-2">Calificación:</label>
                                    <input type="number" 
                                           id="calificacion-${entrega.id}" 
                                           value="${entrega.calificacion || ''}" 
                                           min="0" 
                                           max="${tarea.puntos_maximos}" 
                                           step="0.1"
                                           required
                                           class="w-20 rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-1">/ ${tarea.puntos_maximos}</span>
                                </div>
                                
                                <div class="mt-2">
                                    <label class="block text-sm font-medium mb-1">Comentarios:</label>
                                    <textarea id="comentarios-${entrega.id}" 
                                              class="w-full rounded-md border-gray-300 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500"
                                              rows="2">${entrega.comentarios || ''}</textarea>
                                </div>
                                
                                <div class="flex justify-end mt-2">
                                    <button type="button"
                                            onclick="calificarEntrega(${tarea.id}, ${entrega.id})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition">
                                        Guardar Calificación
                                    </button>
                                </div>
                            </form>
                        </div>
                    `;
                });
                
                content.innerHTML = htmlContent;
            })
            .catch(error => {
                console.error('Error al cargar entregas:', error);
                content.innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Error al cargar las entregas: ${error.message}
                                </p>
                                <div class="mt-2">
                                    <button type="button" 
                                            onclick="toggleCalificarModal(${tareaId}, '${tareaTitulo}')" 
                                            class="text-red-700 hover:text-red-600 underline text-sm">
                                        Intentar nuevamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        function calificarEntrega(tareaId, entregaId) {
            const calificacion = document.getElementById(`calificacion-${entregaId}`).value;
            const comentarios = document.getElementById(`comentarios-${entregaId}`).value;
            
            if (!calificacion) {
                alert('La calificación es obligatoria');
                return;
            }
            
            // Mostrar estado de carga
            const botonCalificar = document.querySelector(`#form-calificar-${entregaId} button`);
            const textoOriginal = botonCalificar.innerHTML;
            botonCalificar.disabled = true;
            botonCalificar.innerHTML = 'Guardando...';
            
            fetch(`/tareas/${tareaId}/entregas/${entregaId}/calificar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ calificacion, comentarios })
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Error HTTP:', response.status, response.statusText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta calificación:', data); // Para depuración
                
                if (data.success) {
                    // Actualizar el indicador de calificación en la UI
                    const calificacionIndicador = document.querySelector(`#form-calificar-${entregaId}`).closest('.border').querySelector('.bg-gray-100 span');
                    calificacionIndicador.innerHTML = `<span class="text-green-600">${data.data.calificacion}/${data.data.puntos_maximos}</span>`;
                    
                    // Mensaje de éxito
                    const successElement = document.createElement('div');
                    successElement.className = 'bg-green-100 text-green-700 p-2 rounded mt-2 text-sm text-center';
                    successElement.textContent = 'Calificación guardada correctamente';
                    document.querySelector(`#form-calificar-${entregaId}`).appendChild(successElement);
                    
                    // Eliminar mensaje después de 3 segundos
                    setTimeout(() => {
                        successElement.remove();
                    }, 3000);
                } else {
                    alert(data.error || 'Error al guardar la calificación');
                }
            })
            .catch(error => {
                console.error('Error al calificar:', error);
                alert(`Error: ${error.message}`);
            })
            .finally(() => {
                // Restaurar el botón
                botonCalificar.disabled = false;
                botonCalificar.innerHTML = textoOriginal;
            });
        }
    </script>
    @endpush
</x-app-layout>
