<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acuerdo de Confidencialidad - {{ $usuario->name }}</title>
    <style>
        @page {
            size: portrait;
            margin: 1.5cm;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.4;
            font-size: 11px;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            background-image: url("{{ public_path('favicon.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.1;
            z-index: -1;
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
            margin-top: 1rem;
            position: relative;
            page-break-inside: avoid;
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
            margin-top: 20px;
        }
        .cedula {
            margin-bottom: 5px;
        }
        .date-line {
            position: absolute;
            left: 0;
            margin-top: 60px;
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
        <div class="title">ACUERDO DE CONFIDENCIALIDAD, NO DIVULGACIÓN Y PROTECCIÓN DE INFORMACIÓN</div>
        <div class="title">DE DATOS DE CURSOS DE FORMACIÓN CONTINUA</div>
    </div>

    <div class="content">
        <p class="paragraph">
            ENTRE: Innovakap, con domicilio en la ciudad de Quito, con RUC 1793225297001 en adelante "EL CENTRO" y el ciudadano {{ $usuario->name }} con número de identificación {{ $usuario->userProfile->cedula ?? '___________' }} domiciliado en {{ trim($usuario->userProfile->direccion_calle . ' ' . $usuario->userProfile->direccion_ciudad . ', ' . $usuario->userProfile->direccion_provincia) ?? '___________________' }}, en adelante "ESTUDIANTE".
        </p>

        <p class="paragraph">
            <span class="bold">CONSIDERANDO QUE:</span> EL CENTRO brindará capacitación en el curso de Auxiliar de Enfermería, y EL ESTUDIANTE se compromete a participar en dicho curso, con ética y probidad y siempre bajo el principio de buena fe y, ambas partes acuerdan los términos siguientes:
        </p>

        <p class="paragraph">
            <span class="bold">PRIMERO: Objeto del Acuerdo</span><br>
            El presente Acuerdo tiene como finalidad proteger la confidencialidad de toda la información que EL CENTRO brinde a EL ESTUDIANTE durante el curso de auxiliar de enfermería, así como cualquier material, metodología, estrategia o contenido que no sea de dominio público.
        </p>

        <p class="paragraph">
            <span class="bold">SEGUNDO: Alcance de la Información Protegida</span><br>
            Para efectos de este Acuerdo, se entenderá por Información Confidencial:<br>
            Todo el contenido del curso, incluyendo pero no limitado a materiales de estudio, presentaciones, grabaciones, y cualquier otra información relacionada con la capacitación. Cualquier información técnica o comercial que se considere confidencial o sensible y que no sea de dominio público.
        </p>

        <p class="paragraph">
            <span class="bold">TERCERO: Obligaciones de Confidencialidad de EL ESTUDIANTE.</span><br>
            EL ESTUDIANTE se obliga a no revelar, comunicar, distribuir o poner a disposición de terceros, por cualquier medio, la Información Confidencial de EL CENTRO, sin el consentimiento expreso y por escrito de EL CENTRO.<br>
            EL ESTUDIANTE se compromete a tomar todas las precauciones necesarias para evitar la divulgación o el uso no autorizado de la Información Confidencial, incluso después de la finalización del curso. EL ESTUDIANTE se obliga a devolver cualquier material o información confidencial a EL CENTRO al finalizar el curso y, subvencionar de manera pecuniaria y económica.
        </p>

        <p class="paragraph">
            <span class="bold">CUARTO: Excepciones a la Confidencialidad</span><br>
            Las obligaciones de confidencialidad establecidas en este Acuerdo no se aplicarán a información que: Sea de dominio público en el momento de la divulgación o se haga pública posteriormente sin que ello implique incumplimiento por parte de EL ESTUDIANTE. Deba ser revelada por mandato judicial o por orden de una autoridad competente, en cuyo caso EL ESTUDIANTE notificará a EL CENTRO con la debida antelación.
        </p>

        <p class="paragraph">
            <span class="bold">QUINTO: Vigencia del Acuerdo.</span> Las obligaciones de confidencialidad permanecerán vigentes durante toda la duración del curso y continuarán vigentes por un período adicional de 5 años a partir de la finalización del mismo.
        </p>

        <p class="paragraph">
            <span class="bold">SEXTO: Consecuencias del Incumplimiento.</span> En caso de incumplimiento de alguna de las obligaciones de confidencialidad, EL ESTUDIANTE se compromete a indemnizar a EL CENTRO por cualquier daño, perjuicio o pérdida que pueda derivarse del incumplimiento con 100 salarios básicos unificados.
        </p>

        <p class="paragraph">
            <span class="bold">SÉPTIMO: Legislación Aplicable y Jurisdicción.</span> Este Acuerdo se rige por las leyes del Ecuador y las partes se someten a los tribunales competentes de Quito.
        </p>

        <p class="paragraph">
            <span class="bold">OCTAVO: Compromiso.</span> EL ESTUDIANTE se compromete a seguir todas las indicaciones y actuar con probidad, respeto y acudir a todas las actividades académicas del curso.
        </p>

        <p class="paragraph">
            <span class="bold">Aceptación.</span> Ambas partes declaran haber leído y comprendido los términos de este Acuerdo y manifiestan su conformidad firmando.
        </p>
    </div>

    <div class="signatures-container">
        <div class="signature-left">
            <div class="signature-line">
                <div class="cedula">RUC: 1793225297001</div>
                Innovakap
            </div>
        </div>
        <div class="signature-right">
            <div class="signature-line">
                <div class="cedula">C.C: {{ $usuario->userProfile->cedula ?? '___________' }}</div>
                {{ $usuario->name }}
            </div>
        </div>
        <div class="date-line">
            FECHA: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </div>
    </div>
</body>
</html> 