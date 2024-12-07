<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
            {{ __('Crear Curso') }} 🎓
        </h2>
    </x-slot>

    <head>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('cursos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre 🏷️
                            </label>
                            <input type="text" name="nombre" id="nombre" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Precio 💲
                            </label>
                            <input type="number" name="precio" id="precio" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Descripción 📝
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Estado 🏆
                            </label>
                            <select name="estado" id="estado" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div>
                            <label for="tipo_curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo de Curso 💼
                            </label>
                            <select name="tipo_curso_id" id="tipo_curso_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                                @foreach ($tiposCursos as $tipoCurso)
                                    <option value="{{ $tipoCurso->id }}">{{ $tipoCurso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="imagen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Imagen 🖼️
                        </label>
                        <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        <i class="fas fa-book w-12 h-12 text-gray-500 dark:text-gray-300 mt-2"></i>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 active:bg-blue-700 dark:active:bg-blue-600 disabled:opacity-25 transition">
                            💾 Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
