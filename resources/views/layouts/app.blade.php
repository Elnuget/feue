<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Metatags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cap U 🎓 @hasSection('page_title') - @yield('page_title') @endif</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>


<body class="font-sans antialiased">
    <!-- Agregamos darkMode al x-data y la lógica en x-init -->
    <div x-data="{ open: false, darkMode: false }" x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
        if(darkMode) document.documentElement.classList.add('dark');
    " class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header
                class="flex items-center px-4 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 relative z-10">
                <!-- Botón Toggle Sidebar -->
                <button @click="open = !open" class="text-gray-500 dark:text-gray-400 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Título o Logo -->
                <div class="flex-1 text-center">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Panel Principal 🎓
                        </span>
                        <span class="text-xs text-gray-600 dark:text-gray-400">
                            {{ Auth::user()->roles->pluck('name')->first() ?? 'No Role' }}
                        </span>
                    </a>
                </div>

                <!-- Botón Modo Claro/Oscuro al lado de la imagen de perfil -->
                <button @click="
                        darkMode = !darkMode;
                        localStorage.setItem('darkMode', JSON.stringify(darkMode));
                        if (darkMode) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    " class="text-gray-500 dark:text-gray-400 focus:outline-none mr-4">
                        <span x-text="darkMode ? '🌙' : '☀️'"></span>
                </button>

                <!-- Menú de Usuario -->
                <div class="flex items-center ml-auto">
                    <!-- Mensaje de Bienvenida -->
                    <span class="hidden sm:block text-gray-800 dark:text-gray-200 mr-4">
                        Bienvenido, {{ Auth::user()->name }}.
                    </span>

                    <!-- Dropdown de Usuario -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center focus:outline-none transition duration-150">
                                @if(Auth::user()->profile && Auth::user()->profile->photo && file_exists(storage_path('app/public/' . Auth::user()->profile->photo)))
                                    <img src="{{ asset('storage/' . Auth::user()->profile->photo) }}" 
                                         alt="User Photo"
                                         class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600">👤</span>
                                    </div>
                                @endif
                                <span class="ml-2">▼</span>
                            </button>
                        </x-slot>

                        <x-dropdown-link :href="route('profile.edit')">
                            👤 {{ __('Perfil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.complete')">
                            ⚙️ {{ __('Configuración') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                🚪 {{ __('Cerrar Sesión') }}
                            </x-dropdown>
                        </form>
                    </x-dropdown>
                </div>
            </header>

            <!-- Alert Section -->
            <div id="alert-section">
                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4 dark:bg-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('updated'))
                    <div class="bg-blue-500 text-white p-4 rounded mb-4 dark:bg-blue-700">
                        {{ session('updated') }}
                    </div>
                @endif
                @if(session('deleted'))
                    <div class="bg-red-500 text-white p-4 rounded mb-4 dark:bg-red-700">
                        {{ session('deleted') }}
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4">
                @if (isset($header))
                    <div class="bg-white dark:bg-gray-800 shadow mb-4">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
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

        document.addEventListener('DOMContentLoaded', function () {
            @if(Auth::check())
                const isComplete = {{ Auth::user()->profile && Auth::user()->profile->isComplete() ? 'true' : 'false' }};
                console.log('Profile is complete:', isComplete);
                if (!isComplete) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'bg-orange-500 text-white p-4 rounded mb-4 dark:bg-orange-700';
                    alertDiv.innerHTML = 'Por favor, completa tu perfil. <a href="{{ route('profile.complete') }}" class="underline">Completar registro</a>';
                    document.getElementById('alert-section').appendChild(alertDiv);
                }
            @else
                console.error('User is not authenticated');
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
