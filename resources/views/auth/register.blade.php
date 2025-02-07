<x-guest-layout>
    @section('page_title', 'Register')
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- CEDULA Y VERIFICACIÓN -->
        <div>
            <x-input-label for="cedula" :value="__('Cédula')"/> 🆔
            <p class="text-xs text-gray-500 mt-1">
                La cédula debe no estar registrada en el sistema para poder registrarse. Si ya tienes una cuenta, puedes <a href="{{ route('login') }}" class="underline text-indigo-600 hover:text-indigo-900">iniciar sesión</a>.
            </p>
            <p class="text-xs text-gray-500 mt-1">
                Los campos de registro se habilitarán una vez que verifiques tu cédula.
            </p>
            <div class="flex">
                <x-text-input id="cedula" class="block mat-1 w-full" type="text" name="cedula" 
                              :value="old('cedula')" required autocomplete="cedula" />
                <button type="button" id="verify-cedula" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded">
                    Verificar
                </button>
            </div>
            <div id="cedula-feedback" class="mt-2 text-sm"></div>
            <x-input-error :messages="$errors->get('cedula')" class="mt-2" />
        </div>

        <!-- APELLIDOS Y NOMBRES -->
        <div>
            <x-input-label for="name" :value="__('APELLIDOS Y NOMBRES')"/> 📝
            <!-- Aplicamos text-transform: uppercase para mostrar el texto en mayúsculas -->
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                          :value="old('name')" required autofocus autocomplete="name" 
                          style="text-transform: uppercase;" disabled/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Correo Electrónico -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Electrónico')"/> 📧
            <x-text-input id="email" class="block mt-1 w-full" 
                          type="email" name="email" 
                          :value="old('email')" required autocomplete="username" disabled/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Celular -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Celular')"/> 📱
            <x-text-input id="phone" class="block mt-1 w-full"
                          type="text"
                          name="phone"
                          :value="old('phone')" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')"/> 🔒
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" disabled/>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar Contraseña -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')"/> 🔒
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" disabled/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Campos para registrar matrícula -->
        <div class="mt-4">
            <x-input-label for="tipo_curso_id" :value="__('Tipo de Curso')"/> 📚
            <select name="tipo_curso_id" id="tipo_curso_id" required class="block mt-1 w-full">
                <option value="">Selecciona un Tipo de Curso</option>
                @foreach($tiposCursos as $tipoCurso)
                    <option value="{{ $tipoCurso->id }}">{{ $tipoCurso->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <x-input-label for="curso_id" :value="__('Curso')"/> 📖
            <select name="curso_id" id="curso_id" required class="block mt-1 w-full">
                <option value="">Selecciona un Curso</option>
            </select>
        </div>

        <div class="mt-4">
            <x-input-label for="fecha_matricula" :value="__('Fecha de Matrícula')"/> 📅
            <x-text-input id="fecha_matricula" class="block mt-1 w-full" type="date" name="fecha_matricula" 
                          value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required />
        </div>

        <div class="mt-4">
            <x-input-label for="monto_total" :value="__('Monto Total')"/> 💰
            <x-text-input id="monto_total" class="block mt-1 w-full" type="number" name="monto_total" 
                          required readonly placeholder="Selecciona un curso" />
        </div>

        <!-- Campos para registrar pago -->
        <div class="mt-4">
            <x-input-label for="metodo_pago_id" :value="__('Método de Pago')"/> 💳
            <select name="metodo_pago_id" id="metodo_pago_id" required class="block mt-1 w-full">
                @foreach($metodosPago as $metodo)
                    <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <x-input-label for="monto" :value="__('Monto de Pago')"/> 💵
            <x-text-input id="monto" class="block mt-1 w-full" type="number" name="monto" 
                          value="0" step="0.01" placeholder="Monto de pago" required readonly />
        </div>

        <div class="mt-4">
            <x-input-label for="fecha_pago" :value="__('Fecha de Pago')"/> 📅
            <x-text-input id="fecha_pago" class="block mt-1 w-full" type="date" name="fecha_pago" 
                          value="{{ now()->timezone('America/Guayaquil')->toDateString() }}" required />
        </div>

        <div class="col-span-1 md:col-span-2" id="comprobante_pago_container">
            <x-input-label for="comprobante_pago" :value="__('Comprobante de Pago')"/> 📎
            <div class="mt-1 flex items-center">
                <input type="file" name="comprobante_pago" id="comprobante_pago"
                       accept=".png, .jpg, .jpeg, .pdf"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100"
                       onchange="handleFileSelect(this)">
            </div>
            <div id="fileError" class="mt-2 text-sm text-red-600 hidden"></div>
            <div id="filePreview" class="mt-2 hidden">
                <img id="imagePreview" class="max-w-xs hidden" alt="Vista previa">
                <p id="pdfPreview" class="text-sm text-gray-600 hidden">
                    Archivo PDF seleccionado
                </p>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4" disabled>
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Toast de notificación: ahora verifica session('error') o errores de validación -->
    @if(session('status') || session('error') || $errors->any())
    <div id="toast" data-type="{{ session('status') ? 'status' : 'error' }}" class="fixed bottom-5 right-5 px-4 py-2 rounded shadow-lg
         {{ session('status') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
        {{-- Muestra el mensaje flash o el primer error de validación --}}
        {{ session('status') ?? session('error') ?? $errors->first() }}
    </div>
    @endif

    <!-- Script para verificar cédula y controlar el estado de los campos -->
    <script>
        // Ocultar el toast automáticamente después de 5 segundos
        const toast = document.getElementById('toast');
        if (toast) {
            const toastType = toast.getAttribute('data-type');
            // Si es error, mantener el mensaje visible 10 seg; si no, 5 seg.
            const timeoutDuration = toastType === 'error' ? 10000 : 5000;
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, timeoutDuration);
        }

        /**
         * Función para validar una cédula ecuatoriana.
         * Verifica que la cédula tenga 10 dígitos, que el código de provincia sea válido,
         * que el tercer dígito sea menor que 6 y que el dígito verificador sea correcto.
         *
         * @param {string} cedula - La cédula a validar.
         * @return {boolean} - Retorna true si la cédula es válida, de lo contrario false.
         */
        function validarCedulaEcuatoriana(cedula) {
            if (!/^\d{10}$/.test(cedula)) {
                return false;
            }

            const provincia = parseInt(cedula.substring(0, 2), 10);
            if (provincia < 1 || provincia > 24) {
                return false;
            }

            const tercerDigito = parseInt(cedula.charAt(2), 10);
            if (tercerDigito >= 6) {
                return false;
            }

            let suma = 0;
            for (let i = 0; i < 9; i++) {
                let num = parseInt(cedula.charAt(i), 10);
                num = (i % 2 === 0) ? num * 2 : num;
                if (num > 9) {
                    num -= 9;
                }
                suma += num;
            }

            const digitoVerificador = parseInt(cedula.charAt(9), 10);
            const decenaSuperior = Math.ceil(suma / 10) * 10;
            const digitoCalculado = decenaSuperior - suma;

            return digitoCalculado === digitoVerificador;
        }

        document.getElementById('verify-cedula').addEventListener('click', function() {
            const cedula = document.getElementById('cedula').value.trim();
            const feedback = document.getElementById('cedula-feedback');
            const formFields = document.querySelectorAll('input:not([name="cedula"])');
            const submitButton = document.querySelector('button[type="submit"]');

            // Reset feedback
            feedback.textContent = '';
            feedback.classList.remove('text-red-500', 'text-green-500');

            if (!cedula) {
                feedback.textContent = 'Por favor, ingresa una cédula.';
                feedback.classList.add('text-red-500');
                formFields.forEach(field => field.disabled = true);
                submitButton.disabled = true;
                return;
            }

            if (!validarCedulaEcuatoriana(cedula)) {
                feedback.textContent = 'La cédula ingresada no es válida.';
                feedback.classList.add('text-red-500');
                formFields.forEach(field => field.disabled = true);
                submitButton.disabled = true;
                return;
            }

            // Si la cédula es válida, proceder a verificar su disponibilidad en el servidor
            fetch('{{ route('user_profiles.checkCedula') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ cedula: cedula })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    feedback.textContent = 'La cédula ya está registrada.';
                    feedback.classList.add('text-red-500');
                    formFields.forEach(field => field.disabled = true);
                    submitButton.disabled = true;
                } else {
                    feedback.textContent = 'La cédula está disponible.';
                    feedback.classList.add('text-green-500');
                    formFields.forEach(field => field.disabled = false);
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                feedback.textContent = 'Error al verificar la cédula.';
                feedback.classList.add('text-red-500');
                formFields.forEach(field => field.disabled = true);
                submitButton.disabled = true;
            });
        });

        document.getElementById('tipo_curso_id').addEventListener('change', function() {
            const tipoCursoId = this.value;
            const cursoSelect = document.getElementById('curso_id');
            cursoSelect.innerHTML = '<option value="">Selecciona un Curso</option>'; // Reset con opción por defecto

            if (tipoCursoId) {
                const cursosPorTipo = @json($cursosPorTipo);
                if (cursosPorTipo[tipoCursoId]) {
                    const cursosOrdenados = cursosPorTipo[tipoCursoId].sort((a, b) =>
                        a.nombre.localeCompare(b.nombre)
                    );
                    cursosOrdenados.forEach(curso => {
                        if (curso.estado === 'Activo') {
                            const option = new Option(
                                `${curso.nombre} - $${parseFloat(curso.precio).toFixed(2)}`,
                                curso.id
                            );
                            option.dataset.precio = curso.precio;
                            cursoSelect.add(option);
                        }
                    });
                    if (cursoSelect.options.length > 1) { // Si hay más de la opción por defecto
                        const primerCurso = cursosPorTipo[tipoCursoId].find(c => c.estado === 'Activo');
                        if (primerCurso) {
                            const precio = parseFloat(primerCurso.precio).toFixed(2);
                            document.getElementById('monto_total').value = precio;
                            document.getElementById('monto').value = precio;
                        }
                    } else {
                        document.getElementById('monto_total').value = '0.00';
                        document.getElementById('monto').value = '0.00';
                    }
                } else {
                    document.getElementById('monto_total').value = '0.00';
                    document.getElementById('monto').value = '0.00';
                }
            } else {
                document.getElementById('monto_total').value = '0.00';
                document.getElementById('monto').value = '0.00';
            }
        });

        document.getElementById('curso_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption) {
                const precio = parseFloat(selectedOption.getAttribute('data-precio')).toFixed(2);
                document.getElementById('monto_total').value = precio;
                document.getElementById('monto').value = precio;
            }
        });

        function handleFileSelect(input) {
            const fileError = document.getElementById('fileError');
            const filePreview = document.getElementById('filePreview');
            const imagePreview = document.getElementById('imagePreview');
            const pdfPreview = document.getElementById('pdfPreview');

            fileError.classList.add('hidden');
            imagePreview.classList.add('hidden');
            pdfPreview.classList.add('hidden');
            filePreview.classList.add('hidden');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validar tamaño (máximo 5MB)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    fileError.textContent = 'El archivo es demasiado grande. Máximo 5MB permitido.';
                    fileError.classList.remove('hidden');
                    input.value = '';
                    return;
                }

                // Validar tipo de archivo
                const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (!validTypes.includes(file.type)) {
                    fileError.textContent = 'Tipo de archivo no válido. Solo se permiten JPG, PNG y PDF.';
                    fileError.classList.remove('hidden');
                    input.value = '';
                    return;
                }

                filePreview.classList.remove('hidden');

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    pdfPreview.classList.remove('hidden');
                }
                
                // Habilitar el botón de envío si hay un archivo válido
                document.querySelector('button[type="submit"]').disabled = false;
            }
        }

        // Validación del formulario antes del envío
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('comprobante_pago');
            const fileError = document.getElementById('fileError');
            const metodoPago = document.getElementById('metodo_pago_id');
            const isEfectivo = metodoPago.options[metodoPago.selectedIndex].text.toLowerCase() === 'efectivo';

            // Solo validar si no es efectivo o si se ha seleccionado un archivo
            if (!isEfectivo && (!fileInput.files || fileInput.files.length === 0)) {
                e.preventDefault();
                fileError.textContent = 'Debe subir un comprobante de pago.';
                fileError.classList.remove('hidden');
                return false;
            }
        });

        // Agregar evento para el cambio de método de pago
        document.getElementById('metodo_pago_id').addEventListener('change', function() {
            const comprobanteInput = document.getElementById('comprobante_pago');
            const isEfectivo = this.options[this.selectedIndex].text.toLowerCase() === 'efectivo';
            
            if (isEfectivo) {
                comprobanteInput.removeAttribute('required');
                // Actualizar el texto del label para indicar que es opcional
                document.querySelector('label[for="comprobante_pago"]').textContent = 'Comprobante de Pago (Opcional)';
            } else {
                comprobanteInput.setAttribute('required', 'required');
                document.querySelector('label[for="comprobante_pago"]').textContent = 'Comprobante de Pago';
            }
        });
    </script>
</x-guest-layout>
