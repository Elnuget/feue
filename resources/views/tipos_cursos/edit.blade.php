<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Tipo de Curso') }}
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('tipos_cursos.update', $tipoCurso) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{ $tipoCurso->nombre }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">{{ $tipoCurso->descripcion }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 active:bg-blue-700 dark:active:bg-blue-600 disabled:opacity-25 transition">
                            Actualizar
                        </button>
                        <a href="{{ route('tipos_cursos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring focus:ring-gray-200 dark:focus:ring-gray-500 active:bg-gray-700 dark:active:bg-gray-600 disabled:opacity-25 transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>