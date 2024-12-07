<!-- resources/views/layouts/navigation.blade.php -->
<div :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed inset-y-0 left-0 w-48 bg-gray-900 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 overflow-y-auto transition-transform duration-300 transform h-full z-20">
    <div class="flex items-center justify-between px-4 py-4">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
        </a>
        <!-- Botón de Toggle -->
        <button @click="open = !open" class="text-gray-500 dark:text-gray-400 focus:outline-none">
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
        @if(auth()->user()->hasRole(1))
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-gray-500 dark:text-gray-400 focus:outline-none">
                    <span>{{ __('General') }}</span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="mt-2 space-y-1 pl-4">
                    @foreach([
                        ['route' => 'dashboard', 'label' => __('Dashboard')],
                        ['route' => 'roles.index', 'label' => __('Roles')],
                        ['route' => 'users.index', 'label' => __('Users')],
                        ['route' => 'estados_academicos.index', 'label' => __('Estados Académicos')],
                        ['route' => 'universidades.index', 'label' => __('Universidades')],
                        ['route' => 'metodos_pago.index', 'label' => __('Métodos de Pago')],
                        ['route' => 'user_profiles.index', 'label' => __('User Profiles')],
                        ['route' => 'user_academicos.index', 'label' => __('User Académicos')],
                        ['route' => 'user_aspiraciones.index', 'label' => __('User Aspiraciones')],
                        ['route' => 'documentos.index', 'label' => __('Documentos')],
                    ] as $item)
                        <x-nav-link :href="route($item['route'])" :active="request()->routeIs($item['route'])">
                            {{ $item['label'] }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ahora cursos y tipos de cursos son menús principales con emojis -->
        @if(auth()->user()->hasRole(1))
            <x-nav-link :href="route('tipos_cursos.index')" :active="request()->routeIs('tipos_cursos.index')">
                🎨 {{ __('Tipos de Cursos') }}
            </x-nav-link>
            <x-nav-link :href="route('cursos.index')" :active="request()->routeIs('cursos.index')">
                📚 {{ __('Cursos') }}
            </x-nav-link>
        @endif

        <x-nav-link :href="route('matriculas.index')" :active="request()->routeIs('matriculas.index')">
            📝 {{ __('Matrículas') }}
        </x-nav-link>
        <x-nav-link :href="route('pagos.index')" :active="request()->routeIs('pagos.index')">
            💳 {{ __('Pagos') }}
        </x-nav-link>
    </nav>
</div>
