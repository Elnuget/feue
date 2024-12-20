<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Credenciales de Matriculados') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>{{ __('Credenciales de Matriculados') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>{{ __('Nombre del Matriculado') }}</th>
                <th>{{ __('Valor Pendiente') }}</th>
                <th>{{ __('Valor Pendiente en Moneda') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matriculas as $index => $matricula)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $matricula->usuario->name }}</td>
                    <td>{{ $matricula->valor_pendiente > 0 ? 'Sí' : 'No' }}</td>
                    <td>${{ number_format($matricula->valor_pendiente, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
