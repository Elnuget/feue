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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Estilos personalizados para transiciones e imágenes -->
    <style>
        /* Aseguramos que las imágenes mantengan una proporción adecuada */
        .curso-img {
            aspect-ratio: 16/9;
        }
        /* Efecto hover en las tarjetas de curso */
        .curso-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center text-gray-800 dark:text-gray-200 text-lg sm:text-xl font-bold">
                        <i class="fas fa-graduation-cap text-xl sm:text-2xl mr-2"></i>
                        Cap U 🎓
                    </a>
                </div>
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Botón para Dark Mode -->
                    <button @click="
                        darkMode = !darkMode;
                        localStorage.setItem('darkMode', JSON.stringify(darkMode));
                        document.documentElement.classList.toggle('dark', darkMode);
                    " class="focus:outline-none text-2xl">
                        <span x-text="darkMode ? '🌙' : '☀️'"></span>
                    </button>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="flex items-center text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-base font-medium">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Panel 🖥️
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-base font-medium">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Iniciar sesión 🔑
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="flex items-center text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-base font-medium">
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
    <main class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Título -->
            <h3 class="text-2xl sm:text-4xl font-extrabold text-center text-gray-900 dark:text-gray-100 mb-8">
                Cursos disponibles 📚
            </h3>
            
            <!-- Tipos de Cursos -->
            @foreach($tiposCurso as $tipo)
                <div x-data="{ open: false }" class="mb-8">
                    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $tipo->nombre }}
                        </span>
                        <svg :class="{'rotate-180': open}" class="w-6 h-6 transform transition-transform duration-200 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition.duration.300ms class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cursosPorTipo->get($tipo->id, []) as $curso)
                            <div class="curso-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-lg transition-transform duration-200">
                                <!-- Imagen del curso -->
                                @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                                    <img src="{{ asset('storage/' . $curso->imagen) }}" alt="{{ $curso->nombre }}" class="curso-img w-full object-cover">
                                @else
                                    <img src="{{ asset('CursosDefecto.jpg') }}" alt="{{ $curso->nombre }}" class="curso-img w-full object-cover">
                                @endif
                                
                                <!-- Detalles del curso -->
                                <div class="p-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center mb-2">
                                        <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                                        {{ $curso->nombre }} 🎓
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-2 line-clamp-3">
                                        {{ $curso->descripcion }}
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
                                        <i class="fas fa-clock mr-1"></i> {{ $curso->horario }}
                                    </p>
                                    <a href="{{ route('matriculas.create', ['curso_id' => $curso->id]) }}" class="block text-center bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition-colors">
                                        <i class="fas fa-folder-plus mr-1"></i> Matricularme
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </main>

</body>
</html>
