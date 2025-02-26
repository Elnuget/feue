<x-app-layout>
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

    <style>
        .tarjeta-contenedor {
            width: 100%;
            max-width: 900px;
            margin: 0.5rem auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 0.5rem;
            transition: background-color 0.3s ease;
        }

        /* Modo oscuro */
        :root[class~="dark"] .tarjeta-contenedor {
            background: #1f2937;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        @media (min-width: 768px) {
            .tarjeta-contenedor {
                padding: 0.75rem;
                margin: 0.75rem auto;
            }
        }

        /* Optimización específica para tablets */
        @media (min-width: 768px) and (max-width: 1023px) {
            .tarjeta-header {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .contenedor-escanner {
                max-width: 400px;
                margin: 0 auto;
            }

            .preview-container {
                height: 250px;
            }

            .info-principal {
                margin-bottom: 0.5rem;
            }

            .cursos-lista {
                margin-top: 0.5rem;
            }
        }

        .tarjeta-header {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        @media (min-width: 1024px) {
            .tarjeta-header {
                grid-template-columns: 1fr 300px;
                gap: 1rem;
                margin-bottom: 1rem;
            }
        }

        /* Contenedor principal de la sección del usuario */
        .contenedor-usuario {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            width: 100%;
        }

        /* Contenedor de la información principal */
        .info-principal {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.5rem;
            background: #f8fafc;
            padding: 0.5rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        :root[class~="dark"] .info-principal {
            background: #111827;
            border-color: #374151;
        }

        .contenedor-imagen {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        @media (min-width: 640px) {
            .info-principal {
                gap: 1rem;
                padding: 1rem;
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
                width: 140px;
                height: 140px;
            }
        }

        .info-usuario {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-destacada {
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        @media (min-width: 640px) {
            .info-destacada {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .tarjeta-info {
            background: #ffffff;
            padding: 0.75rem;
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
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 0.25rem;
        }

        :root[class~="dark"] .numero-grande {
            color: #60a5fa;
        }

        @media (min-width: 768px) {
            .numero-grande {
                font-size: 2rem;
            }
        }

        .estado-pago {
            font-size: 1rem;
            font-weight: 600;
        }

        :root[class~="dark"] .estado-pago {
            color: #4ade80;
        }

        @media (min-width: 768px) {
            .estado-pago {
                font-size: 1.25rem;
            }
        }

        .estado-pago.pendiente {
            color: #dc2626;
        }

        .estado-pago.al-dia {
            color: #16a34a;
        }

        .cursos-lista {
            margin-top: 0.5rem;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .cursos-lista {
                margin-top: 0.75rem;
            }
        }

        .curso-item {
            padding: 0.5rem;
        }

        @media (min-width: 768px) {
            .curso-item {
                padding: 0.75rem;
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
            position: relative;
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
            height: 180px;
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .preview-container {
                height: 220px;
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
            width: 120px;
            height: 120px;
            border: 2px solid #ff0000;
            border-radius: 8px;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 10;
        }

        @media (min-width: 768px) {
            .scan-region-highlight {
                width: 150px;
                height: 150px;
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
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        @media (min-width: 768px) {
            .ultima-asistencia {
                margin-top: 0.75rem;
                padding: 0.75rem;
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

    <div class="py-2">
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
            // Inicializar Select2 con búsqueda AJAX
            $('#usuario').select2({
                placeholder: 'Buscar usuario...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route("usuarios.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.users.map(user => ({
                                id: user.id,
                                text: user.name,
                                photo: user.profile?.photo || null
                            }))
                        };
                    },
                    cache: true
                },
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    },
                    errorLoading: function() {
                        return "Error al cargar los resultados";
                    }
                }
            }).on('select2:select', function(e) {
                cargarInformacionUsuario(e.params.data.id);
            }).on('select2:clear', function() {
                document.getElementById('datos-usuario').classList.add('hidden');
            });

            function cargarInformacionUsuario(userId) {
                if (!userId) {
                    const datosUsuario = document.getElementById('datos-usuario');
                    if (datosUsuario) {
                        datosUsuario.classList.add('hidden');
                    }
                    return;
                }

                // Restaurar la estructura HTML original
                const datosUsuario = document.getElementById('datos-usuario');
                if (!datosUsuario) {
                    console.error('Elemento datos-usuario no encontrado');
                    return;
                }

                // Guardar la estructura HTML original si no está guardada
                if (!window.estructuraOriginal) {
                    window.estructuraOriginal = datosUsuario.innerHTML;
                }

                // Mostrar loading
                datosUsuario.classList.remove('hidden');
                datosUsuario.innerHTML = `
                    <div class="flex items-center justify-center p-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <span class="ml-2">Cargando información...</span>
                    </div>
                `;

                // Cargar datos del usuario mediante AJAX
                fetch(`{{ route('usuarios.info', ['id' => ':id']) }}`.replace(':id', userId))
                    .then(response => {
                        console.log('Status:', response.status); // Debug
                        return response.json().then(data => {
                            if (!response.ok) {
                                throw new Error(data.error || 'Error del servidor');
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data);
                        if (!data.user) {
                            throw new Error('No se recibieron datos del usuario');
                        }
                        // Restaurar estructura original antes de actualizar
                        datosUsuario.innerHTML = window.estructuraOriginal;
                        actualizarInterfazUsuario(data);
                    })
                    .catch(error => {
                        console.error('Error detallado:', error);
                        if (datosUsuario) {
                            datosUsuario.innerHTML = `
                                <div class="p-4 text-center">
                                    <div class="text-red-600 mb-2">Error al cargar la información del usuario</div>
                                    <div class="text-sm text-gray-600">${error.message}</div>
                                    <div class="mt-2">
                                        <button onclick="cargarInformacionUsuario('${userId}')" 
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                            Intentar nuevamente
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                    });
            }

            function actualizarInterfazUsuario(data) {
                try {
                    console.log('Actualizando interfaz con datos:', data); // Debug
                    const { user, asistencias = [], matriculas = [] } = data;
                    
                    // Actualizar foto de perfil
                    const userPhotoDiv = document.getElementById('user-photo');
                    if (userPhotoDiv) {
                        userPhotoDiv.innerHTML = user.profile?.photo 
                            ? `<img src="${user.profile.photo}" alt="Foto de perfil" class="w-full h-full object-cover">`
                            : `<span class="text-2xl text-gray-600">👤</span>`;
                    }

                    // Actualizar asistencias
                    const numeroAsistencias = document.getElementById('numero-asistencias');
                    if (numeroAsistencias) {
                        numeroAsistencias.textContent = asistencias.length.toString();
                    }

                    // Actualizar última asistencia
                    if (asistencias.length > 0) {
                        const ultima = asistencias[0];
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
                        
                        const ultimaFecha = document.getElementById('ultima-fecha');
                        const ultimaEntrada = document.getElementById('ultima-entrada');
                        const ultimaSalida = document.getElementById('ultima-salida');
                        
                        if (ultimaFecha) {
                            ultimaFecha.textContent = new Date(ultima.fecha_hora).toLocaleDateString('es-EC', options);
                        }
                        if (ultimaEntrada) {
                            ultimaEntrada.textContent = ultima.hora_entrada ? 
                                new Date(ultima.hora_entrada).toLocaleTimeString('es-EC', timeOptions) : '-';
                        }
                        if (ultimaSalida) {
                            ultimaSalida.textContent = ultima.hora_salida ? 
                                new Date(ultima.hora_salida).toLocaleTimeString('es-EC', timeOptions) : '-';
                        }
                    }

                    // Actualizar cursos matriculados
                    const cursosContainer = document.getElementById('cursos-matriculados');
                    if (cursosContainer) {
                        cursosContainer.innerHTML = matriculas.length > 0 
                            ? matriculas.map(matricula => `
                                <div class="curso-item">
                                    <div class="curso-nombre">${matricula.curso?.nombre || 'Curso sin nombre'}</div>
                                    <div class="curso-detalles">
                                        <div><i class="fas fa-clock mr-1"></i>${matricula.curso?.horario || 'Horario no definido'}</div>
                                        <div><i class="fas fa-map-marker-alt mr-1"></i>${matricula.curso?.sede || 'Sede Principal'}</div>
                                    </div>
                                </div>
                            `).join('')
                            : `<div class="p-4 text-sm italic text-gray-500">No hay cursos registrados</div>`;
                    }

                    // Actualizar estado de pagos
                    const valoresPendientesElement = document.getElementById('valores-pendientes');
                    if (valoresPendientesElement) {
                        const totalPendiente = matriculas.reduce((total, matricula) => {
                            return total + (parseFloat(matricula.valor_pendiente) || 0);
                        }, 0);

                        valoresPendientesElement.textContent = totalPendiente > 0 ? 'Valores Pendientes' : 'Al Día';
                        valoresPendientesElement.className = `estado-pago ${totalPendiente > 0 ? 'pendiente' : 'al-dia'}`;
                    }

                    // Asegurarse de que el contenedor esté visible
                    const datosUsuario = document.getElementById('datos-usuario');
                    if (datosUsuario) {
                        datosUsuario.classList.remove('hidden');
                    }

                } catch (error) {
                    console.error('Error al actualizar la interfaz:', error);
                    throw error; // Propagar el error para manejarlo en el catch superior
                }
            }

            // Inicializar escáner
            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview'),
                mirror: false,
                scanPeriod: 5
            });

            scanner.addListener('scan', function(content) {
                processQRCode(content);
            });

            function processQRCode(content) {
                const resultDiv = document.getElementById('result');
                const scanRegion = document.querySelector('.scan-region-highlight');

                resultDiv.style.display = 'block';
                resultDiv.innerHTML = 'Procesando código QR...';
                scanRegion.classList.add('success');

                // Cargar información del usuario y registrar asistencia
                Promise.all([
                    cargarInformacionUsuario(content),
                    registrarAsistencia(content)
                ]).catch(error => {
                    console.error('Error:', error);
                    resultDiv.innerHTML = 'Error al procesar el código QR';
                    resultDiv.className = 'text-red-600';
                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                        scanRegion.classList.remove('success');
                    }, 3000);
                });
            }

            function registrarAsistencia(userId) {
                const horaActual = new Date();
                horaActual.toLocaleString('es-EC', { timeZone: 'America/Guayaquil' });

                return fetch('{{ route('asistencias.registerScan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        data: userId,
                        hora_actual: horaActual.toISOString()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('result');
                    resultDiv.innerHTML = data.message;
                    
                    if (data.success) {
                        resultDiv.className = 'text-green-600';
                        if (data.tipo === 'salida') {
                            setTimeout(() => {
                                resultDiv.innerHTML = '¡Que tenga un buen día! 👋';
                            }, 2000);
                        }
                        
                        // Recargar los datos del usuario después de 5 segundos
                        setTimeout(() => {
                            cargarInformacionUsuario(userId);
                        }, 5000);
                    } else {
                        resultDiv.className = 'text-red-600';
                        setTimeout(() => {
                            resultDiv.style.display = 'none';
                            document.querySelector('.scan-region-highlight').classList.remove('success');
                        }, 4000);
                    }
                });
            }

            // Inicialización de cámaras
            Instascan.Camera.getCameras().then(cameras => {
                if (cameras.length > 0) {
                    const camerasSelect = document.getElementById('cameras');
                    camerasSelect.innerHTML = '<option value="">Seleccionar cámara...</option>';
                    
                    cameras.forEach((camera, i) => {
                        const option = document.createElement('option');
                        option.value = i;
                        option.text = camera.name || `Cámara ${i + 1}`;
                        camerasSelect.add(option);
                    });

                    // Seleccionar cámara por defecto
                    let selectedCamera;
                    
                    if (isTablet()) {
                        selectedCamera = cameras.find(camera => 
                            camera.name && (
                                camera.name.toLowerCase().includes('front') ||
                                camera.name.toLowerCase().includes('user') ||
                                camera.name.toLowerCase().includes('selfie')
                            )
                        );
                    } else {
                        selectedCamera = cameras.find(camera => 
                            camera.name && camera.name.toLowerCase().includes('back')
                        );
                    }

                    if (!selectedCamera) {
                        selectedCamera = cameras[0];
                    }

                    camerasSelect.value = cameras.indexOf(selectedCamera);
                    scanner.start(selectedCamera).catch(console.error);

                    camerasSelect.addEventListener('change', function(e) {
                        if (scanner) {
                            scanner.stop();
                        }
                        scanner.start(cameras[e.target.value]).catch(console.error);
                    });
                }
            }).catch(console.error);

            function isTablet() {
                return window.innerWidth >= 768 && window.innerWidth <= 1024;
            }
        });
    </script>
    @endpush
</x-app-layout>
