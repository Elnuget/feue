<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Metatags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="{ open: false }" class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header
                class="flex items-center px-4 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 relative z-10">
                <!-- Botón de Toggle (solo para pantallas pequeñas) -->
                <button @click="open = !open" class="sm:hidden text-gray-500 dark:text-gray-400 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <!-- Ícono de menú (tres líneas) -->
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <!-- Ícono de cerrar (X) -->
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Título o Logo (opcional) -->
                <div class="flex-1 text-center sm:text-left">
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'No Role' }}
                    </a>
                </div>

                <!-- Menú de Usuario -->
                <div class="flex items-center ml-auto">
                    <!-- Mensaje de Bienvenida -->
                    <span class="hidden sm:block text-gray-800 dark:text-gray-200 mr-4">
                        Bienvenido, {{ Auth::user()->name }}.
                    </span>

                    <!-- Foto de Usuario -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center focus:outline-none transition duration-150">
                                @if(Auth::user()->profile && Auth::user()->profile->photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile->photo) }}" alt="User Photo"
                                         class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default User Photo"
                                         class="h-8 w-8 rounded-full object-cover">
                                @endif
                                <svg class="ml-2 h-4 w-4 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <!-- Contenido del Dropdown -->
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Autenticación -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                             this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4">
                @if (isset($header))
                    <div class="bg-white dark:bg-gray-800 shadow mb-4">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endif

                @if(Auth::check() && Auth::user()->profile && !Auth::user()->profile->isComplete())
                    <div class="bg-yellow-500 text-white p-4 rounded mb-4">
                        Por favor, completa tu perfil. <a href="{{ url('/complete-profile') }}" class="underline">Completar perfil</a>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            @if(Auth::check() && Auth::user()->profile)
                console.log('Profile is complete:', {{ Auth::user()->profile->isComplete() ? 'true' : 'false' }});
            @endif
        });
    </script>
</body>

</html>
