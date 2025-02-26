<x-app-layout>
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

    <style>
        .tarjeta-contenedor {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 1rem;
            transition: background-color 0.3s ease;
        }

        /* Modo oscuro */
        :root[class~="dark"] .tarjeta-contenedor {
            background: #1f2937;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        @media (min-width: 768px) {
            .tarjeta-contenedor {
                padding: 1.5rem;
            }
        }

        /* Optimización específica para tablets */
        @media (min-width: 768px) and (max-width: 1023px) {
            .tarjeta-header {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .contenedor-escanner {
                max-width: 600px;
                margin: 0 auto;
            }

            .preview-container {
                height: 400px;
            }
        }

        .tarjeta-header {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        @media (min-width: 1024px) {
            .tarjeta-header {
                grid-template-columns: 1fr 400px;
                gap: 2rem;
                margin-bottom: 2rem;
            }
        }

        /* Contenedor principal de la sección del usuario */
        .contenedor-usuario {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
        }

        /* Contenedor de la información principal */
        .info-principal {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        :root[class~="dark"] .info-principal {
            background: #111827;
            border-color: #374151;
        }

        .contenedor-imagen {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: #ffffff;
            border: 3px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        @media (min-width: 640px) {
            .info-principal {
                grid-template-columns: auto 1fr;
                gap: 2rem;
                padding: 1.5rem;
            }
        }

        .contenedor-imagen {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: #ffffff;
            border: 3px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        @media (min-width: 640px) {
            .contenedor-imagen {
                width: 150px;
                height: 150px;
                margin: 0;
            }
        }

        @media (min-width: 1024px) {
            .contenedor-imagen {
                width: 180px;
                height: 180px;
            }
        }

        .info-usuario {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-destacada {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        @media (min-width: 640px) {
            .info-destacada {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .tarjeta-info {
            background: #ffffff;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        :root[class~="dark"] .tarjeta-info {
            background: #1f2937;
            border-color: #374151;
        }

        .tarjeta-info.asistencias {
            background: #f0f9ff;
            border-color: #93c5fd;
        }

        :root[class~="dark"] .tarjeta-info.asistencias {
            background: #1e3a8a;
            border-color: #2563eb;
        }

        .tarjeta-info.pagos {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        :root[class~="dark"] .tarjeta-info.pagos {
            background: #991b1b;
            border-color: #dc2626;
        }

        .numero-grande {
            font-size: 2rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 0.5rem;
        }

        :root[class~="dark"] .numero-grande {
            color: #60a5fa;
        }

        @media (min-width: 768px) {
            .numero-grande {
                font-size: 2.5rem;
            }
        }

        .estado-pago {
            font-size: 1.25rem;
            font-weight: 600;
        }

        :root[class~="dark"] .estado-pago {
            color: #4ade80;
        }

        @media (min-width: 768px) {
            .estado-pago {
                font-size: 1.5rem;
            }
        }

        .estado-pago.pendiente {
            color: #dc2626;
        }

        .estado-pago.al-dia {
            color: #16a34a;
        }

        .cursos-lista {
            margin-top: 1rem;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .cursos-lista {
                margin-top: 1.5rem;
            }
        }

        .curso-item {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        @media (min-width: 768px) {
            .curso-item {
                padding: 1rem;
            }
        }

        .curso-nombre {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        :root[class~="dark"] .curso-nombre {
            color: #e5e7eb;
        }

        .curso-detalles {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        :root[class~="dark"] .curso-detalles {
            color: #9ca3af;
        }

        @media (min-width: 640px) {
            .curso-detalles {
                flex-direction: row;
                gap: 2rem;
                font-size: 0.9rem;
            }
        }

        /* Escáner */
        .contenedor-escanner {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            transition: all 0.3s ease;
        }

        :root[class~="dark"] .contenedor-escanner {
            background: #1f2937;
            border-color: #374151;
        }

        .scanner-header {
            background: #f8fafc;
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        :root[class~="dark"] .scanner-header {
            background: #111827;
            border-color: #374151;
        }

        .preview-container {
            position: relative;
            width: 100%;
            height: 250px;
            background: #000;
        }

        @media (min-width: 768px) {
            .preview-container {
                height: 300px;
            }
        }

        #preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scan-region-highlight {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            border: 2px solid #ff0000;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        @media (min-width: 768px) {
            .scan-region-highlight {
                width: 200px;
                height: 200px;
            }
        }

        .scan-region-highlight.success {
            border-color: #22c55e;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
        }

        #result {
            padding: 0.75rem;
            text-align: center;
            background: #1e293b;
            color: white;
            font-size: 0.875rem;
            display: none;
        }

        @media (min-width: 768px) {
            #result {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        .ultima-asistencia {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        @media (min-width: 768px) {
            .ultima-asistencia {
                margin-top: 1rem;
                padding: 1rem;
            }
        }

        .ultima-asistencia-titulo {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        :root[class~="dark"] .ultima-asistencia-titulo {
            color: #e5e7eb;
        }

        .ultima-asistencia-detalles {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        @media (min-width: 640px) {
            .ultima-asistencia-detalles {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                font-size: 0.9rem;
            }
        }

        .estado-asistencia {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        @media (min-width: 768px) {
            .estado-asistencia {
                padding: 0.25rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        .estado-presente {
            background: #dcfce7;
            color: #16a34a;
        }

        .estado-tardanza {
            background: #fef9c3;
            color: #ca8a04;
        }

        .estado-ausente {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Ocultar al inicio */
        #datos-usuario.hidden {
            display: none;
        }

        :root[class~="dark"] #datos-usuario.hidden {
            display: none;
        }
    </style>

    <div class="py-4">
        <div class="tarjeta-contenedor">
            <div class="tarjeta-header">
                <!-- Columna Izquierda: Información del Usuario -->
                <div class="contenedor-usuario">
                    <!-- Selector de Usuario -->
                    <select id="usuario" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccione un usuario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    data-photo="{{ $user->profile && $user->profile->photo ? asset('storage/' . $user->profile->photo) : '' }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Información Principal -->
                    <div id="datos-usuario" class="hidden">
                        <div class="info-principal">
                            <!-- Foto del Usuario -->
                            <div id="user-photo" class="contenedor-imagen">
                                <span class="text-2xl text-gray-600">👤</span>
                            </div>

                            <!-- Información Destacada -->
                            <div class="info-destacada">
                                <!-- Asistencias -->
                                <div class="tarjeta-info asistencias">
                                    <h3 class="text-lg font-semibold text-blue-900">Asistencias Registradas</h3>
                                    <div class="numero-grande" id="numero-asistencias">0</div>
                                    <div class="ultima-asistencia">
                                        <div class="ultima-asistencia-titulo">Última Asistencia</div>
                                        <div class="ultima-asistencia-detalles">
                                            <div>
                                                <div class="font-medium">Fecha</div>
                                                <div id="ultima-fecha">-</div>
                                            </div>
                                            <div>
                                                <div class="font-medium">Entrada/Salida</div>
                                                <div>
                                                    <span id="ultima-entrada">-</span> /
                                                    <span id="ultima-salida">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pagos -->
                                <div class="tarjeta-info pagos">
                                    <h3 class="text-lg font-semibold text-red-900">Estado de Pagos</h3>
                                    <div id="valores-pendientes" class="estado-pago">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Cursos -->
                        <div class="cursos-lista mt-4">
                            <h3 class="text-lg font-semibold p-4 bg-gray-50 border-b border-gray-200">
                                Cursos Matriculados
                            </h3>
                            <div id="cursos-matriculados" class="divide-y divide-gray-200">
                                <div class="p-4 text-sm italic text-gray-500">
                                    No hay cursos registrados
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Escáner -->
                <div class="contenedor-escanner">
                    <div class="scanner-header">
                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="cameras">
                            <option value="">Seleccionar cámara...</option>
                        </select>
                    </div>
                    <div class="preview-container">
                        <video id="preview"></video>
                        <div class="scan-region-highlight"></div>
                    </div>
                    <div id="result"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const users = @json($users);
            const matriculas = @json($matriculas);
            const asistencias = @json($asistencias);

            // Inicializar Select2
            $('#usuario').select2({
                placeholder: 'Buscar usuario...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            }).on('select2:select', function (e) {
                actualizarInformacionUsuario(parseInt(e.target.value));
            }).on('select2:clear', function () {
                document.getElementById('datos-usuario').classList.add('hidden');
            });

            function actualizarInformacionUsuario(userId) {
                if (!userId) {
                    document.getElementById('datos-usuario').classList.add('hidden');
                    return;
                }

                const user = users.find(u => u.id === userId);
                if (!user) return;

                // Mostrar la sección de datos
                document.getElementById('datos-usuario').classList.remove('hidden');

                // Actualizar foto de perfil
                const selectedOption = document.getElementById('usuario').options[document.getElementById('usuario').selectedIndex];
                const photoUrl = selectedOption.dataset.photo;
                const userPhotoDiv = document.getElementById('user-photo');
                
                if (photoUrl) {
                    fetch(photoUrl)
                        .then(response => {
                            if (response.ok) {
                                userPhotoDiv.innerHTML = `<img src="${photoUrl}" alt="Foto de perfil" class="w-full h-full object-cover">`;
                            } else {
                                userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600">👤</span>`;
                            }
                        })
                        .catch(() => {
                            userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600">👤</span>`;
                        });
                } else {
                    userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600">👤</span>`;
                }

                // Actualizar asistencias
                const userAsistencias = asistencias.filter(a => a.user_id === userId);
                document.getElementById('numero-asistencias').textContent = userAsistencias.length;

                // Actualizar última asistencia
                if (userAsistencias.length > 0) {
                    const ultima = userAsistencias[userAsistencias.length - 1];
                    const options = { 
                        timeZone: 'America/Guayaquil',
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric'
                    };
                    const timeOptions = {
                        timeZone: 'America/Guayaquil',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    };
                    
                    document.getElementById('ultima-fecha').textContent = new Date(ultima.fecha_hora).toLocaleDateString('es-EC', options);
                    document.getElementById('ultima-entrada').textContent = ultima.hora_entrada ? 
                        new Date(ultima.hora_entrada).toLocaleTimeString('es-EC', timeOptions) : '-';
                    document.getElementById('ultima-salida').textContent = ultima.hora_salida ? 
                        new Date(ultima.hora_salida).toLocaleTimeString('es-EC', timeOptions) : '-';
                }

                // Actualizar cursos matriculados
                const userMatriculas = matriculas.filter(m => m.usuario_id === userId);
                const cursosContainer = document.getElementById('cursos-matriculados');
                
                if (userMatriculas.length > 0) {
                    cursosContainer.innerHTML = userMatriculas.map(matricula => `
                        <div class="curso-item">
                            <div class="curso-nombre">${matricula.curso.nombre}</div>
                            <div class="curso-detalles">
                                <div><i class="fas fa-clock mr-1"></i>${matricula.curso.horario}</div>
                                <div><i class="fas fa-map-marker-alt mr-1"></i>${matricula.curso.sede || 'Sede Principal'}</div>
                            </div>
                        </div>
                    `).join('');
                }

                // Actualizar estado de pagos
                const totalPendiente = userMatriculas.reduce((total, matricula) => {
                    return total + (parseFloat(matricula.valor_pendiente) || 0);
                }, 0);

                const valoresPendientesElement = document.getElementById('valores-pendientes');
                if (totalPendiente > 0) {
                    valoresPendientesElement.textContent = 'Valores Pendientes';
                    valoresPendientesElement.className = 'estado-pago pendiente';
                } else {
                    valoresPendientesElement.textContent = 'Al Día';
                    valoresPendientesElement.className = 'estado-pago al-dia';
                }
            }

            // Inicializar escáner
            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview'),
                mirror: false
            });

            scanner.addListener('scan', function(content) {
                processQRCode(content);
            });

            Instascan.Camera.getCameras().then(cameras => {
                if (cameras.length > 0) {
                    const camerasSelect = document.getElementById('cameras');
                    cameras.forEach((camera, i) => {
                        const option = document.createElement('option');
                        option.value = i;
                        option.text = camera.name;
                        camerasSelect.add(option);
                    });

                    let selectedCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                    if (!selectedCamera) {
                        selectedCamera = cameras[0];
                    }

                    scanner.start(selectedCamera);

                    camerasSelect.addEventListener('change', function(e) {
                        scanner.start(cameras[e.target.value]);
                    });
                }
            });

            function processQRCode(content) {
                const resultDiv = document.getElementById('result');
                const scanRegion = document.querySelector('.scan-region-highlight');

                resultDiv.style.display = 'block';
                resultDiv.innerHTML = 'Procesando código QR...';
                scanRegion.classList.add('success');

                // Actualizar información del usuario
                $('#usuario').val(content).trigger('change');
                actualizarInformacionUsuario(parseInt(content));

                // Obtener la hora actual en la zona horaria de Guayaquil
                const horaActual = new Date();
                horaActual.toLocaleString('es-EC', { timeZone: 'America/Guayaquil' });

                // Registrar asistencia
                fetch('{{ route('asistencias.registerScan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        data: content,
                        hora_actual: horaActual.toISOString()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    resultDiv.innerHTML = data.message;
                    if (data.success) {
                        resultDiv.className = 'text-green-600';
                        if (data.tipo === 'salida') {
                            setTimeout(() => {
                                resultDiv.innerHTML = '¡Que tenga un buen día! 👋';
                            }, 2000);
                        }
                        
                        // Recargar la página después de 5 segundos
                        setTimeout(() => {
                            window.location.reload();
                        }, 5000);
                    } else {
                        resultDiv.className = 'text-red-600';
                        setTimeout(() => {
                            resultDiv.style.display = 'none';
                            scanRegion.classList.remove('success');
                        }, 4000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultDiv.innerHTML = 'Error al procesar el código QR';
                    resultDiv.className = 'text-red-600';
                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                        scanRegion.classList.remove('success');
                    }, 3000);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
