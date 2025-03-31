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
            padding: 2rem;
            padding-top: 1rem;
            text-align: center;
        }
        .certificate-title {
            font-size: 60px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .nombre-destacado {
            font-size: 30px;
            font-weight: bold;
            margin: 20px 0;
        }
        .texto-certificado {
            font-size: 18px;
            line-height: 1.5;
            margin: 10px 0;
        }
        .registro-senescyt {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 14px;
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
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-content">
            <h1>SE CONFIERE EL SIGUIENTE</h1>
            
            <div class="certificate-title">CERTIFICADO</div>
            
            <p>A</p>

            <div class="nombre-destacado">{{ $certificado->nombre_completo }}</div>

            <p class="texto-certificado">Por ser parte del programa de capacitación continua en</p>
            <p class="texto-certificado" style="font-weight: bold;">el curso de {{ $certificado->nombre_curso }}</p>

            <p class="texto-certificado">
                Con una carga académica de {{ $certificado->horas_curso }} horas prácticas en {{ $certificado->sede_curso }}.
            </p>
            <p class="texto-certificado">
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