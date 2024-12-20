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
            padding: 1.5rem;
        }

        .tarjeta-header {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: flex-start;
            justify-content: space-between;
        }

        /* Contenedor principal de la sección del usuario (combobox, foto e info) */
        .contenedor-usuario {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
            min-width: 320px;
        }

        /* Contendrá la foto a la izquierda y la info a la derecha en una sola fila */
        .info-usuario-contenedor {
            display: flex;
            align-items: flex-start;
            gap: 2rem;
            /* Ensure there's enough space for the image and data */
            flex-wrap: nowrap;
        }

        .contenedor-combobox {
            margin-bottom: 1rem;
        }

        .contenedor-imagen {
            width: 200px; /* Increased from 150px */
            height: 200px; /* Increased from 150px */
            background: #f1f5f9;
            display: flex;
            border: 3px solid #cbd5e1;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
        }

        /* Estilo para la sección de información del usuario en una sola fila */
        .info-usuario-items {
            display: flex;
            flex-direction: column; /* Arrange items in rows */
            gap: 1rem; /* Adjust gap between rows */
            flex: 1; /* Allow the items to take up remaining space */
        }

        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            min-width: 200px;
            flex: 1;
            /* Ensure each item spans the full width */
            width: 100%;
        }

        .info-item h3 {
            margin-bottom: 0.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-item h3 i {
            color: #4b5563;
        }

        .info-item ul, .info-item p, .info-item div {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #4b5563;
        }

        /* Escáner */
        .contenedor-escanner {
            flex: 1;
            min-width: 320px;
            max-width: 400px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .scanner-header, .scanner-footer {
            background: #f8fafc;
            border-color: #e2e8f0;
            padding: 0.75rem;
        }

        .scanner-header {
            border-bottom: 1px solid #e2e8f0;
        }

        .scanner-footer {
            border-top: 1px solid #e2e8f0;
        }

        .camera-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background: #ffffff;
        }

        .preview-container {
            position: relative;
            width: 100%;
            height: 250px;
            background: #000;
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
            transition: border-color 0.3s ease;
        }

        .scan-region-highlight.success {
            border-color: #00ff00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .scan-region-highlight::before,
        .scan-region-highlight::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid;
            border-color: inherit;
        }

        .scan-region-highlight::before {
            top: -2px;
            left: -2px;
            border-right: none;
            border-bottom: none;
        }

        .scan-region-highlight::after {
            bottom: -2px;
            right: -2px;
            border-left: none;
            border-top: none;
        }

        #result {
            position: relative;
            padding: 0.5rem;
            text-align: center;
            background: rgba(0,0,0,0.8);
            color: white;
            border-radius: 4px;
            font-size: 0.9rem;
            display: none;
        }

        #user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Ocultar al inicio */
        #datos-usuario.hidden {
            display: none;
        }
    </style>

    <div class="py-4">
        <div class="tarjeta-contenedor">
            <!-- Contenedor principal -->
            <div class="tarjeta-header">
                <!-- Columna Izquierda: Combobox + Foto + Info Usuario (en fila) -->
                <div class="contenedor-usuario">
                    <!-- Combobox -->
                    <div class="contenedor-combobox">
                        <select id="usuario" class="w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                        data-photo="{{ $user->profile && $user->profile->photo ? asset('storage/' . $user->profile->photo) : '' }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contenedor foto + info -->
                    <div class="info-usuario-contenedor">
                        <!-- Foto del usuario -->
                        <div id="user-photo" class="contenedor-imagen">
                            <span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>
                        </div>

                        <!-- Datos del usuario en fila -->
                        <div id="datos-usuario" class="info-usuario-items hidden">
                            <div class="info-item">
                                <h3><i class="fas fa-graduation-cap"></i>Cursos Matriculados</h3>
                                <div class="border-t border-gray-200 pt-2">
                                    <ul id="cursos-matriculados" class="list-disc list-inside">
                                        <li class="text-sm italic text-gray-400">No hay cursos registrados</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="info-item">
                                <h3><i class="fas fa-check-circle"></i>Asistencias Registradas</h3>
                                <div class="border-t border-gray-200 pt-2 flex items-baseline">
                                    <span class="text-3xl font-bold text-blue-600" id="numero-asistencias">0</span>
                                    <span class="ml-2 text-sm">asistencias totales</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <h3><i class="fas fa-dollar-sign"></i>Valores Pendientes</h3>
                                <div class="border-t border-gray-200 pt-2">
                                    <span id="valores-pendientes" class="font-bold text-2xl"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Escáner -->
                <div class="contenedor-escanner">
                    <div class="scanner-header">
                        <select class="camera-select" id="cameras">
                            <option value="">Seleccionar cámara...</option>
                        </select>
                    </div>
                    <div class="preview-container">
                        <video id="preview"></video>
                        <div class="scan-region-highlight" id="scanRegion"></div>
                    </div>
                    <div class="scanner-footer">
                        <div id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        let scanner = null;
        
        function startScanner(cameras) {
            scanner = new Instascan.Scanner({
                video: document.getElementById('preview'),
                mirror: false,
                scanPeriod: 5 
            });

            scanner.addListener('scan', function(content) {
                processQRCode(content);
            });

            const selectCameras = document.getElementById('cameras');
            cameras.forEach((camera, i) => {
                const option = document.createElement('option');
                option.value = i;
                option.text = camera.name;
                selectCameras.add(option);
            });

            let selectedCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
            if (!selectedCamera) {
                selectedCamera = cameras[0];
            }

            scanner.start(selectedCamera);

            selectCameras.addEventListener('change', function(e) {
                scanner.start(cameras[e.target.value]);
            });
        }

        function processQRCode(content) {
            const resultDiv = document.getElementById('result');
            const scanRegion = document.getElementById('scanRegion');

            resultDiv.style.display = 'block';
            resultDiv.innerHTML = 'Procesando código QR...';
            scanRegion.classList.add('success');

            fetch('{{ route('asistencias.registerScan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ data: content })
            })
            .then(response => response.json())
            .then(data => {
                resultDiv.innerHTML = data.message;
                if (data.success) {
                    const usuarioSelect = document.getElementById('usuario');
                    usuarioSelect.value = content;
                    usuarioSelect.dispatchEvent(new Event('change'));
                    
                    resultDiv.innerHTML = 'Asistencia registrada correctamente';

                    // Increment attendance count in UI
                    const attendanceCountElement = document.getElementById('numero-asistencias');
                    let currentCount = parseInt(attendanceCountElement.textContent);
                    attendanceCountElement.textContent = currentCount + 1;

                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                        scanRegion.classList.remove('success');
                    }, 3000);
                } else {
                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                        scanRegion.classList.remove('success');
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = 'Error al procesar el código QR';
                setTimeout(() => {
                    resultDiv.style.display = 'none';
                    scanRegion.classList.remove('success');
                }, 3000);
            });
        }

        Instascan.Camera.getCameras()
            .then(cameras => {
                if (cameras.length > 0) {
                    startScanner(cameras);
                } else {
                    console.error('No se encontraron cámaras.');
                    document.getElementById('result').innerHTML = 'No se encontraron cámaras disponibles.';
                }
            })
            .catch(error => {
                console.error('Error al acceder a las cámaras:', error);
                document.getElementById('result').innerHTML = 'Error al acceder a las cámaras.';
            });

        document.addEventListener('DOMContentLoaded', function() {
            const users = @json($users);
            const matriculas = @json($matriculas);
            const asistencias = @json($asistencias);

            const usuarioSelect = document.getElementById('usuario');
            const userPhotoDiv = document.getElementById('user-photo');
            const datosUsuarioDiv = document.getElementById('datos-usuario');
            const cursosMatriculadosUl = document.getElementById('cursos-matriculados');
            const numeroAsistenciasP = document.getElementById('numero-asistencias');
            const valoresPendientesP = document.getElementById('valores-pendientes');

            usuarioSelect.addEventListener('change', function() {
                const userId = parseInt(usuarioSelect.value);
                if (userId) {
                    const selectedOption = usuarioSelect.options[usuarioSelect.selectedIndex];
                    const photoUrl = selectedOption.dataset.photo;
                    actualizarFotoPerfil(photoUrl);

                    const userMatriculas = matriculas.filter(m => m.usuario && m.usuario.id === userId);

                    cursosMatriculadosUl.innerHTML = '';
                    if (userMatriculas.length > 0) {
                        userMatriculas.forEach(matricula => {
                            if (matricula.curso) {
                                cursosMatriculadosUl.innerHTML += `
                                    <li class="text-sm">${matricula.curso.nombre}</li>
                                `;
                            }
                        });
                    } else {
                        cursosMatriculadosUl.innerHTML = `
                            <li class="text-sm italic text-gray-400">No hay cursos registrados</li>
                        `;
                    }

                    const userAsistencias = asistencias.filter(a => a.user_id === userId);
                    numeroAsistenciasP.textContent = userAsistencias.length;

                    const totalPendiente = userMatriculas.reduce((total, matricula) => {
                        return total + (parseFloat(matricula.valor_pendiente) || 0);
                    }, 0);
                    if (totalPendiente > 0) {
                        valoresPendientesP.textContent = 'Existen valores pendientes';
                        valoresPendientesP.classList.remove('text-green-600');
                        valoresPendientesP.classList.add('text-red-600');
                    } else {
                        valoresPendientesP.textContent = 'Al día';
                        valoresPendientesP.classList.remove('text-red-600');
                        valoresPendientesP.classList.add('text-green-600');
                    }

                    datosUsuarioDiv.classList.remove('hidden');
                } else {
                    actualizarFotoPerfil('');
                    datosUsuarioDiv.classList.add('hidden');
                }
            });

            function actualizarFotoPerfil(photoUrl) {
                if (photoUrl) {
                    fetch(photoUrl)
                        .then(response => {
                            if (response.ok) {
                                userPhotoDiv.innerHTML = `<img src="${photoUrl}" alt="Foto de perfil">`;
                            } else {
                                userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
                            }
                        })
                        .catch(() => {
                            userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
                        });
                } else {
                    userPhotoDiv.innerHTML = `<span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>`;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
