<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Credenciales de Matriculados') }}</title>
    <style>
        /* Importar la fuente Roboto (opcional).
           Puedes quitarlo y solo dejar Arial si lo prefieres. */
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

        /* Ajuste de página a 53.975 x 85.725 mm, sin márgenes */
        @page {
            size: 53.975mm 85.725mm;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            /* Usamos la fuente Roboto, y en fallback Arial, sans-serif */
            font-family: 'Roboto', Arial, sans-serif; 
            font-size: 12px;
        }

        /* Contenedor principal de la credencial */
        .credential-container {
            width: 53.975mm;
            height: 85.725mm;
            page-break-inside: avoid;
            -webkit-page-break-inside: avoid;
            break-inside: avoid-page;
            position: relative;
            box-sizing: border-box;
            padding: 5mm;
            overflow: hidden;
        }

        /* Imagen de fondo */
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Foto de perfil */
        .profile-photo {
            width: 18mm;
            height: 18mm;
            object-fit: cover;
            border-radius: 0;
            display: block;
            /* Aumentamos el margen superior para bajar la imagen */
            margin: 10mm auto 0; 
        }

        /* Texto centrado */
        .text-center {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        /* Contenedor de la información del usuario */
        .user-info {
            margin-top: 2mm;    /* Puedes ajustar para más o menos espacio */
            line-height: 1.2;   /* Ajustamos el interlineado para que quede más junto */
        }
        .user-info p {
            margin: 2px 0;     /* Ajustar según necesites */
            padding: 0;
        }

        .user-info .user-name {
            font-size: 10px;
        }

        /* Contenedor del QR */
        .qr-container {
            margin-top: 5mm;
            text-align: center;
        }
        .qr-container img {
            width: 12mm;
            height: 12mm;
            object-fit: contain;
        }
    </style>
</head>
<body>

{{-- Importante: SIN comentarios, saltos de línea o espacios extra antes del foreach --}}
@foreach($matriculas as $index => $matricula)
    <div class="credential-container" 
         style="@if($loop->iteration > 1) page-break-before: always; @endif">
         
        {{-- Si hay un background definido en la sesión, lo aplicamos --}}
        @if(session('background_path'))
            <div class="background-image"
                 style="background-image: url('{{ public_path('storage/imagenes_de_fondo/' . session('background_path')) }}');">
            </div>
        @endif

        <!-- Foto de perfil -->
        <div class="text-center">
            @if(
                $matricula->usuario->profile &&
                $matricula->usuario->profile->photo &&
                file_exists(storage_path('app/public/' . $matricula->usuario->profile->photo))
            )
                <img
                  src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $matricula->usuario->profile->photo))) }}"
                  alt="Foto de perfil"
                  class="profile-photo"
                />
            @else
                <!-- Si no hay foto, dibujamos un círculo vacío -->
                <div 
                  style="width: 15mm; height: 15mm; border: 1px solid #000; border-radius: 50%; margin: 10mm auto 0;">
                </div>
            @endif
        </div>

        <!-- Datos del usuario -->
        <div class="user-info text-center">
            @php
            $parts = explode(' ', $matricula->usuario->name);
            $firstLine = implode(' ', array_slice($parts, 0, 2));
            $secondLine = implode(' ', array_slice($parts, 2, 2));
            @endphp
            <p>
                <strong style="color: #ffffff;">Nombre:</strong><br>
                <span style="color: #ffffff;" class="user-name">
                    {{ $firstLine }}<br>{{ $secondLine }}
                </span>
            </p>
            <p>
                <strong style="color: #ffffff;">Cédula:</strong><br>
                <span style="color: #ffffff;">{{ $matricula->usuario->profile->cedula ?? 'N/A' }}</span>
            </p>
            <p>
                <strong style="color: #ffffff;">Fecha de Emisión:</strong><br>
                <span style="color: #ffffff;">{{ now()->format('Y-m-d') }}</span>
            </p>
        </div>

        <!-- Código QR en Base64 -->
        <div class="qr-container">
            <img src="data:image/png;base64,{{ $qrCodes[$matricula->usuario->id] }}" alt="QR Code">
        </div>

    </div>
@endforeach

</body>
</html>
