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
            border: 2px solid #ffffff; /* Add border to the photo */
            border-radius: 4px; /* Optional: add rounded corners */
        }

        .text-center {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .user-info {
            margin-top: 2mm;
            line-height: 1.2;
        }

        .user-info p {
            margin: 1px 0;
            padding: 0;
            text-align: center; /* Center the text */
        }

        .user-info .user-name {
            font-size: 10px;
            font-style: italic; /* Add italic style */
        }

        .qr-container {
            margin-top: 4mm;
            text-align: center;
        }

        .qr-container img {
            width: 18mm;
            height: 18mm;
            object-fit: contain;
        }

        .vertical-text {
            position: absolute;
            top: 50%; /* Asegura que el texto comience en el centro vertical */
            left: -15mm; /* Ajusta la distancia desde el borde izquierdo */
            transform: translateY(-50%) rotate(-90deg); /* Centrado y rotación de -90° */
            transform-origin: center; /* Punto de anclaje en el centro del texto */
            font-size: 10px; /* Tamaño de fuente */
            font-weight: bold; /* Negrita para mejor visibilidad */
            color: #ffffff; /* Blanco, asegurando contraste */
            letter-spacing: 1px; /* Espaciado para legibilidad */
            text-align: center; /* Center the text */
        }

        .vertical-date {
            position: absolute;
            top: 50%; /* Asegura que el texto comience en el centro vertical */
            right: calc(-15mm - 6px); /* Ajusta la distancia desde el borde derecho */
            transform: translateY(-50%) rotate(90deg); /* Centrado y rotación de 90° */
            transform-origin: center; /* Punto de anclaje en el centro del texto */
            font-size: 10px; /* Tamaño de fuente */
            font-weight: bold; /* Negrita para mejor visibilidad */
            color: #ffffff; /* Blanco, asegurando contraste */
            letter-spacing: 1px; /* Espaciado para legibilidad */
        }

        .docente-badge {
            position: absolute;
            top: 2mm;
            right: 2mm;
            background-color: #ff6b00;
            color: white;
            font-weight: bold;
            font-size: 8px;
            padding: 1mm 2mm;
            border-radius: 2mm;
            transform: rotate(15deg);
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
                />
            @else
                <div style="width: 22mm; height: 30mm; border: 1px solid #000; margin: 5mm auto 0; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 24px;">👤</span>
                </div>
            @endif
        </div>

        <div class="user-info text-center">
            @php
            $parts = explode(' ', $docente->name);
            $firstLine = implode(' ', array_slice($parts, 0, 2));
            $secondLine = implode(' ', array_slice($parts, 2));
            @endphp
            <p><strong style="color: #ffffff;">Carnet de Docente</strong><br><span class="user-name" style="color: #ffffff;">{{ $firstLine }}<br>{{ $secondLine }}</span></p>
            <p><strong style="color: #ffffff;"></strong><br><span style="color: #ffffff;">{{ $docente->profile->cedula ?? 'N/A' }}</span></p>
        </div>

        <div class="qr-container">
            @if(isset($qrCodes[$docente->id]))
                <img src="data:image/png;base64,{{ $qrCodes[$docente->id] }}" 
                     alt="QR Code" 
                     style="width: 18mm; height: 18mm; margin: 0 auto;">
            @endif
        </div>

        <div class="vertical-text">
            {{ __('CENTRO DE CAPACITACIÓN') }}
        </div>

        <div class="vertical-date">
            <strong>{{ __('Fecha de Emisión:') }} {{ now()->format('Y-m-d') }}</strong>
        </div>
    </div>
@endforeach

</body>
</html> 