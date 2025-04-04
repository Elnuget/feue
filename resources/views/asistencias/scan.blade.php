<x-app-layout>
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

    <style>
        .tarjeta-contenedor {
            width: 100%;
            max-width: 1200px;
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

        /* Ocultar la tarjeta de asistencias para docentes */
        .tarjeta-info.asistencias.ocultar-para-docente {
            display: none;
        }

        /* Cuando es docente, modificar el layout */
        .info-destacada.modo-docente {
            grid-template-columns: 1fr;
        }

        /* La tarjeta de estadísticas ocupa todo el ancho en modo docente */
        .info-destacada.modo-docente .tarjeta-info {
            width: 100%;
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
            grid-template-columns: 300px 1fr;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 1023px) {
            .tarjeta-header {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }

        /* Contenedor principal de la sección del usuario */
        .contenedor-usuario {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            height: 100%;
            overflow-y: auto;
        }

        /* Contenedor de la información principal */
        .info-principal {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            align-items: start;
            margin-bottom: 1rem;
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
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 1279px) {
            .info-destacada {
                grid-template-columns: 1fr;
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
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-top: 0;
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
            height: 300px;
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        @media (max-width: 1023px) {
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
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        @media (min-width: 768px) {
            #result {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        .ultima-asistencia {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .ultima-asistencia-titulo {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .ultima-asistencia-detalles {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        @media (min-width: 1280px) {
            .ultima-asistencia-detalles {
                grid-template-columns: repeat(3, 1fr);
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
                <!-- Columna Izquierda: Escáner -->
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

                <!-- Columna Derecha: Información del Usuario -->
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

                            <div class="w-full">
                                <!-- Nombre del Usuario -->
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-3 user-name" id="user-name">Usuario</h2>
                                
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
                return new Promise((resolve, reject) => {
                    if (!userId) {
                        const datosUsuario = document.getElementById('datos-usuario');
                        if (datosUsuario) {
                            datosUsuario.classList.add('hidden');
                        }
                        reject(new Error('ID de usuario no válido'));
                        return;
                    }

                    // Restaurar la estructura HTML original
                    const datosUsuario = document.getElementById('datos-usuario');
                    if (!datosUsuario) {
                        console.error('Elemento datos-usuario no encontrado');
                        reject(new Error('Elemento datos-usuario no encontrado'));
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
                            resolve(data);
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
                            reject(error);
                        });
                });
            }

            function actualizarInterfazUsuario(data) {
                try {
                    console.log('Actualizando interfaz con datos:', data); // Debug
                    const { user, asistencias = [], matriculas = [], sesiones = [] } = data;
                    
                    // Verificar si el usuario es docente
                    const esDocente = user.roles && user.roles.some(role => role.name === 'Docente');
                    console.log('Es docente:', esDocente);
                    
                    // Actualizar nombre de usuario
                    const userNameElement = document.getElementById('user-name');
                    if (userNameElement && user.name) {
                        // Mostrar el nombre y un badge de rol si es docente
                        if (esDocente) {
                            userNameElement.innerHTML = `
                                ${user.name} 
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Docente
                                </span>
                            `;
                        } else {
                            userNameElement.textContent = user.name;
                        }
                        userNameElement.dataset.esDocente = esDocente.toString();
                    }
                    
                    // Ocultar/mostrar la tarjeta de asistencias según el tipo de usuario
                    const tarjetaAsistencias = document.querySelector('.tarjeta-info.asistencias');
                    if (tarjetaAsistencias) {
                        if (esDocente) {
                            tarjetaAsistencias.classList.add('ocultar-para-docente');
                        } else {
                            tarjetaAsistencias.classList.remove('ocultar-para-docente');
                        }
                    }
                    
                    // Aplicar clase modo-docente al contenedor de información destacada
                    const infoDestacada = document.querySelector('.info-destacada');
                    if (infoDestacada) {
                        if (esDocente) {
                            infoDestacada.classList.add('modo-docente');
                        } else {
                            infoDestacada.classList.remove('modo-docente');
                        }
                    }
                    
                    // Actualizar foto de perfil
                    const userPhotoDiv = document.getElementById('user-photo');
                    if (userPhotoDiv) {
                        userPhotoDiv.innerHTML = user.profile?.photo 
                            ? `<img src="${user.profile.photo}" alt="Foto de perfil" class="w-full h-full object-cover">`
                            : `<span class="text-2xl text-gray-600">👤</span>`;
                    }

                    // Actualizar asistencias - Contar todas las asistencias registradas
                    const numeroAsistencias = document.getElementById('numero-asistencias');
                    if (numeroAsistencias) {
                        // Contar todas las asistencias que tengan al menos hora_entrada o hora_salida
                        const totalAsistencias = asistencias.filter(asistencia => 
                            asistencia.fecha_hora || asistencia.hora_entrada || asistencia.hora_salida
                        ).length;
                        numeroAsistencias.textContent = totalAsistencias.toString();
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
                            // Usar fecha o fecha_hora dependiendo del tipo de usuario
                            const fechaAsistencia = esDocente ? ultima.fecha : ultima.fecha_hora;
                            ultimaFecha.textContent = new Date(fechaAsistencia).toLocaleDateString('es-EC', options);
                        }
                        if (ultimaEntrada) {
                            // Usar hora_entrada directamente para ambos tipos de usuarios
                            ultimaEntrada.textContent = ultima.hora_entrada ? 
                                new Date(ultima.hora_entrada).toLocaleTimeString('es-EC', timeOptions) : '-';
                        }
                        if (ultimaSalida) {
                            if (esDocente) {
                                // Para docentes, no mostramos hora de salida
                                ultimaSalida.textContent = '-';
                                
                                // Cambiamos el título para mostrar solo "Entrada"
                                const entradaSalidaLabel = ultimaSalida.parentElement?.previousElementSibling;
                                if (entradaSalidaLabel) {
                                    entradaSalidaLabel.textContent = 'Entrada';
                                }
                            } else {
                                // Para estudiantes, mostramos hora de salida normalmente
                                ultimaSalida.textContent = ultima.hora_salida ? 
                                    new Date(ultima.hora_salida).toLocaleTimeString('es-EC', timeOptions) : '-';
                            }
                        }
                    }

                    // Sección de información variable según el tipo de usuario
                    const cursosContainer = document.getElementById('cursos-matriculados');
                    const valoresPendientesElement = document.getElementById('valores-pendientes');

                    // Obtener el mes actual en formato YYYY-MM
                    const currentDate = new Date();
                    const currentMonth = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}`;
                    
                    if (esDocente) {
                        // Para docentes, actualizar la sección de cursos para mostrar información de sesiones
                        const cursosLista = document.querySelector('.cursos-lista h3');
                        if (cursosLista) {
                            cursosLista.textContent = 'Sesiones y Asistencias del Mes';
                        }
                        
                        // Actualizar la sección de pagos a información de asistencias
                        if (valoresPendientesElement) {
                            const totalSesiones = sesiones.length || 0;
                            
                            // Asistencias del docente en el mes actual
                            // En este caso, las asistencias para docentes vienen directamente como asistencias en la respuesta
                            const asistenciasDelMes = asistencias.filter(a => {
                                // Verificar si los datos de asistencia vienen con fecha o fecha_hora
                                const fechaAsistencia = a.fecha ? new Date(a.fecha) : 
                                                      (a.fecha_hora ? new Date(a.fecha_hora) : null);
                                if (!fechaAsistencia) return false;
                                
                                const mesAsistencia = `${fechaAsistencia.getFullYear()}-${String(fechaAsistencia.getMonth() + 1).padStart(2, '0')}`;
                                return mesAsistencia === currentMonth;
                            }).length;
                            
                            const porcentajeAsistencia = totalSesiones > 0 ? Math.round((asistenciasDelMes / totalSesiones) * 100) : 0;
                            
                            // Cambiar el título de la tarjeta
                            const estadoPagosTitulo = document.querySelector('.tarjeta-info.pagos h3');
                            if (estadoPagosTitulo) {
                                estadoPagosTitulo.textContent = 'Estadísticas del Mes';
                                estadoPagosTitulo.className = 'text-lg font-semibold text-blue-900';
                            }
                            
                            // Cambiar el fondo de la tarjeta
                            const tarjetaPagos = document.querySelector('.tarjeta-info.pagos');
                            if (tarjetaPagos) {
                                tarjetaPagos.className = 'tarjeta-info asistencias';
                            }
                            
                            // Actualizar contenido
                            valoresPendientesElement.innerHTML = `
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Sesiones</div>
                                        <div class="text-lg font-bold text-blue-700">${totalSesiones}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Asistencias</div>
                                        <div class="text-lg font-bold text-green-700">${asistenciasDelMes}</div>
                                    </div>
                                    <div class="col-span-2">
                                        <div class="text-sm font-medium text-gray-500">Porcentaje</div>
                                        <div class="flex items-center mt-1">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="h-2.5 rounded-full ${porcentajeAsistencia >= 90 ? 'bg-green-500' : porcentajeAsistencia >= 70 ? 'bg-yellow-500' : porcentajeAsistencia >= 50 ? 'bg-orange-500' : 'bg-red-500'}" 
                                                     style="width: ${porcentajeAsistencia}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">${porcentajeAsistencia}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 border-t pt-2 text-sm text-blue-800 bg-blue-50 p-2 rounded">
                                    <i class="fas fa-info-circle mr-1"></i> Los docentes pueden registrar múltiples asistencias durante el día.
                                </div>
                            `;
                            valoresPendientesElement.className = 'p-3';
                        }
                        
                        // Mostrar lista de sesiones
                        if (cursosContainer) {
                            if (sesiones.length > 0) {
                                cursosContainer.innerHTML = sesiones
                                    .sort((a, b) => new Date(b.fecha) - new Date(a.fecha))
                                    .map(sesion => {
                                        const fechaSesion = new Date(sesion.fecha);
                                        const fechaFormateada = fechaSesion.toLocaleDateString('es-EC', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric'
                                        });
                                        
                                        // Filtrar todas las asistencias para esta sesión (puede haber múltiples en un día)
                                        const asistenciasSesion = asistencias.filter(a => {
                                            // Obtener la fecha de la asistencia según su estructura
                                            const fechaAsistencia = a.fecha ? 
                                                new Date(a.fecha).toLocaleDateString('es-EC') : 
                                                (a.fecha_hora ? new Date(a.fecha_hora).toLocaleDateString('es-EC') : null);
                                            
                                            if (!fechaAsistencia) return false;
                                            
                                            // Fecha de la sesión para comparar
                                            const fechaSesionStr = fechaSesion.toLocaleDateString('es-EC');
                                            
                                            // Verificar que las fechas coincidan
                                            return fechaAsistencia === fechaSesionStr;
                                        });
                                        
                                        // Verificar si asistió al menos una vez
                                        const asistio = asistenciasSesion.length > 0;
                                        
                                        // Obtener horas de entrada como string
                                        let horasEntrada = '';
                                        if (asistenciasSesion.length > 0) {
                                            horasEntrada = asistenciasSesion
                                                .map(a => {
                                                    const horaObj = a.hora_entrada ? 
                                                        new Date(a.hora_entrada) : null;
                                                    return horaObj ? 
                                                        horaObj.toLocaleTimeString('es-EC', {
                                                            hour: '2-digit',
                                                            minute: '2-digit',
                                                            hour12: false
                                                        }) : '';
                                                })
                                                .filter(h => h) // Eliminar valores vacíos
                                                .join(', ');
                                        }
                                        
                                        return `
                                            <div class="curso-item">
                                                <div class="curso-nombre">${sesion.curso?.nombre || 'Sesión sin curso'}</div>
                                                <div class="curso-detalles">
                                                    <div><i class="fas fa-calendar mr-1"></i>${fechaFormateada}</div>
                                                    <div><i class="fas fa-clock mr-1"></i>${sesion.hora_inicio || ''} - ${sesion.hora_fin || ''}</div>
                                                    <div class="estado-asistencia ${asistio ? 'estado-presente' : 'estado-ausente'}">
                                                        ${asistio 
                                                            ? `Asistió ${asistenciasSesion.length > 1 
                                                                ? `(${asistenciasSesion.length} entradas)` 
                                                                : ''}`
                                                            : 'No asistió'}
                                                    </div>
                                                    ${horasEntrada ? 
                                                        `<div class="mt-1 text-xs text-gray-600"><i class="fas fa-sign-in-alt mr-1"></i>Entradas: ${horasEntrada}</div>` 
                                                        : ''}
                                                </div>
                                            </div>
                                        `;
                                    }).join('');
                            } else {
                                cursosContainer.innerHTML = `<div class="p-4 text-sm italic text-gray-500">No hay sesiones registradas este mes</div>`;
                            }
                        }
                    } else {
                        // Para estudiantes, mostrar matrículas y estado de pagos (código original)
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
                        if (valoresPendientesElement) {
                            // Verificar si hay matrículas con pago mensual
                            const matriculasMensuales = matriculas.filter(m => m.tipo_pago === 'Mensual');
                            const matriculasUnicas = matriculas.filter(m => m.tipo_pago === 'Pago Único');
                            
                            // Obtener el tipo de pago de la matrícula más reciente
                            const matriculaActual = matriculas.sort((a, b) => 
                                new Date(b.created_at) - new Date(a.created_at)
                            )[0];

                            console.log('Matrícula actual:', matriculaActual); // Debug
                            
                            // Asegurarse de que el tipo de pago tenga un valor válido
                            const tipoPagoActual = matriculaActual && matriculaActual.tipo_pago 
                                ? matriculaActual.tipo_pago 
                                : (matriculaActual && matriculaActual.valor_pendiente > 0 ? 'Pago Único' : 'No especificado');
                            
                            console.log('Tipo de pago:', tipoPagoActual); // Debug
                            
                            // Obtener el último pago registrado
                            const ultimoPago = matriculas.reduce((ultimo, matricula) => {
                                if (!matricula.pagos || matricula.pagos.length === 0) return ultimo;
                                
                                const pagosMatricula = matricula.pagos
                                    .filter(p => p.estado === 'Aprobado')
                                    .sort((a, b) => new Date(b.fecha_pago) - new Date(a.fecha_pago));
                                
                                if (pagosMatricula.length === 0) return ultimo;
                                
                                const ultimoPagoMatricula = pagosMatricula[0];
                                if (!ultimo || new Date(ultimoPagoMatricula.fecha_pago) > new Date(ultimo.fecha_pago)) {
                                    return ultimoPagoMatricula;
                                }
                                return ultimo;
                            }, null);
                            
                            // Verificar pagos del mes actual para matrículas mensuales
                            const pagosDelMes = matriculasMensuales.some(matricula => {
                                return matricula.pagos && matricula.pagos.some(pago => {
                                    if (pago.estado !== 'Aprobado') return false;
                                    
                                    const fechaPago = new Date(pago.fecha_pago);
                                    const mesPago = `${fechaPago.getFullYear()}-${String(fechaPago.getMonth() + 1).padStart(2, '0')}`;
                                    return mesPago === currentMonth;
                                });
                            });
                            
                            // Calcular el total pendiente de todas las matrículas
                            const totalPendiente = matriculas.reduce((total, matricula) => {
                                return total + (parseFloat(matricula.valor_pendiente) || 0);
                            }, 0);
                            
                            // Determinar el estado de pago
                            let estadoPago = 'Al Día';
                            let claseEstado = 'al-dia';
                            
                            // Si hay matrículas mensuales, verificar primero si hay pagos del mes
                            if (matriculasMensuales.length > 0) {
                                if (pagosDelMes) {
                                    estadoPago = 'Pago Mensual Correctamente';
                                    claseEstado = 'al-dia';
                                } else {
                                    estadoPago = 'Valores Pendientes';
                                    claseEstado = 'pendiente';
                                }
                            } 
                            // Si no hay matrículas mensuales o todas están al día, verificar el total pendiente
                            else if (totalPendiente > 0) {
                                estadoPago = 'Valores Pendientes';
                                claseEstado = 'pendiente';
                            }
                            
                            // Actualizar el elemento con el estado de pago
                            valoresPendientesElement.innerHTML = `
                                <div class="estado-pago ${claseEstado}">${estadoPago}</div>
                                <div class="mt-2 text-sm">
                                    <div class="font-medium">Tipo de Pago:</div>
                                    <div class="text-gray-600">
                                        ${tipoPagoActual}
                                    </div>
                                </div>
                                ${ultimoPago ? `
                                    <div class="mt-2 text-sm">
                                        <div class="font-medium">Último Pago:</div>
                                        <div class="text-gray-600">
                                            ${new Date(ultimoPago.fecha_pago).toLocaleDateString('es-EC')} - 
                                            $${parseFloat(ultimoPago.monto).toFixed(2)}
                                        </div>
                                    </div>
                                ` : ''}
                            `;
                            
                            // Agregar información adicional sobre el tipo de pago
                            const infoPago = document.createElement('div');
                            infoPago.className = 'text-xs mt-1 text-gray-600';
                            
                            if (tipoPagoActual === 'Mensual') {
                                infoPago.innerHTML = `<i class="fas fa-info-circle mr-1"></i> Pago Mensual - ${pagosDelMes ? 'Pagado este mes' : 'Pendiente este mes'}`;
                            } else if (tipoPagoActual === 'Pago Único') {
                                infoPago.innerHTML = `<i class="fas fa-info-circle mr-1"></i> Pago Único - ${totalPendiente > 0 ? 'Pendiente' : 'Completado'}`;
                            }
                            
                            // Agregar la información solo si hay matrículas
                            if (matriculas.length > 0) {
                                valoresPendientesElement.appendChild(infoPago);
                            }
                        }
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

                // Primero cargar información del usuario, luego registrar asistencia
                cargarInformacionUsuario(content)
                    .then(() => {
                        // Ahora registrar asistencia
                        return registrarAsistencia(content);
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
                    
                    // Obtener nombre de usuario
                    let userName = '';
                    // Intentar obtener el nombre del elemento user-name
                    const userNameElement = document.getElementById('user-name');
                    if (userNameElement) {
                        userName = userNameElement.textContent || '';
                    }
                    
                    // Si no se encuentra, intentar desde el select
                    if (!userName) {
                        const userSelect = document.getElementById('usuario');
                        if (userSelect && userSelect.selectedOptions && userSelect.selectedOptions.length > 0) {
                            userName = userSelect.selectedOptions[0].text || '';
                        }
                    }
                    
                    // Usar un nombre genérico si no se encontró el nombre
                    if (!userName) {
                        userName = 'Usuario';
                    }
                    
                    // Personalizar mensaje según tipo (entrada/salida)
                    if (data.success) {
                        // Obtener si el usuario es docente del modelo user
                        const esDocente = document.getElementById('user-name')?.dataset?.esDocente === 'true';

                        // Ajustar clase y estilo según el tipo de registro
                        if (data.tipo === 'entrada') {
                            resultDiv.className = 'px-4 py-3 text-center text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-100 font-semibold rounded-b';
                            resultDiv.innerHTML = `
                                <div class="text-lg">¡Bienvenido, ${userName}!</div>
                                <div class="text-sm mt-1">${esDocente ? 'Asistencia docente registrada correctamente' : 'Asistencia registrada correctamente'}</div>
                            `;
                        } else {
                            resultDiv.className = 'px-4 py-3 text-center text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-100 font-semibold rounded-b';
                            resultDiv.innerHTML = `
                                <div class="text-lg">¡Adiós, ${userName}!</div>
                                <div class="text-sm mt-1">Que tengas un buen día 👋</div>
                            `;
                        }
                        
                        // Esconder el mensaje después de un tiempo más largo
                        setTimeout(() => {
                            resultDiv.style.display = 'none';
                            document.querySelector('.scan-region-highlight').classList.remove('success');
                        }, 6000);
                        
                        // Recargar los datos del usuario después de un tiempo
                        setTimeout(() => {
                            cargarInformacionUsuario(userId);
                        }, 5000);
                    } else {
                        resultDiv.innerHTML = data.message;
                        resultDiv.className = 'px-4 py-3 text-center text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-100 font-semibold rounded-b';
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
