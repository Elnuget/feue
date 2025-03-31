<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $certificado->numero_certificado }}</title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .certificate-container {
            width: 100%;
            height: 100vh;
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
            padding-top: 1rem;
            text-align: center;
        }
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 6rem;
            letter-spacing: 2px;
            margin-bottom: -2rem;
        }
        .nombre-destacado {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .texto-certificado {
            line-height: 1.3;
            margin-bottom: 5px;
        }
        .registro-senescyt {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.9rem;
        }
        .qr-container {
            position: absolute;
            right: 40px;
            top: 65%;
            transform: translateY(-50%);
            background-color: white;
            padding: 10px;
            border-radius: 5px;
        }
        .qr-container img {
            width: 120px;
            height: 120px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
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
                Dado en la ciudad de Quito el {{ \Carbon\Carbon::parse($certificado->fecha_emision)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}.
            </p>

            <div class="registro-senescyt">
                Registro No. SENESCYT-CGAJ-DAJ-{{ $certificado->numero_certificado }}
            </div>
        </div>

        <div class="qr-container">
            {!! QrCode::size(120)->generate(route('certificados.show', $certificado->id)) !!}
        </div>
    </div>
</body>
</html> 