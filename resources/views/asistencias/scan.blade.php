<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Escanear QR') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div id="reader"></div>
                <div id="result" class="mt-4 text-center text-gray-700 dark:text-gray-300"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            try {
                const userId = decodedText;
                fetch('{{ route('asistencias.registerScan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('result').innerHTML = data.message;
                    setTimeout(() => {
                        window.location.href = '{{ route('asistencias.index') }}';
                    }, 2000);
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
    @endpush
</x-app-layout>