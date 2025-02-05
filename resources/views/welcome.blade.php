<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" x-init="
    darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
    if(darkMode) document.documentElement.classList.add('dark');
">
<head>
    <!-- Metatags, etc. -->
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
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-YOUR_INTEGRITY_HASH_HERE"
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo / Título -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center text-gray-800 dark:text-gray-200 text-lg sm:text-xl font-bold">
                        <i class="fas fa-graduation-cap text-xl sm:text-2xl mr-2"></i>
                        Cap U 🎓
                    </a>
                </div>

                <!-- Opciones de Usuario y Dark Mode -->
                <div class="flex items-center">
                    <!-- Dark Mode Toggle -->
                    <button
                      @click="
                        darkMode = !darkMode;
                        localStorage.setItem('darkMode', JSON.stringify(darkMode));
                        if (darkMode) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                      "
                      class="text-gray-600 dark:text-gray-400 focus:outline-none mr-2 sm:mr-4 text-base"
                      title="Cambiar modo"
                    >
                        <span x-text="darkMode ? '🌙' : '☀️'"></span>
                    </button>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            @auth
                                <a
                                  href="{{ url('/dashboard') }}"
                                  class="text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-1 rounded-md text-base font-medium flex items-center"
                                >
                                    <i class="fas fa-tachometer-alt mr-2"></i> Panel 🖥️
                                </a>
                            @else
                                <a
                                  href="{{ route('login') }}"
                                  class="text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-1 rounded-md text-base font-medium flex items-center"
                                >
                                    <i class="fas fa-sign-in-alt mr-2"></i> Iniciar sesión 🔑
                                </a>
                                @if (Route::has('register'))
                                    <a
                                      href="{{ route('register') }}"
                                      class="text-gray-800 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-1 rounded-md text-base font-medium flex items-center"
                                    >
                                        <i class="fas fa-user-plus mr-2"></i> Registrarse 📝
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
    <main class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Título general -->
            <h3 class="text-2xl sm:text-4xl font-bold text-gray-900 dark:text-gray-200 mb-6 sm:mb-10 text-center">
                Cursos Disponibles 📚
            </h3>

            <!-- Listado de Tipos de Cursos -->
            @foreach($tiposCurso as $tipo)
                <div x-data="{ open: false }" class="mb-8">
                    <!-- Botón para desplegar -->
                    <button
                      @click="open = !open"
                      class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                    >
                        <span class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-200">
                            {{ $tipo->nombre }}
                        </span>
                        <svg
                          :class="{'rotate-180': open}"
                          class="w-5 h-5 transform transition-transform duration-300 text-gray-600 dark:text-gray-300"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                        >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z"
                              clip-rule="evenodd"
                            />
                        </svg>
                    </button>

                    <!-- Contenedor de Cursos por Tipo -->
                    <div
                      x-show="open"
                      x-transition:enter="transition ease-out duration-300"
                      x-transition:enter-start="opacity-0 scale-95"
                      x-transition:enter-end="opacity-100 scale-100"
                      x-transition:leave="transition ease-in duration-200"
                      x-transition:leave-start="opacity-100 scale-100"
                      x-transition:leave-end="opacity-0 scale-95"
                      class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                      style="display: none;"
                    >
                        @foreach($cursosPorTipo->get($tipo->id, []) as $curso)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden flex flex-col">
                                <!-- Imagen (usamos object-contain para que se vea completa) -->
                                @if($curso->imagen && file_exists(public_path('storage/' . $curso->imagen)))
                                    <img
                                      src="{{ asset('storage/' . $curso->imagen) }}"
                                      alt="{{ $curso->nombre }}"
                                      class="w-full h-48 object-contain bg-gray-100 dark:bg-gray-900"
                                    >
                                @else
                                    <img
                                      src="{{ asset('CursosDefecto.jpg') }}"
                                      alt="{{ $curso->nombre }}"
                                      class="w-full h-48 object-contain bg-gray-100 dark:bg-gray-900"
                                    >
                                @endif

                                <!-- Información del Curso -->
                                <div class="p-4 flex flex-col flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center mb-2">
                                        <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                                        {{ $curso->nombre }} 🎓
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                        {{ $curso->descripcion }}
                                    </p>
                                    <!-- <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $curso->precio }} $</p> -->
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                                        <i class="fas fa-clock mr-1"></i> {{ $curso->horario }}
                                    </p>
                                    <div class="mt-auto">
                                        <a
                                          href="{{ route('matriculas.create', ['curso_id' => $curso->id]) }}"
                                          class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded shadow transition-colors duration-200"
                                        >
                                            <i class="fas fa-folder-plus mr-2"></i> Matricularme
                                        </a>
                                    </div>
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
