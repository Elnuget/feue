<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acuerdo de Confidencialidad - {{ $usuario->name }}</title>
    <style>
        @page {
            size: portrait;
            margin: 2cm;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .content {
            text-align: justify;
            margin-bottom: 2rem;
        }
        .signature-section {
            margin-top: 4rem;
            text-align: center;
        }
        .signature-line {
            width: 50%;
            margin: 0 auto;
            border-top: 1px solid black;
            padding-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">ACUERDO DE CONFIDENCIALIDAD</div>
        <div>{{ $curso->nombre }}</div>
    </div>

    <div class="content">
        <p>En la ciudad de Quito, a los {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [días del mes de] MMMM [del] YYYY') }}, yo {{ $usuario->name }}, 
        con documento de identidad {{ $usuario->cedula ?? 'N/A' }}, en mi calidad de estudiante del curso "{{ $curso->nombre }}", 
        me comprometo a mantener la más estricta confidencialidad sobre toda la información, documentos, metodologías y materiales 
        proporcionados durante el desarrollo del curso.</p>

        <p>Me comprometo específicamente a:</p>
        <ol>
            <li>No divulgar ninguna información confidencial recibida durante el curso.</li>
            <li>No reproducir ni distribuir los materiales del curso sin autorización expresa.</li>
            <li>Utilizar la información únicamente para los fines académicos del curso.</li>
            <li>Mantener la confidencialidad incluso después de finalizado el curso.</li>
        </ol>

        <p>Entiendo que el incumplimiento de este acuerdo puede resultar en acciones legales y/o académicas.</p>
    </div>

    <div class="signature-section">
        <div class="signature-line">
            {{ $usuario->name }}<br>
            CI: {{ $usuario->cedula ?? 'N/A' }}
        </div>
    </div>

    <div class="footer">
        Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html> 