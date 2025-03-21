<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Certificado') }}
            </h2>
            <a href="{{ route('certificados.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Volver') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('certificados.update', $certificado) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Campo oculto para usuario_id -->
                        <input type="hidden" name="usuario_id" value="{{ $certificado->usuario_id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="numero_certificado" :value="__('Número de Certificado')" />
                                <x-text-input id="numero_certificado" name="numero_certificado" type="text" class="mt-1 block w-full" 
                                    :value="old('numero_certificado', $certificado->numero_certificado)" required />
                            </div>

                            <div>
                                <x-input-label for="nombre_completo" :value="__('Nombre Completo')" />
                                <x-text-input id="nombre_completo" name="nombre_completo" type="text" class="mt-1 block w-full" 
                                    :value="old('nombre_completo', $certificado->nombre_completo)" required />
                            </div>

                            <div>
                                <x-input-label for="nombre_curso" :value="__('Nombre del Curso')" />
                                <x-text-input id="nombre_curso" name="nombre_curso" type="text" class="mt-1 block w-full" 
                                    :value="old('nombre_curso', $certificado->nombre_curso)" required />
                            </div>

                            <div>
                                <x-input-label for="horas_curso" :value="__('Horas del Curso')" />
                                <x-text-input id="horas_curso" name="horas_curso" type="number" class="mt-1 block w-full" 
                                    :value="old('horas_curso', $certificado->horas_curso)" required />
                            </div>

                            <div>
                                <x-input-label for="sede_curso" :value="__('Sede del Curso')" />
                                <x-text-input id="sede_curso" name="sede_curso" type="text" class="mt-1 block w-full" 
                                    :value="old('sede_curso', $certificado->sede_curso)" required />
                            </div>

                            <div>
                                <x-input-label for="estado" :value="__('Estado')" />
                                <select id="estado" name="estado" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                    <option value="1" {{ old('estado', $certificado->estado) ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ !old('estado', $certificado->estado) ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="fecha_emision" :value="__('Fecha de Emisión')" />
                                <x-text-input id="fecha_emision" name="fecha_emision" type="date" class="mt-1 block w-full" 
                                    :value="old('fecha_emision', $certificado->fecha_emision->format('Y-m-d'))" required />
                            </div>

                            <div>
                                <x-input-label for="anio_emision" :value="__('Año de Emisión (2 dígitos)')" />
                                <x-text-input id="anio_emision" name="anio_emision" type="text" class="mt-1 block w-full" 
                                    :value="old('anio_emision', $certificado->anio_emision)" required maxlength="2" />
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="observaciones" :value="__('Observaciones')" />
                                <textarea id="observaciones" name="observaciones" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">{{ old('observaciones', $certificado->observaciones) }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Actualizar Certificado') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 