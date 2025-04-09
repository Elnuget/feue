<x-guest-layout>
    @section('page_title', 'Register')
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registration-form">
        @csrf

        <!-- Indicador de progreso -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="step-indicator active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-title">Verificación</div>
                </div>
                <div class="step-indicator" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-title">Datos Personales</div>
                </div>
                <div class="step-indicator" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-title">Matrícula</div>
                </div>
                <div class="step-indicator" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-title">Pago</div>
                </div>
            </div>
            <div class="progress-bar mt-2">
                <div class="progress-fill" style="width: 25%"></div>
            </div>
        </div>

        <!-- PASO 1: VERIFICACIÓN DE CÉDULA -->
        <div class="step-content active" id="step-1">
            <h2 class="text-xl font-bold mb-4">Verificación de Cédula</h2>
            
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
                <!-- Checkbox para extranjeros -->
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="es_extranjero" name="es_extranjero" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Soy extranjero (usar documento de identidad extranjero)</span>
                    </label>
                </div>
                <div id="cedula-feedback" class="mt-2 text-sm"></div>
                <x-input-error :messages="$errors->get('cedula')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" class="next-step-btn px-4 py-2 bg-blue-500 text-white rounded" data-next="2" disabled>
                    Siguiente
                </button>
            </div>
        </div>

        <!-- PASO 2: DATOS PERSONALES -->
        <div class="step-content hidden" id="step-2">
            <h2 class="text-xl font-bold mb-4">Datos Personales</h2>
            
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

            <div class="flex justify-between mt-6">
                <button type="button" class="prev-step-btn px-4 py-2 bg-gray-300 text-gray-700 rounded" data-prev="1">
                    Anterior
                </button>
                <button type="button" class="next-step-btn px-4 py-2 bg-blue-500 text-white rounded" data-next="3">
                    Siguiente
                </button>
            </div>
        </div>

        <!-- PASO 3: MATRÍCULA -->
        <div class="step-content hidden" id="step-3">
            <h2 class="text-xl font-bold mb-4">Matrícula</h2>
            
            <!-- Campos para registrar matrícula -->
            <div class="mt-4">
                <x-input-label for="tipo_curso_id" :value="__('Sede')"/> 📚
                <select name="tipo_curso_id" id="tipo_curso_id" required class="block mt-1 w-full">
                    <option value="">Selecciona una Sede</option>
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
                <div id="curso_details" class="mt-2 p-3 bg-gray-50 rounded-md text-sm hidden">
                    <div id="curso_nombre" class="font-bold"></div>
                    <div id="curso_precio" class="text-green-600"></div>
                    <div id="curso_horario" class="text-gray-700"></div>
                </div>
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

            <div class="flex justify-between mt-6">
                <button type="button" class="prev-step-btn px-4 py-2 bg-gray-300 text-gray-700 rounded" data-prev="2">
                    Anterior
                </button>
                <button type="button" class="next-step-btn px-4 py-2 bg-blue-500 text-white rounded" data-next="4">
                    Siguiente
                </button>
            </div>
        </div>

        <!-- PASO 4: PAGO -->
        <div class="step-content hidden" id="step-4">
            <h2 class="text-xl font-bold mb-4">Pago</h2>
            
            <!-- Tipo de Pago -->
            <div class="mt-4">
                <x-input-label for="tipo_pago" :value="__('Tipo de Pago')"/> 💳
                <select name="tipo_pago" id="tipo_pago" class="block mt-1 w-full" onchange="handleTipoPagoChange()">
                    <option value="">Selecciona un tipo de pago</option>
                    <option value="Pago Único">Pago Único</option>
                    <option value="Mensual">Mensual</option>
                </select>
            </div>

            <!-- Mensaje para pago mensual -->
            <div id="mensaje_pago_mensual" class="mt-2 p-3 bg-blue-50 text-blue-700 rounded-md text-sm hidden">
                <p>Para pagos mensuales, por favor introduce el valor que pagarás mensualmente.</p>
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
                              value="0" step="0.01" placeholder="Monto de pago" required />
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

            <div class="flex justify-between mt-6">
                <button type="button" class="prev-step-btn px-4 py-2 bg-gray-300 text-gray-700 rounded" data-prev="3">
                    Anterior
                </button>
                <div class="flex items-center">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4" href="{{ route('login') }}">
                        {{ __('¿Ya estás registrado?') }}
                    </a>
                    <x-primary-button class="ms-4" disabled>
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </div>
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

        // Funciones para la navegación por pasos
        document.querySelectorAll('.next-step-btn').forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = parseInt(this.getAttribute('data-next')) - 1;
                const nextStep = parseInt(this.getAttribute('data-next'));
                
                // Ocultar el paso actual
                document.getElementById(`step-${currentStep}`).classList.add('hidden');
                document.getElementById(`step-${currentStep}`).classList.remove('active');
                
                // Mostrar el siguiente paso
                document.getElementById(`step-${nextStep}`).classList.remove('hidden');
                document.getElementById(`step-${nextStep}`).classList.add('active');
                
                // Actualizar indicadores de progreso
                document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.remove('active');
                document.querySelector(`.step-indicator[data-step="${nextStep}"]`).classList.add('active');
                
                // Actualizar barra de progreso
                const progressFill = document.querySelector('.progress-fill');
                progressFill.style.width = `${(nextStep / 4) * 100}%`;
            });
        });

        document.querySelectorAll('.prev-step-btn').forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = parseInt(this.getAttribute('data-prev')) + 1;
                const prevStep = parseInt(this.getAttribute('data-prev'));
                
                // Ocultar el paso actual
                document.getElementById(`step-${currentStep}`).classList.add('hidden');
                document.getElementById(`step-${currentStep}`).classList.remove('active');
                
                // Mostrar el paso anterior
                document.getElementById(`step-${prevStep}`).classList.remove('hidden');
                document.getElementById(`step-${prevStep}`).classList.add('active');
                
                // Actualizar indicadores de progreso
                document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.remove('active');
                document.querySelector(`.step-indicator[data-step="${prevStep}"]`).classList.add('active');
                
                // Actualizar barra de progreso
                const progressFill = document.querySelector('.progress-fill');
                progressFill.style.width = `${(prevStep / 4) * 100}%`;
            });
        });

        /**
         * Función para manejar el cambio en el tipo de pago
         */
        function handleTipoPagoChange() {
            const tipoPago = document.getElementById('tipo_pago').value;
            const montoInput = document.getElementById('monto');
            const mensajePagoMensual = document.getElementById('mensaje_pago_mensual');
            const montoTotal = document.getElementById('monto_total').value;
            
            if (tipoPago === 'Mensual') {
                // Mostrar mensaje para pago mensual
                mensajePagoMensual.classList.remove('hidden');
                // Establecer monto a 0
                montoInput.value = '0';
                // Habilitar el campo de monto para que el usuario pueda introducir el valor
                montoInput.readOnly = false;
            } else if (tipoPago === 'Pago Único') {
                // Ocultar mensaje
                mensajePagoMensual.classList.add('hidden');
                // Restaurar el valor original del monto (que se establece cuando se selecciona un curso)
                montoInput.value = montoTotal;
                // Habilitar el campo de monto para que el usuario pueda modificarlo
                montoInput.readOnly = false;
            } else {
                // Si no hay tipo de pago seleccionado
                mensajePagoMensual.classList.add('hidden');
                montoInput.value = '0';
                montoInput.readOnly = true;
            }
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

        /**
         * Función para validar un documento de identidad extranjero.
         * Solo verifica que tenga entre 5 y 20 caracteres alfanuméricos.
         *
         * @param {string} documento - El documento a validar.
         * @return {boolean} - Retorna true si el documento es válido, de lo contrario false.
         */
        function validarDocumentoExtranjero(documento) {
            // Permite números y letras, longitud entre 5 y 20 caracteres
            return /^[A-Za-z0-9]{5,20}$/.test(documento);
        }

        document.getElementById('verify-cedula').addEventListener('click', function() {
            const cedula = document.getElementById('cedula').value.trim();
            const esExtranjero = document.getElementById('es_extranjero').checked;
            const feedback = document.getElementById('cedula-feedback');
            const formFields = document.querySelectorAll('input:not([name="cedula"]):not([name="es_extranjero"])');
            const submitButton = document.querySelector('button[type="submit"]');
            const nextStepBtn = document.querySelector('.next-step-btn[data-next="2"]');

            // Reset feedback
            feedback.textContent = '';
            feedback.classList.remove('text-red-500', 'text-green-500');

            if (!cedula) {
                feedback.textContent = 'Por favor, ingresa un documento de identificación.';
                feedback.classList.add('text-red-500');
                formFields.forEach(field => field.disabled = true);
                submitButton.disabled = true;
                nextStepBtn.disabled = true;
                return;
            }

            // Validar según el tipo de documento
            let documentoValido = esExtranjero ? 
                validarDocumentoExtranjero(cedula) : 
                validarCedulaEcuatoriana(cedula);

            if (!documentoValido) {
                feedback.textContent = esExtranjero ? 
                    'El documento de identidad no es válido. Debe tener entre 5 y 20 caracteres alfanuméricos.' : 
                    'La cédula ecuatoriana ingresada no es válida.';
                feedback.classList.add('text-red-500');
                formFields.forEach(field => field.disabled = true);
                submitButton.disabled = true;
                nextStepBtn.disabled = true;
                return;
            }

            // Si el documento es válido, proceder a verificar su disponibilidad en el servidor
            fetch('{{ route('user_profiles.checkCedula') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    cedula: cedula,
                    es_extranjero: esExtranjero
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    feedback.textContent = 'El documento de identificación ya está registrado.';
                    feedback.classList.add('text-red-500');
                    formFields.forEach(field => field.disabled = true);
                    submitButton.disabled = true;
                    nextStepBtn.disabled = true;
                } else {
                    feedback.textContent = 'El documento de identificación está disponible.';
                    feedback.classList.add('text-green-500');
                    formFields.forEach(field => field.disabled = false);
                    submitButton.disabled = false;
                    nextStepBtn.disabled = false;
                }
            })
            .catch(error => {
                feedback.textContent = 'Error al verificar el documento de identificación.';
                feedback.classList.add('text-red-500');
                formFields.forEach(field => field.disabled = true);
                submitButton.disabled = true;
                nextStepBtn.disabled = true;
            });
        });

        document.getElementById('tipo_curso_id').addEventListener('change', function() {
            const tipoCursoId = this.value;
            const cursoSelect = document.getElementById('curso_id');
            cursoSelect.innerHTML = '<option value="">Selecciona un Curso</option>'; // Reset con opción por defecto
            document.getElementById('curso_details').classList.add('hidden');

            if (tipoCursoId) {
                const cursosPorTipo = @json($cursosPorTipo);
                if (cursosPorTipo[tipoCursoId]) {
                    const cursosOrdenados = cursosPorTipo[tipoCursoId].sort((a, b) =>
                        a.nombre.localeCompare(b.nombre)
                    );
                    cursosOrdenados.forEach(curso => {
                        if (curso.estado === 'Activo') {
                            // Creamos opciones más simples para el combobox
                            const option = new Option(curso.nombre, curso.id);
                            option.dataset.precio = curso.precio;
                            option.dataset.horario = curso.horario || 'Horario no disponible';
                            option.dataset.nombre = curso.nombre;
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
            const detallesDiv = document.getElementById('curso_details');
            
            if (selectedOption && selectedOption.value) {
                const precio = parseFloat(selectedOption.getAttribute('data-precio')).toFixed(2);
                document.getElementById('monto_total').value = precio;
                
                // Si el tipo de pago no es mensual, establecer el monto de pago igual al monto total
                const tipoPago = document.getElementById('tipo_pago').value;
                if (tipoPago !== 'Mensual') {
                    document.getElementById('monto').value = precio;
                }
                
                // Actualizar detalles del curso
                document.getElementById('curso_nombre').textContent = selectedOption.getAttribute('data-nombre');
                document.getElementById('curso_precio').textContent = `Precio: $${precio}`;
                document.getElementById('curso_horario').textContent = `Horario: ${selectedOption.getAttribute('data-horario')}`;
                
                detallesDiv.classList.remove('hidden');
            } else {
                detallesDiv.classList.add('hidden');
            }
        });

        function handleFileSelect(input) {
            const fileError = document.getElementById('fileError');
            const filePreview = document.getElementById('filePreview');
            const imagePreview = document.getElementById('imagePreview');
            const pdfPreview = document.getElementById('pdfPreview');
            const submitButton = document.querySelector('button[type="submit"]');

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
                    submitButton.disabled = true;
                    return;
                }

                // Validar tipo de archivo
                const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (!validTypes.includes(file.type)) {
                    fileError.textContent = 'Tipo de archivo no válido. Solo se permiten JPG, PNG y PDF.';
                    fileError.classList.remove('hidden');
                    input.value = '';
                    submitButton.disabled = true;
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
                submitButton.disabled = false;
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

    <style>
        /* Estilos para los indicadores de pasos */
        .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }
        
        .step-indicator:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background-color: #e5e7eb;
            z-index: -1;
        }
        
        .step-indicator.active:not(:last-child)::after {
            background-color: #3b82f6;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .step-indicator.active .step-number {
            background-color: #3b82f6;
            color: white;
        }
        
        .step-title {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
        }
        
        .step-indicator.active .step-title {
            color: #3b82f6;
            font-weight: bold;
        }
        
        .progress-bar {
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }
        
        .step-content {
            transition: opacity 0.3s ease;
        }
    </style>
</x-guest-layout>
