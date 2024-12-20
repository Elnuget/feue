<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Credenciales de Matriculados') }}</title>
    <style>
        @page { 
            size: 53.975mm 85.725mm; 
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px; /* Reducir aún más el tamaño de fuente */
            padding: 5px;
        }
        .credential-container {
            position: relative;
            width: 100%;
            height: 85.725mm;
            padding: 5px;
        }
        .profile-photo {
            width: 15mm;
            height: 15mm;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 2mm auto;
        }
        .qr-container {
            text-align: center;
            margin: 2mm auto;
            width: 20mm;  /* Aumentado el tamaño */
            height: 20mm; /* Aumentado el tamaño */
            background: white;
            padding: 1mm;
            border: 1px solid black; /* Añadir borde para el rectángulo */
        }
        .qr-code {
            width: 100%;
            height: 100%;
        }
        .qr-container svg {
            width: 100% !important;
            height: 100% !important;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    @foreach($matriculas as $index => $matricula)
    <div class="credential-container">
        <div class="text-center">
            @if($matricula->usuario->profile && $matricula->usuario->profile->photo)
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $matricula->usuario->profile->photo))) }}" 
                     alt="Photo" 
                     class="profile-photo"/>
            @endif
        </div>
        <p class="text-center"><strong>{{ $matricula->usuario->name }}</strong></p>
        <p class="text-center">{{ $matricula->usuario->profile->cedula ?? 'N/A' }}</p>
        <p class="text-center">{{ now()->format('Y-m-d') }}</p>
        <div class="qr-container">
            <div class="qr-code">
                {!! QrCode::size(75)->generate($matricula->usuario->id) !!}
            </div>
        </div>
    </div>
    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
    @endforeach
</body>
</html>
