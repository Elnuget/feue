
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Listas de Matriculados') }}</title>
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
    <h1>{{ __('Listas de Matriculados') }}</h1>
    <h2>{{ $curso->nombre }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>{{ __('Nombre del Matriculado') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matriculas as $index => $matricula)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $matricula->usuario->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>