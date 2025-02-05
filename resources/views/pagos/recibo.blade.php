<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .favicon-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .favicon-container img {
            width: 100px;
            height: auto;
        }
        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .subtitulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 40px;
        }
        .contenido {
            margin-bottom: 20px;
            text-align: left;
        }
        .horario {
            margin-top: 20px;
            margin-bottom: 100px; 
            color: #000000;
            font-weight: bold;
        }
        .nota {
            margin-top: 30px;
            margin-bottom: 100px; 
        }
        .firma {
            margin-bottom: 15px;
            text-align: left;
        }
        .firma-final {
            margin-top: 100px; 
        }
        .metodo-pago {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="favicon-container">
        <img src="{{ public_path('favicon.png') }}" alt="Logo">
    </div>

    <div class="titulo">
        RECIBO
    </div>
    <div class="subtitulo">
        {{ strtoupper($pago->matricula->curso->nombre) }}
    </div>

    <div class="contenido">
        Se recibe de 
        @php
            $userProfile = $pago->matricula->usuario->userProfile;
            $gender = $userProfile->gender ?? null;
            $cedula = $userProfile->cedula ?? 'No registrado';
            
            if ($gender === 'Masculino') {
                $tratamiento = 'el señor';
            } elseif ($gender === 'Femenino') {
                $tratamiento = 'la señora';
            } else {
                $tratamiento = 'el/la señor(a)';
            }
        @endphp
        {{ $tratamiento }} <strong>{{ strtoupper($pago->matricula->usuario->name) }}</strong> con 
        CI#<strong>{{ $cedula }}</strong> la cantidad de
        <strong>${{ number_format($pago->monto, 2) }}</strong> dólares americanos mediante 
        <strong>{{ $pago->metodoPago->nombre }}</strong> por concepto de pago del CURSO 
        <strong>{{ strtoupper($pago->matricula->curso->nombre) }}</strong>.
    </div>

    <div class="contenido horario">
        MODALIDAD: {{ strtoupper($pago->matricula->curso->tipoCurso->nombre ?? 'NO ESPECIFICADO') }}<br>
        HORARIO: {{ strtoupper($pago->matricula->curso->horario ?? 'NO ESPECIFICADO') }}
    </div>

    <div class="contenido nota">
        <strong>Nota:</strong> No se aceptan devoluciones por matrícula o mensualidad bajo ninguna circunstancia.
    </div>

    <div class="firma">
        <strong>{{ strtoupper($pago->matricula->usuario->name) }}</strong><br>
        <strong>ESTUDIANTE</strong>
    </div>

    <div class="firma firma-final">
        <strong>CENTRO DE CAPACITACIÓN UNIVERSITARIA</strong><br>
        <strong>ADMINISTRACIÓN</strong>
    </div>

    <div class="metodo-pago">
        <strong>Método de Pago:</strong> {{ $pago->metodoPago->nombre }}<br>
        <strong>Número de Transacción:</strong><br>
        <strong>Fecha de Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
    </div>
</body>
</html>
