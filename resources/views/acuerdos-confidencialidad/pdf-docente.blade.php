<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acuerdo de Confidencialidad - Docente</title>
    <style>
        @page {
            size: portrait;
            margin: 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 1rem;
        }
        .title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }
        .content {
            text-align: justify;
            margin-bottom: 1rem;
        }
        .signatures-container {
            width: 100%;
            margin-top: 2rem;
            position: relative;
        }
        .signature-left {
            position: absolute;
            left: 0;
            width: 40%;
        }
        .signature-right {
            position: absolute;
            right: 0;
            width: 40%;
        }
        .signature-line {
            border-top: 1px solid black;
            padding-top: 5px;
            margin-top: 30px;
        }
        .cedula {
            margin-bottom: 5px;
        }
        .date-line {
            position: absolute;
            left: 0;
            margin-top: 80px;
        }
        .paragraph {
            margin-bottom: 0.7rem;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">

        <div class="title">ESTE ACUERDO AUN SE ESTA PREPARANDO NO FIRMAR NI SUBIR</div>
    </div>

    <div class="content">
        <p class="paragraph">
            En la ciudad de {{ $fecha['ciudad'] }}, a los {{ $fecha['dia'] }} días del mes de {{ $fecha['mes'] }} del año {{ $fecha['año'] }}, comparecen a la celebración del presente acuerdo:
        </p>

       

       

    <div class="signatures-container">
        <div class="signature-left">
            <div class="signature-line">
                <div class="cedula">C.C: 1718239153</div>
                Ab. Erik Barba<br>
                REPRESENTANTE LEGAL
            </div>
        </div>
        <div class="signature-right">
            <div class="signature-line">
                <div class="cedula">C.C: {{ $usuario->userProfile->cedula ?? '___________' }}</div>
                {{ $usuario->name }}<br>
                DOCENTE
            </div>
        </div>
        <div class="date-line">
            FECHA: {{ $fecha['dia'] }}/{{ $fecha['mes'] }}/{{ $fecha['año'] }}
        </div>
    </div>
</body>
</html> 