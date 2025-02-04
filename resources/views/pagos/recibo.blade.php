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
            margin-bottom: 100px; /* Aumentado el espacio después del horario */
            color: #000000;
            font-weight: bold;
        }
        .nota {
            margin-top: 30px;
            margin-bottom: 100px; /* Aumentado el espacio después de la nota */
        }
        .firma {
            margin-bottom: 15px;
            text-align: left;
        }
        .firma-final {
            margin-top: 100px; /* Manteniendo el mismo espacio que el anterior */
        }
        .metodo-pago {
            margin-top: 20px;
        }
    </style>
</head>
<body>
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
            $cedula = $userProfile->cedula ?? '1751550193';
            
            if ($gender === 'Masculino') {
                $tratamiento = 'el señor';
            } elseif ($gender === 'Femenino') {
                $tratamiento = 'la señora';
            } else {
                $tratamiento = 'el/la señor(a)';
            }
        @endphp
        {{ $tratamiento }} <strong>{{ strtoupper($pago->matricula->usuario->name) }}</strong> con 
        CI#<strong>{{ $cedula }}</strong> de
        <strong>${{ number_format($pago->monto, 2) }}</strong> dólares americanos en 
        <strong>{{ $pago->metodoPago->nombre }}</strong> por pago 
        CURSO <strong>{{ strtoupper($pago->matricula->curso->nombre) }}</strong>
    </div>

    <div class="contenido horario">
        PRESENCIAL DE LUNES A VIERNES DE 17H00 A 19H00
    </div>

    <div class="contenido nota">
        <strong>Nota:</strong> No hay posibilidad de devolución por ninguna causa.
    </div>

    <div class="firma">
        <strong>{{ strtoupper($pago->matricula->usuario->name) }}</strong><br>
        <strong>ENTREGA</strong>
    </div>

    <div class="firma firma-final">
        <strong>CENTRO DE CAPACITACIONES UNIVERSITARIO</strong><br>
        <strong>RECIBE DALTON HEREDIA</strong>
    </div>

    <div class="metodo-pago">
        <strong>{{ $pago->metodoPago->nombre }}</strong>
    </div>
</body>
</html> 