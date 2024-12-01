<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Documento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('documentos.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="tipo_documento" class="block text-gray-700">Tipo de Documento</label>
                            <select name="tipo_documento" id="tipo_documento" class="form-select mt-1 block w-full">
                                <option value="Foto">Foto</option>
                                <option value="Acta de Grado">Acta de Grado</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="ruta" class="block text-gray-700">Ruta</label>
                            <input type="text" name="ruta" id="ruta" class="form-input mt-1 block w-full" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>