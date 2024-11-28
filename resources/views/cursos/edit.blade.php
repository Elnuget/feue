
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Curso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('cursos.update', $curso) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ $curso->nombre }}" required>
                    </div>
                    <div>
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion">{{ $curso->descripcion }}</textarea>
                    </div>
                    <div>
                        <label for="precio">Precio</label>
                        <input type="number" name="precio" id="precio" step="0.01" value="{{ $curso->precio }}" required>
                    </div>
                    <div>
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" required>
                            <option value="Activo" {{ $curso->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ $curso->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div>
                        <label for="tipo_curso_id">Tipo de Curso</label>
                        <select name="tipo_curso_id" id="tipo_curso_id" required>
                            @foreach ($tiposCursos as $tipoCurso)
                                <option value="{{ $tipoCurso->id }}" {{ $curso->tipo_curso_id == $tipoCurso->id ? 'selected' : '' }}>{{ $tipoCurso->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>