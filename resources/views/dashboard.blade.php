<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Panel de Control</h1>
            
            <!-- Tarjetas para todos los usuarios -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Accesos Generales</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Aulas Virtuales - Visible para todos -->
                    <a href="{{ route('aulas_virtuales.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-laptop text-3xl mb-2"></i>
                        Aulas Virtuales
                    </a>

                    <!-- Mi Código QR - Visible para todos los usuarios -->
                    <a href="{{ route('users.qr', auth()->id()) }}" class="bg-purple-500 hover:bg-purple-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-qrcode text-3xl mb-2"></i>
                        Mi Código QR
                    </a>

                    <!-- Acuerdos de Confidencialidad - Visible para todos -->
                    <a href="{{ route('acuerdos-confidencialidad.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-file-alt text-3xl mb-2"></i>
                        Acuerdos de Confidencialidad
                    </a>
                </div>
            </div>

            <!-- Tarjetas para estudiantes (no docentes) -->
            @if(!auth()->user()->hasRole('Docente'))
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Área de Estudiantes</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('matriculas.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-graduation-cap text-3xl mb-2"></i>
                        Mis Matrículas
                    </a>

                    <a href="{{ route('pagos.index') }}" class="bg-green-500 hover:bg-green-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-money-bill-wave text-3xl mb-2"></i>
                        Mis Pagos
                    </a>

                    <a href="{{ route('asistencias.usuario', auth()->id()) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-calendar-check text-3xl mb-2"></i>
                        Mis Asistencias
                    </a>

                    <a href="/" class="bg-yellow-500 hover:bg-yellow-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-plus text-3xl mb-2"></i>
                        Añadir Matrícula o Ver todos los Cursos
                    </a>
                </div>
            </div>
            @endif

            <!-- Tarjetas para docentes y administradores -->
            @if(auth()->user()->hasRole('Docente') || auth()->user()->hasRole(1))
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Área de Docentes</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('sesiones-docentes.index') }}" class="bg-orange-500 hover:bg-orange-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-calendar-alt text-3xl mb-2"></i>
                        Sesiones Docentes
                    </a>

                    <a href="{{ route('matriculas.listas') }}" class="bg-red-500 hover:bg-red-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-list text-3xl mb-2"></i>
                        Listas
                    </a>
                </div>
            </div>
            @endif

            <!-- Tarjetas solo para administradores -->
            @if(auth()->user()->hasRole(1))
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Área de Administración</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('asistencias-docentes.index') }}" class="bg-teal-500 hover:bg-teal-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-check-circle text-3xl mb-2"></i>
                        Asistencias Docentes
                    </a>

                    <a href="{{ route('credenciales-docentes.index') }}" class="bg-pink-500 hover:bg-pink-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-id-card text-3xl mb-2"></i>
                        Credenciales Docentes
                    </a>

                    <a href="{{ route('asistencias.index') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-clipboard-check text-3xl mb-2"></i>
                        Asistencias
                    </a>

                    <a href="{{ route('tipos_cursos.index') }}" class="bg-amber-500 hover:bg-amber-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-palette text-3xl mb-2"></i>
                        Tipos de Cursos
                    </a>

                    <a href="{{ route('cursos.index') }}" class="bg-emerald-500 hover:bg-emerald-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-book text-3xl mb-2"></i>
                        Cursos
                    </a>

                    <a href="{{ route('certificados.index') }}" class="bg-rose-500 hover:bg-rose-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-certificate text-3xl mb-2"></i>
                        Certificados
                    </a>

                    <a href="{{ route('pruebas') }}" class="bg-violet-500 hover:bg-violet-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-crown text-3xl mb-2"></i>
                        Admin
                    </a>

                    <a href="{{ route('users.index') }}" class="bg-sky-500 hover:bg-sky-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-users text-3xl mb-2"></i>
                        Usuarios
                    </a>
                </div>
            </div>

            <!-- Configuración General - Solo para administradores -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Configuración General</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('roles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-user-tag text-3xl mb-2"></i>
                        Roles
                    </a>

                    <a href="{{ route('estados_academicos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-graduation-cap text-3xl mb-2"></i>
                        Estados Académicos
                    </a>

                    <a href="{{ route('universidades.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-university text-3xl mb-2"></i>
                        Universidades
                    </a>

                    <a href="{{ route('metodos_pago.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-credit-card text-3xl mb-2"></i>
                        Métodos de Pago
                    </a>

                    <a href="{{ route('user_profiles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-user-circle text-3xl mb-2"></i>
                        User Profiles
                    </a>

                    <a href="{{ route('user_academicos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-user-graduate text-3xl mb-2"></i>
                        User Académicos
                    </a>

                    <a href="{{ route('user_aspiraciones.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-star text-3xl mb-2"></i>
                        User Aspiraciones
                    </a>

                    <a href="{{ route('documentos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-6 px-4 rounded-lg shadow-lg flex flex-col items-center">
                        <i class="fas fa-file-alt text-3xl mb-2"></i>
                        Documentos
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
