<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Certificados</title>
  <style>
    /* Configura el tamaño exacto de la página y quita márgenes. */
    @page {
      /* Usamos medidas exactas en mm en vez de "A4 landscape" */
      size: 297mm 210mm; 
      margin: 0;
    }
    /* Quitamos márgenes y padding del HTML y body */
    html, body {
      margin: 0;
      padding: 0;
      /* No es obligatorio, pero a veces ayuda a ciertos conversores */
      width: 297mm;
      height: 210mm;
    }
    /* Cada certificado será una “página” de 297×210 mm */
    .certificate-page {
      width: 297mm;
      height: 210mm;
      margin: 0; 
      padding: 0;
      box-sizing: border-box;

      /* Forzamos que cada certificado se imprima en una página distinta */
      page-break-after: always;
      page-break-inside: avoid;

      /* Centramos el contenido */
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    /* Evitar un salto de página extra al final */
    .certificate-page:last-child {
      page-break-after: auto;
    }
  </style>
</head>
<body>
  @foreach($matriculas as $matricula)
    <div class="certificate-page">
      <div>
        <h1>Certificado de Culminación</h1>
        <p>Se certifica que:</p>
        <p><strong>{{ $matricula->usuario->name }}</strong></p>
        <p>Ha completado satisfactoriamente el curso:</p>
        <p>{{ $curso->nombre }}</p>
        <p>Con una duración de {{ $curso->horas }} horas</p>
        <p>Horario: {{ $curso->horario }}</p>
        <p>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</p>
      </div>
    </div>
  @endforeach
</body>
</html>
