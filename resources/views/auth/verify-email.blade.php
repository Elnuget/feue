<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo electrónico, con gusto te enviaremos otro. 📧') }}
    </div>

    <!-- Advertencia de Spam -->
    <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded-r-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <!-- Icono de advertencia -->
                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-900">¡Importante! 🔍</h3>
                <div class="mt-1 text-sm text-yellow-800">
                    <p>Si no encuentras el correo de verificación en tu bandeja de entrada:</p>
                    <ul class="list-disc list-inside mt-1">
                        <li>Revisa tu carpeta de <strong>SPAM</strong> o <strong>Correo no deseado</strong> 📥</li>
                        <li>Marca nuestro correo como "No es spam" para futuros mensajes ✅</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro. ✉️') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Reenviar correo de verificación 📤') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Cerrar sesión 🚪') }}
            </button>
        </form>
    </div>
</x-guest-layout>
