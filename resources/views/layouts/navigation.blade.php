<!-- resources/views/layouts/navigation.blade.php -->
<div :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed inset-y-0 left-0 w-64 bg-gray-900 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 overflow-y-auto transition-transform duration-300 transform sm:translate-x-0 sm:static sm:inset-0 h-full z-20">
    <div class="flex items-center justify-between px-4 py-4">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
        </a>
        <!-- Botón de Toggle -->
        <button @click="open = !open" class="sm:hidden text-gray-500 dark:text-gray-400 focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <!-- Ícono de menú (tres líneas) -->
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <!-- Ícono de cerrar (X) -->
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Navegación -->
    <nav class="mt-5 flex flex-col space-y-1 px-2">
        @foreach([
            ['route' => 'dashboard', 'label' => __('Dashboard')],
            ['route' => 'roles.index', 'label' => __('Roles')],
            ['route' => 'users.index', 'label' => __('Users')],
            ['route' => 'estados_academicos.index', 'label' => __('Estados Académicos')],
            ['route' => 'universidades.index', 'label' => __('Universidades')],
            ['route' => 'tipos_cursos.index', 'label' => __('Tipos de Cursos')],
            ['route' => 'metodos_pago.index', 'label' => __('Métodos de Pago')],
            ['route' => 'cursos.index', 'label' => __('Cursos')],
            ['route' => 'user_profiles.index', 'label' => __('User Profiles')],
            ['route' => 'user_academicos.index', 'label' => __('User Académicos')],
            ['route' => 'user_aspiraciones.index', 'label' => __('User Aspiraciones')],
            ['route' => 'documentos.index', 'label' => __('Documentos')],
            ['route' => 'matriculas.index', 'label' => __('Matrículas')],
            ['route' => 'pagos.index', 'label' => __('Pagos')],
        ] as $item)
            <x-nav-link :href="route($item['route'])" :active="request()->routeIs($item['route'])">
                {{ $item['label'] }}
            </x-nav-link>
        @endforeach
    </nav>
</div>
