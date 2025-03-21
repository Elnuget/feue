<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" x-init="
    darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
    if(darkMode) document.documentElement.classList.add('dark');
">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificado - {{ $certificado->numero_certificado }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts para fuente elegante -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        .certificate-container {
            width: 100%;
            max-width: 1100px;
            height: 800px;
            margin: 0 auto;
            position: relative;
            background-image: url('{{ asset("fonto_Certificado.png") }}');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
        .certificate-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            padding-top: 1rem; /* Reducido para subir el contenido */
            text-align: center;
        }
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 6rem;
            letter-spacing: 2px;
            margin-bottom: -2rem; /* Reducido desde 1.5rem */
        }
        .nombre-destacado {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 1rem; /* Reducido desde 1.5rem */
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.1;
            font-size: 8rem;
            color: #234E70;
            pointer-events: none;
            z-index: 0;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            width: 90%;
            margin-top: 3rem;
            padding-top: 3rem;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .registro-senescyt {
            position: absolute;
            bottom: 40px; /* Subimos un poco la posición */
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.9rem;
        }
        /* Ajustamos el interlineado para el texto del certificado */
        .texto-certificado {
            line-height: 1.3; /* Reducimos el interlineado */
            margin-bottom: 5px; /* Reducimos el margen inferior */
        }
        @media print {
            body {
                background-color: white;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .certificate-container {
                margin: 0;
                height: 100vh;
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo / Título -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center text-gray-800 dark:text-gray-200 text-lg sm:text-xl font-bold">
                        <i class="fas fa-graduation-cap text-xl sm:text-2xl mr-2"></i>
                        Cap U 🎓
                    </a>
                </div>

                <!-- Dark Mode Toggle -->
                <button
                    @click="darkMode = !darkMode; localStorage.setItem('darkMode', JSON.stringify(darkMode)); darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')"
                    class="text-gray-600 dark:text-gray-400 focus:outline-none"
                    title="Cambiar modo"
                >
                    <span x-text="darkMode ? '🌙' : '☀️'"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Certificado -->
    <div class="py-12">
        <div class="certificate-container shadow-xl rounded-lg">
            <div class="certificate-content">
                <h1 class="text-2xl font-bold mb-2">SE CONFIERE EL SIGUIENTE</h1>
                
                <h2 class="certificate-title">CERTIFICADO</h2>
                
                <p class="text-xl mb-1">A</p>

                <h3 class="nombre-destacado">{{ $certificado->nombre_completo }}</h3>

                <p class="text-xl mb-2 texto-certificado">Por ser parte del programa de capacitación continua en</p>
                <p class="text-xl font-bold mb-5 texto-certificado">el curso de {{ $certificado->nombre_curso }}</p>

                <p class="text-xl texto-certificado">
                    Con una carga académica de {{ $certificado->horas_curso }} horas prácticas en {{ $certificado->sede_curso }}.
                </p>
                <p class="text-xl mb-4 texto-certificado">
                    Dado en la ciudad de Quito el {{ $certificado->fecha_emision->formatLocalized('%d de %B del %Y') }}.
                </p>

                <!-- Registro SENESCYT en la parte inferior -->
                <div class="registro-senescyt">
                    Registro No. SENESCYT-CGAJ-DAJ-{{ $certificado->numero_certificado }}
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 flex justify-center space-x-4 no-print">
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-print mr-2"></i> Imprimir Certificado
            </button>
            @auth
                @if(auth()->user()->hasRole(1))
                    <a href="{{ route('certificados.edit', $certificado) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </a>
                    <form action="{{ route('certificados.destroy', $certificado) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar este certificado?')">
                            <i class="fas fa-trash mr-2"></i> Eliminar
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</body>
</html>