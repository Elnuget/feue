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

    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        .certificate-container {
            width: 100%;
            max-width: 1024px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            background-color: white;
        }
        .certificate-border {
            border: 2px solid #234E70;
            padding: 2rem;
            position: relative;
        }
        .certificate-content {
            text-align: center;
            position: relative;
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
            margin-top: 4rem;
            padding: 0 2rem;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            width: 100%;
            border-bottom: 1px solid #000;
            margin-bottom: 0.5rem;
        }
        @media print {
            body {
                background-color: white;
            }
            .no-print {
                display: none;
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
        <div class="certificate-container bg-white shadow-xl rounded-lg">
            <div class="certificate-border">
                <div class="certificate-content">
                    <div class="watermark">CAP-U</div>
                    
                    <h1 class="text-2xl font-bold mb-8">SE CONFIERE EL SIGUIENTE CERTIFICADO</h1>
                    
                    <h2 class="text-5xl font-bold mb-4">CERTIFICADO</h2>
                    
                    <p class="text-xl mb-8">A</p>

                    <h3 class="text-2xl font-bold mb-8">{{ $certificado->nombre_completo }}</h3>

                    <p class="text-xl mb-4">Por ser parte del programa de capacitación continua en</p>
                    <p class="text-xl font-bold mb-8">el curso de {{ $certificado->nombre_curso }}</p>

                    <p class="text-xl mb-8">
                        Con una carga académica de {{ $certificado->horas_curso }} horas prácticas en {{ $certificado->sede_curso }}. Dado en
                        la ciudad de Quito el {{ $certificado->fecha_emision->format('d \d\e F \d\e\l Y') }}.
                    </p>

                    <div class="signature-container">
                        <div class="signature">
                            <div class="signature-line"></div>
                            <p class="font-bold">Ab. Xavier Remache</p>
                            <p class="text-sm">COORDINADOR FEUE NACIONAL</p>
                        </div>
                        <div class="signature">
                            <div class="signature-line"></div>
                            <p class="font-bold">Ab. Erik Barba</p>
                            <p class="text-sm">GERENTE DE CAP - U</p>
                        </div>
                        <div class="signature">
                            <div class="signature-line"></div>
                            <p class="font-bold">Sr. Adrián Durán</p>
                            <p class="text-sm">REPRESENTANTE ASESEC</p>
                        </div>
                    </div>

                    <div class="mt-8 text-sm">
                        Registro No. SENESCYT-CGAJ-DAJ-{{ $certificado->numero_certificado }}
                    </div>
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