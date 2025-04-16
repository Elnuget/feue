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
            background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('fonto_Certificado.png'))) }}');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
        .certificate-container {
            width: 100%;
            height: 100vh;
            position: relative;
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
            padding: 1rem;
            padding-top: 9rem;
            text-align: center;
        }
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 6rem;
            letter-spacing: 2px;
            margin-bottom: -1rem;
        }
        .nombre-destacado {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .texto-certificado {
            font-size: 1.25rem;
            line-height: 0.7;
            margin-bottom: 0px;
        }
        .registro-S {
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
        h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-content">
            <h1>SE CONFIERE EL SIGUIENTE</h1>
            
            <div class="certificate-title">CERTIFICADO</div>
            
            <p class="texto-certificado">A</p>

            <div class="nombre-destacado">{{ $certificado->nombre_completo }}</div>

            <p class="texto-certificado">Por ser parte del programa de capacitación continua en</p>
            <p class="texto-certificado" style="font-weight: bold;">el curso de {{ $certificado->nombre_curso }}</p>

            <p class="texto-certificado">
                Con una carga académica de {{ $certificado->horas_curso }} horas prácticas en {{ $certificado->sede_curso }}.
            </p>
            <p class="texto-certificado">
                Dado en la ciudad de Quito el {{ \Carbon\Carbon::parse($certificado->fecha_emision)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}.
            </p>

            <div class="registro-S">
                Registro No. S-CGAJ-DAJ-{{ $certificado->numero_certificado }}
            </div>
        </div>

        <div class="qr-container">
            <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="Código QR">
        </div>
    </div>
</body>
</html> 