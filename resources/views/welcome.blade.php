<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" x-init="
    darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
    if(darkMode) document.documentElement.classList.add('dark');
">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cap U 🎓</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-YOUR_INTEGRITY_HASH_HERE" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js (for dark mode toggle) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center text-gray-800 dark:text-gray-200">
                        <i class="fas fa-graduation-cap text-2xl mr-2"></i>
                        <span class="font-bold text-xl">Cap U 🎓</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <!-- Dark Mode Toggle -->
                    <button @click="
                        darkMode = !darkMode;
                        localStorage.setItem('darkMode', JSON.stringify(darkMode));
                        if (darkMode) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    " class="text-gray-500 dark:text-gray-400 focus:outline-none mr-4">
                        <!-- Muestra 🌙 si está en modo oscuro, y ☀️ si está en modo claro -->
                        <span x-text="darkMode ? '🌙' : '☀️'"></span>
                    </button>

                    @if (Route::has('login'))
                        <div class="hidden sm:flex sm:items-center">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Panel 🖥️
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Iniciar sesión 🔑
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-user-plus mr-1"></i> Registrarse 📝
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Title -->
            <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-200 mb-6 text-center">Cursos disponibles 📚</h3>
            
            <!-- Tipos de Cursos -->
            @foreach($tiposCurso as $tipo)
                <div x-data="{ open: false }" class="mb-6">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <span class="text-xl font-semibold text-gray-900 dark:text-gray-200">
                            {{ $tipo->nombre }}
                        </span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cursosPorTipo->get($tipo->id, []) as $curso)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                                    <img src="{{ asset('storage/' . $curso->imagen) }}" alt="{{ $curso->nombre }}" class="w-full h-32 object-cover">
                                @else
                                    <!-- Aquí utilizamos la imagen por defecto -->
                                    <img src="{{ asset('CursosDefecto.jpg') }}" alt="{{ $curso->nombre }}" class="w-full h-32 object-cover">
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-200 flex items-center">
                                        <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                                        {{ $curso->nombre }} 🎓
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-300">{{ $curso->descripcion }}</p>
                                    <p class="text-gray-900 dark:text-gray-200 font-bold">{{ $curso->precio }} $</p>
                                    <p class="text-gray-500 dark:text-gray-300"><i class="fas fa-clock mr-1"></i> {{ $curso->horario }}</p>
                                    <a href="{{ route('matriculas.create', ['curso_id' => $curso->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">
                                        <i class="fas fa-folder-plus mr-2"></i> Generar Matrícula 📁
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </div>
    </footer>

</body>
</html>
