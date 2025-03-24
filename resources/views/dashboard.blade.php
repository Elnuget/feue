<x-app-layout>
    <!-- Modal de Aviso -->
    <div id="avisoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-[9999] p-4 sm:p-6 md:p-8" style="display: none; position: fixed; top: 0; left: 0;">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg mx-auto max-h-[90vh] overflow-y-auto" style="position: relative; z-index: 10000;">
            <div class="p-3 sm:p-4 md:p-6">
                <div class="flex justify-between items-center mb-3 sm:mb-4 sticky top-0 bg-white z-10">
                    <h3 class="text-lg sm:text-xl font-bold text-red-600">¡ATENCIÓN!</h3>
                    <button type="button" class="text-gray-600 hover:text-gray-900 p-2" onclick="document.getElementById('avisoModal').style.display='none'">
                        <i class="fas fa-times text-base sm:text-lg"></i>
                    </button>
                </div>
                <div class="mb-3 sm:mb-4">
                    <img src="{{ asset('images/aviso.jpg') }}" alt="Aviso Importante" class="w-full h-auto object-contain max-h-[60vh]">
                </div>
                <div class="text-center sticky bottom-0 bg-white pt-2">
                    <button class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base" onclick="document.getElementById('avisoModal').style.display='none'">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            document.getElementById('avisoModal').style.display = 'flex';
        }
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if(!auth()->user()->hasRole('Docente'))
                    <a href="{{ route('matriculas.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-graduation-cap text-3xl mb-2"></i>
                        Mis Matrículas
                    </a>

                    <a href="{{ route('pagos.index') }}" class="bg-green-500 hover:bg-green-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-money-bill-wave text-3xl mb-2"></i>
                        Mis Pagos
                    </a>

                    <a href="{{ route('users.qr', auth()->id()) }}" class="bg-purple-500 hover:bg-purple-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-qrcode text-3xl mb-2"></i>
                        Mi QR
                    </a>

                    <a href="{{ route('asistencias.usuario', auth()->id()) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-calendar-check text-3xl mb-2"></i>
                        Mis Asistencias
                    </a>

                    <a href="/" class="bg-yellow-500 hover:bg-yellow-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-plus text-3xl mb-2"></i>
                        Añadir Matrícula o Ver todos los Cursos
                    </a>
                @endif

                <!-- Aulas Virtuales - Visible para todos -->
                <a href="{{ route('aulas_virtuales.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                    <i class="fas fa-laptop text-3xl mb-2"></i>
                    Aulas Virtuales
                </a>

                <!-- Horarios Docentes - Visible para docentes y admin -->
                @if(auth()->user()->hasRole('Docente') || auth()->user()->hasRole(1))
                    <a href="{{ route('sesiones-docentes.index') }}" class="bg-orange-500 hover:bg-orange-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-calendar-alt text-3xl mb-2"></i>
                        Sesiones Docentes
                    </a>

                    <a href="{{ route('matriculas.listas') }}" class="bg-red-500 hover:bg-red-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-list text-3xl mb-2"></i>
                        Listas
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
