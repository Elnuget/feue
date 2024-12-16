<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('QR Code for ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 flex flex-col items-center justify-center space-y-6">
                    <!-- QR Container with white background for better contrast -->
                    <div class="bg-white p-4 rounded-lg shadow-sm" id="qr-container">
                        {!! QrCode::size(250)->generate($user->id) !!}
                    </div>
                    
                    <!-- Download Button -->
                    <button onclick="downloadQR()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out flex items-center space-x-2">
                        <i class="fas fa-download"></i>
                        <span>Descargar QR</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para descargar el QR -->
    <script>
        function downloadQR() {
            // Crear un canvas desde el SVG
            const svg = document.querySelector('#qr-container svg');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Configurar el tamaño del canvas
            canvas.width = 250;
            canvas.height = 250;
            
            // Crear una imagen desde el SVG
            const svgData = new XMLSerializer().serializeToString(svg);
            const img = new Image();
            
            img.onload = function() {
                // Dibujar fondo blanco
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                // Dibujar el QR
                ctx.drawImage(img, 0, 0);
                
                // Crear el enlace de descarga
                const a = document.createElement('a');
                a.download = 'qr-code.jpg';
                a.href = canvas.toDataURL('image/jpeg', 0.8);
                a.click();
            };
            
            img.src = 'data:image/svg+xml;base64,' + btoa(svgData);
        }
    </script>
</x-app-layout>
