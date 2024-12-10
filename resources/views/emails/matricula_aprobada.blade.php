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
</body>
</html>