<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado Generado</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748; margin-bottom: 20px;">¡Felicidades!</h2>
        
        <p>Le informamos que ha aprobado satisfactoriamente el curso <strong>{{ $certificado->nombre_curso }}</strong>.</p>
        
        <p>Para descargar o visualizar su certificado, haga clic en el siguiente enlace:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('certificados.show', $certificado->id) }}" 
               style="background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">
                Ver Certificado
            </a>
        </div>
        
        <p>Gracias por su dedicación y esfuerzo. Si tiene alguna duda o requiere más información, no dude en contactarnos.</p>
        
        <p style="margin-top: 30px;">Atentamente,<br>Cap Universitario</p>
    </div>
</body>
</html> 