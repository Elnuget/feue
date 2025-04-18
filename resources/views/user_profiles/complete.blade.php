<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjeta contenedora -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg transition duration-300 ease-in-out hover:shadow-lg"
            >
                <!-- Encabezado o título (opcional) -->
                <div class="px-6 pt-6 pb-2">
                    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                        Completa tu perfil
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Por favor, ingresa o actualiza la información de tu perfil.
                    </p>
                </div>

                <div class="px-6 pb-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <!-- Mensaje de éxito -->
                    @if(session('success'))
                        <div
                            class="bg-green-50 border-l-4 border-green-400 p-4 rounded mb-4 text-green-800 dark:bg-green-500/10 dark:border-green-400 dark:text-green-300 transition duration-300"
                        >
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Mensajes de error -->
                    @if($errors->any())
                        <div
                            class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-4 text-red-800 dark:bg-red-500/10 dark:border-red-400 dark:text-red-300 transition duration-300"
                        >
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form
                        action="{{ route('profile.storeComplete') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6"
                    >
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                        <!-- Campos en cuadrícula -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Teléfono -->
                            <div>
                                <label
                                    for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    📞 Teléfono <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    id="phone"
                                    placeholder="Ingresa tu número de teléfono"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('phone', $profile->phone ?? '') }}"
                                    required
                                >
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label
                                    for="birth_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    🎂 Fecha de Nacimiento <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="birth_date"
                                    id="birth_date"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('birth_date', $profile->birth_date ?? '') }}"
                                    required
                                >
                            </div>

                            <!-- Género -->
                            <div>
                                <label
                                    for="gender"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    ⚧ Género <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="gender"
                                    id="gender"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    required
                                >
                                    <option value="Masculino" {{ (old('gender', $profile->gender ?? '') == 'Masculino') ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ (old('gender', $profile->gender ?? '') == 'Femenino') ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ (old('gender', $profile->gender ?? '') == 'Otro') ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <!-- Cédula -->
                            <div>
                                <label
                                    for="cedula"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    🆔 Cédula <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="cedula"
                                    id="cedula"
                                    placeholder="Ingresa tu número de cédula"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('cedula', $profile->cedula ?? '') }}"
                                    required
                                >
                            </div>

                            <!-- Foto -->
                            <div>
                                <label
                                    for="photo"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    📸 Foto <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="file"
                                        name="photo"
                                        id="photo"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp"
                                        onchange="handleFileSelect(this)"
                                        @if(!isset($profile->photo)) required @endif
                                    >
                                    <p class="mt-1 text-sm text-gray-500">
                                        Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP, BMP. Tamaño máximo: 10MB.
                                    </p>
                                    @if(isset($profile->photo))
                                        <div class="mt-2">
                                            <img
                                                src="{{ Storage::disk('public')->exists($profile->photo) 
                                                    ? Storage::url($profile->photo) 
                                                    : asset('default-profile.png') }}"
                                                alt="Profile Photo"
                                                class="mt-2 w-32 h-32 rounded-full object-cover shadow"
                                            >
                                        </div>
                                    @endif
                                </div>
                                <div id="fileError" class="mt-2 text-sm text-red-600 hidden"></div>
                                <div id="filePreview" class="mt-2 hidden">
                                    <img id="imagePreview" class="max-w-xs hidden" alt="Vista previa">
                                </div>
                            </div>

                            <!-- Dirección Calle -->
                            <div>
                                <label
                                    for="direccion_calle"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    🏠 Dirección Calle (Opcional)
                                </label>
                                <input
                                    type="text"
                                    name="direccion_calle"
                                    id="direccion_calle"
                                    placeholder="Ej. Av. Siempre Viva #742"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('direccion_calle', $profile->direccion_calle ?? '') }}"
                                >
                            </div>

                            <!-- Dirección Ciudad -->
                            <div>
                                <label
                                    for="direccion_ciudad"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    🌆 Dirección Ciudad (Opcional)
                                </label>
                                <input
                                    type="text"
                                    name="direccion_ciudad"
                                    id="direccion_ciudad"
                                    placeholder="Ej. Quito, Ciudad de México, etc."
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('direccion_ciudad', $profile->direccion_ciudad ?? '') }}"
                                >
                            </div>

                            <!-- Dirección Provincia -->
                            <div>
                                <label
                                    for="direccion_provincia"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    📍 Dirección Provincia (Opcional)
                                </label>
                                <input
                                    type="text"
                                    name="direccion_provincia"
                                    id="direccion_provincia"
                                    placeholder="Ej. Pichincha, Jalisco, etc."
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('direccion_provincia', $profile->direccion_provincia ?? '') }}"
                                >
                            </div>

                            <!-- Código Postal -->
                            <div>
                                <label
                                    for="codigo_postal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    📮 Código Postal (Opcional)
                                </label>
                                <input
                                    type="text"
                                    name="codigo_postal"
                                    id="codigo_postal"
                                    placeholder="Ej. 170520, 03521, etc."
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                    value="{{ old('codigo_postal', $profile->codigo_postal ?? '') }}"
                                >
                            </div>
                        </div>

                        <!-- Estado Académico -->
                        <div>
                            <label
                                for="estado_academico_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                🎓 Estado Académico <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="estado_academico_id"
                                id="estado_academico_id"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-150"
                                required
                            >
                                <option value="">Seleccione un estado académico</option>
                                @foreach($estadosAcademicos as $estado)
                                    <option 
                                        value="{{ $estado->id }}"
                                        {{ (old('estado_academico_id', $profile->estadoAcademico->estado_academico_id ?? '') == $estado->id) ? 'selected' : '' }}
                                    >
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botón Guardar -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-800 transition"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function handleFileSelect(input) {
        const fileError = document.getElementById('fileError');
        const filePreview = document.getElementById('filePreview');
        const imagePreview = document.getElementById('imagePreview');

        fileError.classList.add('hidden');
        imagePreview.classList.add('hidden');
        filePreview.classList.add('hidden');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validar tamaño (máximo 10MB)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                fileError.textContent = 'El archivo es demasiado grande. Máximo 10MB permitido.';
                fileError.classList.remove('hidden');
                input.value = '';
                return;
            }

            // Validar tipo de archivo
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
            if (!validTypes.includes(file.type)) {
                fileError.textContent = 'Tipo de archivo no válido. Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP, BMP.';
                fileError.classList.remove('hidden');
                input.value = '';
                return;
            }

            // Mostrar vista previa
            filePreview.classList.remove('hidden');
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);

            // Preparar y enviar el archivo
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('profile.storeComplete') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al subir la imagen');
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Error al procesar la imagen');
                }
            })
            .catch(error => {
                fileError.textContent = error.message;
                fileError.classList.remove('hidden');
            });
        }
    }
</script>
