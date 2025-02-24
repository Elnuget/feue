<x-app-layout>
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
