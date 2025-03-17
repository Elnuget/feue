<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Credenciales de Docentes') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        @page {
            size: 53.975mm 85.725mm;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif; 
            font-size: 12px;
        }

        .credential-container {
            width: 53.975mm;
            height: 85.725mm;
            page-break-inside: avoid;
            position: relative;
            box-sizing: border-box;
            padding: 3mm;
            overflow: hidden;
            margin-left: -12px;
        }

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

        .profile-photo {
            width: 22mm;
            height: 30mm;
            object-fit: cover;
            display: block;
            margin: 5mm auto 0;
            border: 2px solid #ffffff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3); /* Sombra sutil */
        }

        .text-center {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .user-info {
            margin-top: 2mm;
            line-height: 1.3; /* Aumentado ligeramente */
        }

        .user-info p {
            margin: 2px 0; /* Aumentado desde 1px */
            padding: 0;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5); /* Sombra de texto para legibilidad */
        }

        .user-info .user-name {
            font-size: 10px;
            font-style: italic;
            font-weight: 500; /* Un poco más pronunciado */
        }

        .qr-container {
            margin-top: 3mm;
            text-align: center;
        }

        .qr-image {
            width: 18mm;
            height: 18mm;
            object-fit: contain;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 1mm;
            background-color: rgba(255,255,255,0.8);
            border-radius: 2mm;
            margin: 0 auto;
            display: block;
        }

        .vertical-text {
            position: absolute;
            top: 50%;
            left: -15mm;
            transform: translateY(-50%) rotate(-90deg);
            transform-origin: center;
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1.2px; /* Ligeramente aumentado */
            text-align: center;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5); /* Sombra para mejor legibilidad */
        }

        .vertical-date {
            position: absolute;
            top: 50%;
            right: calc(-15mm - 6px);
            transform: translateY(-50%) rotate(90deg);
            transform-origin: center;
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1.2px; /* Ligeramente aumentado */
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5); /* Sombra para mejor legibilidad */
        }

        .docente-badge {
            position: absolute;
            top: -6mm; /* Posicionado más arriba */
            right: -19mm; /* Ajustado para mantener la diagonal correcta */
            background-color: #00703c; /* Color sólido, magenta intenso */
            color: white;
            font-weight: 700;
            font-size: 10px;
            padding: 2mm 17mm; /* Padding extendido para crear una "tira" más ancha */
            transform: rotate(45deg); /* Rotación diagonal */
            box-shadow: 0 2px 3px rgba(0,0,0,0.4); /* Sombra sutil */
            letter-spacing: 1.2px; 
            text-align: center;
            text-transform: uppercase;
            z-index: 10; /* Asegura que esté por encima de otros elementos */
        }
        
        /* Nuevo estilo para el logo institucional */
        .institution-logo {
            position: absolute;
            bottom: 2mm;
            right: 2mm;
            width: 15mm;
            height: 15mm; /* Altura fija */
            object-fit: contain; /* Mantiene proporción sin distorsión */
            opacity: 0.9; /* Ligeramente transparente */
        }
    </style>
</head>
<body>

@foreach($docentes as $docente)
    <div class="credential-container" style="@if($loop->iteration > 1) page-break-before: always; @endif">
        <div class="background-image" 
             style="background-image: url('{{ public_path('storage/imagenes_de_fondo_permanentes/background.jpg') }}');">
        </div>

        <div class="docente-badge">DOCENTE</div>

        <div class="text-center">
            @php
                $photoPath = null;
                if ($docente->profile && 
                    $docente->profile->photo && 
                    Storage::disk('public')->exists($docente->profile->photo)) {
                    $photoPath = storage_path('app/public/' . $docente->profile->photo);
                }
            @endphp
            
            @if($photoPath && file_exists($photoPath))
                <img 
                    src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoPath)) }}"
                    alt="Foto de perfil"
                    class="profile-photo"
                    style="width: 22mm; height: 30mm; object-fit: cover;"
                />
            @else
                <div style="width: 22mm; height: 30mm; border: 1px solid #fff; margin: 5mm auto 0; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.1); border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    <span style="font-size: 28px;">👤</span>
                </div>
            @endif
        </div>

        <div class="user-info text-center">
            @php
            $parts = explode(' ', $docente->name);
            $firstLine = implode(' ', array_slice($parts, 0, 2));
            $secondLine = implode(' ', array_slice($parts, 2));
            @endphp
            <p><span style="color: #ffffff; font-size: 11px; font-weight: normal;">Carnet de Docente</span><br><span class="user-name" style="color: #ffffff; font-weight: 700;">Prof. {{ $firstLine }}<br>{{ $secondLine }}</span></p>
            <p><strong style="color: #ffffff;"></strong><br><span style="color: #ffffff;">{{ $docente->profile->cedula ?? 'N/A' }}</span></p>
        </div>

        <div class="qr-container">
            @if(isset($qrCodes[$docente->id]))
                <img src="data:image/png;base64,{{ $qrCodes[$docente->id] }}" 
                     alt="QR Code" 
                     style="width: 18mm; height: 18mm; object-fit: contain; border: 1px solid rgba(255,255,255,0.3); padding: 1mm; background-color: rgba(255,255,255,0.8); border-radius: 2mm; margin: 0 auto; display: block;">
            @endif
        </div>

        <div class="vertical-text">
            {{ __('CENTRO DE CAPACITACIÓN') }}
        </div>

        <div class="vertical-date">
            <strong>{{ __('Fecha de Emisión:') }} {{ now()->format('Y-m-d') }}</strong>
        </div>
        
        <!-- Logo institucional (si está disponible en la imagen) -->
        @if(isset($institucionalLogo))
            <img src="{{ $institucionalLogo }}" 
                 alt="Logo" 
                 class="institution-logo"
                 style="width: 15mm; height: 15mm; object-fit: contain;">
        @endif
    </div>
@endforeach

</body>
</html>