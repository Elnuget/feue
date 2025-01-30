<x-guest-layout>
    @section('page_title', 'Register')
    <form method="POST" action="{{ route('register') }}">
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
                <x-text-input id="cedula" class="block mt-1 w-full" type="text" name="cedula" 
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

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4" disabled>
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Script para verificar cédula y controlar el estado de los campos -->
    <script>
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
    </script>
</x-guest-layout>
