<!DOCTYPE html>
<html>
<head>
    <title>Comprobante de Pago Aprobado</title>
</head>
<body>
    <h1>¡Gracias por tu pago!</h1>
    <p>Estimado/a {{ $pago->matricula->usuario->name }},</p>
    <p>Tu pago ha sido aprobado exitosamente. A continuación, los detalles de tu pago:</p>
    <ul>
        <li><strong>Matrícula:</strong> {{ $pago->matricula->curso->nombre }}</li>
        <li><strong>Monto:</strong> {{ $pago->monto }}</li>
        <li><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago }}</li>
    </ul>
    <p>Gracias por tu confianza.</p>
    <p>Saludos,</p>
    <p>El equipo de FEUE</p>
</body>
</html>