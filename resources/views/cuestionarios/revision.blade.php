<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Revisión: {{ $cuestionario->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Resultado del intento</h3>
                        <p class="text-gray-600">Calificación: {{ number_format($intento->calificacion, 2) }}%</p>
                        <p class="text-gray-600">
                            Fecha: 
                            @if($intento->fin)
                                {{ Carbon\Carbon::parse($intento->fin)->format('d/m/Y H:i') }}
                            @else
                                No finalizado
                            @endif
                        </p>
                    </div>

                    <div class="space-y-6">
                        @foreach($cuestionario->preguntas as $pregunta)
                            <div class="border rounded-lg p-4">
                                <h4 class="font-semibold mb-2">{{ $pregunta->pregunta }}</h4>
                                
                                @php
                                    $respuestaUsuario = $intento->respuestas()
                                        ->where('pregunta_id', $pregunta->id)
                                        ->first();
                                @endphp

                                <div class="space-y-2">
                                    @foreach($pregunta->opciones as $opcion)
                                        <div class="flex items-center p-2 rounded-lg
                                            @if($opcion->es_correcta) bg-green-50 @endif
                                            @if($respuestaUsuario && $respuestaUsuario->opcion_id === $opcion->id && !$opcion->es_correcta) bg-red-50 @endif">
                                            <span class="mr-2">
                                                @if($respuestaUsuario && $respuestaUsuario->opcion_id === $opcion->id)
                                                    @if($opcion->es_correcta)
                                                        ✅
                                                    @else
                                                        ❌
                                                    @endif
                                                @endif
                                            </span>
                                            {{ $opcion->texto }}
                                            @if($opcion->es_correcta)
                                                <span class="ml-2 text-green-600 text-sm">(Respuesta correcta)</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('aulas_virtuales.show', $cuestionario->aulaVirtual) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver al aula virtual
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 