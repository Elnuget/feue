<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acuerdo de Confidencialidad - Docente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 2cm;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            text-align: justify;
            margin-bottom: 30px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid black;
            margin: 10px auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ACUERDO DE CONFIDENCIALIDAD PARA DOCENTES</h1>
    </div>

    <div class="content">
        <p>En la ciudad de {{ $fecha['ciudad'] }}, a los {{ $fecha['dia'] }} días del mes de {{ $fecha['mes'] }} del año {{ $fecha['año'] }}.</p>

        <p>Yo, <strong>{{ $usuario->name }}</strong>, con documento de identidad {{ $usuario->userProfile->cedula }}, 
        en mi calidad de DOCENTE, me comprometo a:</p>

        <ol>
            <li>Mantener absoluta confidencialidad sobre toda la información académica, administrativa y personal a la que tenga acceso durante mi desempeño como docente.</li>
            <li>No divulgar información sensible relacionada con estudiantes, otros docentes, personal administrativo o procesos internos de la institución.</li>
            <li>Proteger y mantener la confidencialidad de los materiales didácticos, evaluaciones y recursos educativos proporcionados o desarrollados.</li>
            <li>No utilizar la información confidencial para beneficio personal o de terceros.</li>
            <li>Reportar cualquier violación de confidencialidad de la que tenga conocimiento.</li>
            <li>Asegurar que todos los documentos y materiales confidenciales sean manejados y almacenados de manera segura.</li>
            <li>Devolver todos los materiales confidenciales al finalizar mi relación laboral con la institución.</li>
        </ol>

        <p>Entiendo y acepto que este acuerdo de confidencialidad permanecerá vigente durante mi relación con la institución y después de su terminación. El incumplimiento de este acuerdo puede resultar en acciones disciplinarias y/o legales según corresponda.</p>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <p>{{ $usuario->name }}</p>
        <p>CI: {{ $usuario->userProfile->cedula }}</p>
        <p>Fecha: {{ $fecha['dia'] }}/{{ $fecha['mes'] }}/{{ $fecha['año'] }}</p>
    </div>
</body>
</html> 