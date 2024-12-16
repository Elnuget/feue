<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Escanear QR') }}
        </h2>
    </x-slot>

    <style>
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .preview-container {
            position: relative;
            width: 100%;
            height: 300px;
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
            width: 200px;
            height: 200px;
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
            padding: 1rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            margin: 0.5rem;
            border-radius: 4px;
        }
        .camera-select {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background: white;
        }
        .scanner-header {
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .scanner-footer {
            padding: 1rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="scanner-container">
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
            
            // Añadir clase de éxito
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
                    scanner.stop();
                    setTimeout(() => {
                        window.location.href = '{{ route('asistencias.index') }}';
                    }, 2000);
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
    </script>
    @endpush
</x-app-layout>