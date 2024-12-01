<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Display user profile details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->phone }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Birth Date') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->birth_date }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Gender') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->gender }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Photo') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->photo }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Cedula') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->cedula }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Street Address') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->direccion_calle }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('City') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->direccion_ciudad }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Province') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->direccion_provincia }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Postal Code') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->codigo_postal }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Reference Number') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->numero_referencia }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Last Login At') }}</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $profile->last_login_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>