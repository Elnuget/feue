<x-app-layout>
    @section('page_title', 'Admin')
    <style>
        .container {
            padding: 3rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }

        @media (min-width: 768px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .card {
            background-color: #f0f8ff; /* Default pastel color */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-right: 1rem;
            color: #555; /* Icon color */
        }

        .card-content p {
            margin: 0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .card-subtitle {
            font-size: 0.875rem;
            color: #666;
        }

        .bg-blue {
            background-color: #a2d5f2;
        }

        .bg-green {
            background-color: #b9e4c9;
        }

        .bg-yellow {
            background-color: #fdfd96;
        }

        .bg-purple {
            background-color: #d4b0f7;
        }

        .bg-red {
            background-color: #f7b0b0;
        }

        .bg-gray {
            background-color: #e4e4e4;
        }

        .bg-orange {
            background-color: #ffdca8;
        }

        .bg-lime {
            background-color: #d0f0c0;
        }

        .bg-pink {
            background-color: #ffc0cb;
        }
    </style>

    <div class="container">
        <div class="grid">
            <!-- Total de Estudiantes Matriculados -->
            <div class="card bg-blue">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <p class="card-title">{{ $totalEstudiantes }}</p>
                    <p class="card-subtitle">Estudiantes Matriculados</p>
                </div>
            </div>

            <!-- Cursos Activos -->
            <div class="card bg-green">
                <div class="card-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="card-content">
                    <p class="card-title">{{ $cursosActivos }}</p>
                    <p class="card-subtitle">Cursos Activos</p>
                </div>
            </div>

            <!-- Estudiantes con Deudas Pendientes -->
            <div class="card bg-gray">
                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-content">
                    <p class="card-title">{{ $estudiantesConDeudas }}</p>
                    <p class="card-subtitle">Estudiantes con Deudas Pendientes</p>
                </div>
            </div>

            <!-- Usuarios Totales -->
            <div class="card bg-orange">
                <div class="card-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="card-content">
                    <p class="card-title">{{ $totalUsuarios }}</p>
                    <p class="card-subtitle">Usuarios Totales</p>
                </div>
            </div>

            <!-- Cursos Inactivos -->
            <div class="card bg-lime">
                <div class="card-icon">
                    <i class="fas fa-book-dead"></i>
                </div>
                <div class="card-content">
                    <p class="card-title">{{ $cursosInactivos }}</p>
                    <p class="card-subtitle">Cursos Inactivos</p>
                </div>
            </div>

            <!-- Total de Pagos Recibidos -->
            <div class="card bg-pink">
                <div class="card-icon">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="card-content">
                    <p class="card-title">{{ $pagosRecibidos }}</p>
                    <p class="card-subtitle">Total de Pagos Recibidos</p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <canvas id="matriculasChart"></canvas>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Detalles de Matrículas</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Curso</th>
                        <th class="py-2">Número de Matrículas</th>
                        <th class="py-2">Horario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matriculasPorCurso as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->curso->nombre }}</td>
                            <td class="border px-4 py-2">{{ $item->total }}</td>
                            <td class="border px-4 py-2">{{ $item->curso->horario }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('matriculasChart').getContext('2d');
        const matriculasPorCursoLabels = [
            @foreach($matriculasPorCurso as $item)
                '{{ $item->curso->nombre }}',
            @endforeach
        ];
        const matriculasPorCursoData = [
            @foreach($matriculasPorCurso as $item)
                {{ $item->total }},
            @endforeach
        ];

        // Definir una paleta de colores moderna
        const colors = [
            '#4dc9f6',
            '#f67019',
            '#f53794',
            '#537bc4',
            '#acc236',
            '#166a8f',
            '#00a950',
            '#58595b',
            '#8549ba'
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: matriculasPorCursoLabels,
                datasets: [{
                    label: 'Matrículas por Curso',
                    data: matriculasPorCursoData,
                    backgroundColor: colors.slice(0, matriculasPorCursoLabels.length),
                    borderColor: colors.slice(0, matriculasPorCursoLabels.length),
                    borderWidth: 1,
                    hoverBackgroundColor: colors.slice(0, matriculasPorCursoLabels.length).map(color => shadeColor(color, -20)),
                    hoverBorderColor: colors.slice(0, matriculasPorCursoLabels.length).map(color => shadeColor(color, -20)),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    },
                    title: {
                        display: true,
                        text: 'Número de Matrículas por Curso',
                        font: {
                            size: 18
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            precision:0
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Función para oscurecer colores
        function shadeColor(color, percent) {
            let R = parseInt(color.substring(1,3),16);
            let G = parseInt(color.substring(3,5),16);
            let B = parseInt(color.substring(5,7),16);

            R = parseInt(R * (100 + percent) / 100);
            G = parseInt(G * (100 + percent) / 100);
            B = parseInt(B * (100 + percent) / 100);

            R = (R<255)?R:255;  
            G = (G<255)?G:255;  
            B = (B<255)?B:255;  

            const RR = ((R.toString(16).length==1)?'0'+R.toString(16):R.toString(16));
            const GG = ((G.toString(16).length==1)?'0'+G.toString(16):G.toString(16));
            const BB = ((B.toString(16).length==1)?'0'+B.toString(16):B.toString(16));

            return "#"+RR+GG+BB;
        }
    </script>
</x-app-layout>
