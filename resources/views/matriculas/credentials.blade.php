<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Credenciales de Matriculados') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

        @page {
            size: 53.975mm 85.725mm;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif; 
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
            width: 18mm;
            height: 24mm;
            object-fit: cover;
            display: block;
            margin: 5mm auto 0;
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
        }

        .user-info .user-name {
            font-size: 10px;
        }

        .qr-container {
            margin-top: 4mm;
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

@foreach($matriculas as $index => $matricula)
    <div class="credential-container" style="@if($loop->iteration > 1) page-break-before: always; @endif">
        @if(session('background_path'))
            <div class="background-image" 
                 style="background-image: url('{{ public_path('storage/imagenes_de_fondo/' . session('background_path')) }}');">
            </div>
        @endif

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
                <div style="width: 15mm; height: 15mm; border: 1px solid #000; border-radius: 50%; margin: 10mm auto 0;"></div>
            @endif
        </div>

        <div class="user-info text-center">
            @php
            $parts = explode(' ', $matricula->usuario->name);
            $firstLine = implode(' ', array_slice($parts, 0, 2));
            $secondLine = implode(' ', array_slice($parts, 2));
            @endphp
            <p><strong style="color: #ffffff;">Nombre:</strong><br><span class="user-name" style="color: #ffffff;">{{ $firstLine }}<br>{{ $secondLine }}</span></p>
            <p><strong style="color: #ffffff;">Cédula:</strong><br><span style="color: #ffffff;">{{ $matricula->usuario->profile->cedula ?? 'N/A' }}</span></p>
            <p><strong style="color: #ffffff;">Fecha de Emisión:</strong><br><span style="color: #ffffff;">{{ now()->format('Y-m-d') }}</span></p>
        </div>

        <div class="qr-container">
            <img src="data:image/png;base64,{{ $qrCodes[$matricula->usuario->id] }}" alt="QR Code">
        </div>
    </div>
@endforeach

</body>
</html>
