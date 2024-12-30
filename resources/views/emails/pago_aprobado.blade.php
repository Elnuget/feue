<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago Aprobado</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h1 style="color: #4CAF50; text-align: center;">¡Gracias por tu pago!</h1>
        <p>Estimado/a <strong>{{ $pago->matricula->usuario->name }}</strong>,</p>
        <p>Nos complace informarte que tu pago ha sido aprobado exitosamente. Aquí tienes los detalles:</p>
        <ul>
            <li><strong>Curso:</strong> {{ $pago->matricula->curso->nombre }}</li>
            <li><strong>Monto:</strong> ${{ number_format($pago->monto, 2) }}</li>
            <li><strong>Fecha de Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d-m-Y') }}</li>
        </ul>
        <p><strong>Nota:</strong> No se aceptan devoluciones por matrícula o mensualidad bajo ninguna circunstancia.</p>
        <p>Si tienes alguna duda, por favor no dudes en contactarnos.</p>
        <p>Saludos cordiales,</p>
        <p><strong>El equipo de Cap U</strong></p>
    </div>
</body>
</html>