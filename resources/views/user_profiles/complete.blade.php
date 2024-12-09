<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Completa tu Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-500 text-white p-4 rounded mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('profile.storeComplete') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Required Fields -->
                            <div class="form-group">
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📞 Teléfono <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('phone', $profile->phone ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🎂 Fecha de Nacimiento <span class="text-red-500">*</span></label>
                                <input type="date" name="birth_date" id="birth_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('birth_date', $profile->birth_date ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">⚧ Género <span class="text-red-500">*</span></label>
                                <select name="gender" id="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" required>
                                    <option value="Masculino" {{ (old('gender', $profile->gender ?? '') == 'Masculino') ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ (old('gender', $profile->gender ?? '') == 'Femenino') ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ (old('gender', $profile->gender ?? '') == 'Otro') ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cedula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🆔 Cédula <span class="text-red-500">*</span></label>
                                <input type="text" name="cedula" id="cedula" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('cedula', $profile->cedula ?? '') }}" required>
                            </div>
                            
                            <!-- Optional Fields -->
                            <div class="form-group">
                                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📸 Foto (Opcional)</label>
                                <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" accept="image/jpeg,image/png" onchange="uploadFile('photo')">
                                @if(isset($profile->photo))
                                    <img src="{{ asset('storage/' . $profile->photo) }}" alt="Profile Photo" class="mt-2 w-32 h-32 rounded-full">
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="direccion_calle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🏠 Dirección Calle (Opcional)</label>
                                <input type="text" name="direccion_calle" id="direccion_calle" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('direccion_calle', $profile->direccion_calle ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="direccion_ciudad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🌆 Dirección Ciudad (Opcional)</label>
                                <input type="text" name="direccion_ciudad" id="direccion_ciudad" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('direccion_ciudad', $profile->direccion_ciudad ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="direccion_provincia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📍 Dirección Provincia (Opcional)</label>
                                <input type="text" name="direccion_provincia" id="direccion_provincia" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('direccion_provincia', $profile->direccion_provincia ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="codigo_postal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📮 Código Postal (Opcional)</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('codigo_postal', $profile->codigo_postal ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="numero_referencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">🔢 Número de Referencia (Opcional)</label>
                                <input type="text" name="numero_referencia" id="numero_referencia" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" value="{{ old('numero_referencia', $profile->numero_referencia ?? '') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="estado_academico_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado Académico</label>
                            <select name="estado_academico_id" id="estado_academico_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300">
                                @foreach($estadosAcademicos as $estado)
                                    <option value="{{ $estado->id }}" {{ (old('estado_academico_id', $profile->estado_academico_id ?? '') == $estado->id) ? 'selected' : '' }}>{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="acta_grado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Acta de Grado (Opcional)</label>
                            <input type="file" name="acta_grado" id="acta_grado" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300" accept="application/pdf,image/jpeg,image/png" onchange="uploadFile('acta_grado')">
                            @php
                                $userAcademico = \App\Models\UserAcademico::where('user_id', Auth::user()->id)->first();
                            @endphp
                            @if(isset($userAcademico->acta_grado))
                                <a href="{{ asset('storage/' . $userAcademico->acta_grado) }}" target="_blank" class="mt-2 text-blue-500 dark:text-blue-300">Ver Acta de Grado</a>
                            @endif  
                        </div>
                        <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function uploadFile(inputId) {
        const input = document.getElementById(inputId);
        const formData = new FormData();
        formData.append(inputId, input.files[0]);

        fetch('{{ route('profile.storeComplete') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                console.error('Error al subir el archivo:', data);
            }
        })
        .catch(error => {
            console.error('Error al subir el archivo:', error);
        });
    }
</script>