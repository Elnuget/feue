<x-app-layout>
    @if(!auth()->user()->hasRole(1))
        <script>window.location = "{{ route('dashboard') }}";</script>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Escanear QR y Asistencias') }}
        </h2>
    </x-slot>

    <style>
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .preview-container {
            position: relative;
            width: 100%;
            height: 240px;
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
            background: rgba(0, 0, 0, 0.8);
            color: white;
            margin: 0.5rem;
            border-radius: 4px;
        }
        .camera-select {
            width: 100%;
            padding: 0.25rem;
            margin-bottom: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background: white;
        }
        .scanner-header, .scanner-footer {
            padding: 0.5rem;
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .scanner-header {
            border-bottom: 1px solid #e2e8f0;
        }
        .scanner-footer {
            border-top: 1px solid #e2e8f0;
        }
        /* Update the image styling */
        #user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Remove border-radius if present */
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjeta Unificada -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <!-- Primera fila: Combobox (arriba), Imagen (abajo), Escáner a la derecha -->
                <div class="flex flex-col md:flex-row items-start justify-between gap-4">
                    <!-- Contenedor Izquierdo: Combobox y debajo Imagen -->
                    <div class="flex-1 flex flex-col gap-4">
                        <!-- Combobox -->
                        <div>
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
                        <!-- Imagen debajo del Combobox -->
                        <div id="user-photo" class="w-48 h-48 overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center mx-auto border-4 border-gray-300 dark:border-gray-600 shadow-lg">
                            <span class="text-2xl text-gray-600 dark:text-gray-400">👤</span>
                        </div>
                    </div>

                    <!-- Área del Escáner a la derecha -->
                    <div class="scanner-container flex-shrink-0">
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
                            <div id="result" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <!-- Fin primera fila -->

                <!-- Datos del usuario debajo -->
                <div id="datos-usuario" class="hidden mt-8 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Cursos Matriculados -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-graduation-cap mr-2"></i>Cursos Matriculados
                            </h3>
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                <ul id="cursos-matriculados" class="list-disc list-inside text-gray-600 dark:text-gray-300">
                                    <li class="text-sm italic text-gray-400">No hay cursos registrados</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Número de Asistencias -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-check-circle mr-2"></i>Asistencias Registradas
                            </h3>
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-bold text-blue-600 dark:text-blue-400" id="numero-asistencias">0</span>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">asistencias totales</span>
                                </div>
                            </div>
                        </div>

                        <!-- Valores Pendientes -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-dollar-sign mr-2"></i>Valores Pendientes
                            </h3>
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                <div class="flex items-baseline">
                                    <span class="text-2xl font-bold text-red-600 dark:text-red-400">$</span>
                                    <span class="text-2xl font-bold text-red-600 dark:text-red-400" id="valores-pendientes">0.00</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">en matrículas</p>
                            </div>
                        </div>

                        <!-- Estado de Matrículas -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-clipboard-check mr-2"></i>Estado de Matrículas
                            </h3>
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                <ul id="estado-matriculas" class="space-y-2 text-gray-600 dark:text-gray-300">
                                    <li class="text-sm italic text-gray-400">No hay matrículas registradas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin de Datos del usuario -->
            </div>
            <!-- Fin de Tarjeta Unificada -->
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
                scanPeriod: 5 // escanea cada 5 ms
            });

            scanner.addListener('scan', function(content) {
                processQRCode(content);
            });

            // Poblar el selector de cámaras
            const selectCameras = document.getElementById('cameras');
            cameras.forEach((camera, i) => {
                const option = document.createElement('option');
                option.value = i;
                option.text = camera.name;
                selectCameras.add(option);
            });

            // Iniciar con la cámara trasera si está disponible
            let selectedCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
            if (!selectedCamera) {
                selectedCamera = cameras[0];
            }

            scanner.start(selectedCamera);

            // Cambiar de cámara cuando se seleccione una diferente
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
                    // Seleccionar automáticamente el usuario en el combobox
                    const usuarioSelect = document.getElementById('usuario');
                    usuarioSelect.value = content; // El content es el ID del usuario
                    // Disparar el evento change para actualizar los datos
                    usuarioSelect.dispatchEvent(new Event('change'));
                    
                    // Mostrar el mensaje de éxito
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML = 'Asistencia registrada correctamente';
                    
                    // Remover la clase success después de 3 segundos
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

        // Inicializar el scanner
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

        // Actualizar datos de usuario
        document.addEventListener('DOMContentLoaded', function() {
            const users = @json($users);
            const matriculas = @json($matriculas);
            const asistencias = @json($asistencias);

            // Referencias a elementos del DOM
            const usuarioSelect = document.getElementById('usuario');
            const userPhotoDiv = document.getElementById('user-photo');
            const datosUsuarioDiv = document.getElementById('datos-usuario');
            const cursosMatriculadosUl = document.getElementById('cursos-matriculados');
            const numeroAsistenciasP = document.getElementById('numero-asistencias');
            const valoresPendientesP = document.getElementById('valores-pendientes');
            const estadoMatriculasUl = document.getElementById('estado-matriculas');

            usuarioSelect.addEventListener('change', function() {
                const userId = parseInt(usuarioSelect.value);
                if (userId) {
                    // Actualizar foto de perfil
                    const selectedOption = usuarioSelect.options[usuarioSelect.selectedIndex];
                    const photoUrl = selectedOption.dataset.photo;
                    actualizarFotoPerfil(photoUrl);

                    // Filtrar matriculas del usuario
                    const userMatriculas = matriculas.filter(m => m.usuario && m.usuario.id === userId);

                    // Cursos Matriculados
                    cursosMatriculadosUl.innerHTML = '';
                    if (userMatriculas.length > 0) {
                        userMatriculas.forEach(matricula => {
                            if (matricula.curso) {
                                cursosMatriculadosUl.innerHTML += `
                                    <li class="text-sm text-gray-600 dark:text-gray-300">${matricula.curso.nombre}</li>
                                `;
                            }
                        });
                    } else {
                        cursosMatriculadosUl.innerHTML = `
                            <li class="text-sm italic text-gray-400">No hay cursos registrados</li>
                        `;
                    }

                    // Asistencias
                    const userAsistencias = asistencias.filter(a => a.user_id === userId);
                    numeroAsistenciasP.textContent = userAsistencias.length;

                    // Valores pendientes
                    const totalPendiente = userMatriculas.reduce((total, matricula) => {
                        return total + (parseFloat(matricula.valor_pendiente) || 0);
                    }, 0);
                    valoresPendientesP.textContent = totalPendiente.toFixed(2);

                    // Estado de Matrículas
                    estadoMatriculasUl.innerHTML = '';
                    if (userMatriculas.length > 0) {
                        userMatriculas.forEach(matricula => {
                            if (matricula.curso) {
                                const estadoClass = parseFloat(matricula.valor_pendiente) > 0 ? 'text-red-600' : 'text-green-600';
                                const estadoText = parseFloat(matricula.valor_pendiente) > 0 ? 'Pendiente' : 'Pagado';
                                estadoMatriculasUl.innerHTML += `
                                    <li class="text-sm ${estadoClass}">${matricula.curso.nombre}: ${estadoText}</li>
                                `;
                            }
                        });
                    } else {
                        estadoMatriculasUl.innerHTML = `
                            <li class="text-sm italic text-gray-400">No hay matrículas registradas</li>
                        `;
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
                                userPhotoDiv.innerHTML = `<img src="${photoUrl}" alt="Foto de perfil" class="w-full h-full object-cover">`;
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
