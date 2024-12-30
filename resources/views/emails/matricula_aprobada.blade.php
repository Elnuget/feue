<!DOCTYPE html>
<html>
<head>
    <title>Matrícula Aprobada</title>
</head>
<body>
    <h1>¡Felicidades, {{ $matricula->usuario->name }}!</h1>
    <p>Te has matriculado exitosamente en el curso <strong>{{ $matricula->curso->nombre }}</strong>.</p>
    <p>Detalles de la matrícula:</p>
    <ul>
        <li>Fecha de Matrícula: {{ $matricula->fecha_matricula }}</li>
        <li>Precio del Curso: ${{ $matricula->monto_total }}</li>
    </ul>
    <p>¡Esperamos que disfrutes del curso!</p>
    <p>Si tienes alguna pregunta, no dudes en contactarnos a través de nuestro correo electrónico: soporte@ejemplo.com</p>
    <p>Saludos cordiales,</p>
    <p>El equipo de Ejemplo</p>
    <hr>
    <p style="font-size: 0.8em; color: #555;">Gracias por confiar en nosotros. Este es un correo generado automáticamente, por favor no respondas a este mensaje.</p>
</body>
</html>