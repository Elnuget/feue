<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('user_profiles.update', $profile->user_id) }}">
                        @csrf
                        @method('PATCH')
                        <!-- Form fields for user profile editing -->
                        <div class="mb-4">
                            <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                            <input id="phone" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="phone" value="{{ $profile->phone }}" />
                        </div>
                        <div class="mb-4">
                            <label for="birth_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Birth Date') }}</label>
                            <input id="birth_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="date" name="birth_date" value="{{ $profile->birth_date }}" />
                        </div>
                        <div class="mb-4">
                            <label for="gender" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Gender') }}</label>
                            <select id="gender" name="gender" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <option value="Masculino" {{ $profile->gender == 'Masculino' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                                <option value="Femenino" {{ $profile->gender == 'Femenino' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                                <option value="Otro" {{ $profile->gender == 'Otro' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="photo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Photo') }}</label>
                            <input id="photo" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="photo" value="{{ $profile->photo }}" />
                        </div>
                        <div class="mb-4">
                            <label for="cedula" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Cedula') }}</label>
                            <input id="cedula" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="cedula" value="{{ $profile->cedula }}" />
                        </div>
                        <div class="mb-4">
                            <label for="direccion_calle" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Street Address') }}</label>
                            <input id="direccion_calle" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="direccion_calle" value="{{ $profile->direccion_calle }}" />
                        </div>
                        <div class="mb-4">
                            <label for="direccion_ciudad" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('City') }}</label>
                            <input id="direccion_ciudad" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="direccion_ciudad" value="{{ $profile->direccion_ciudad }}" />
                        </div>
                        <div class="mb-4">
                            <label for="direccion_provincia" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Province') }}</label>
                            <input id="direccion_provincia" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="direccion_provincia" value="{{ $profile->direccion_provincia }}" />
                        </div>
                        <div class="mb-4">
                            <label for="codigo_postal" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Postal Code') }}</label>
                            <input id="codigo_postal" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="codigo_postal" value="{{ $profile->codigo_postal }}" />
                        </div>
                        <div class="mb-4">
                            <label for="numero_referencia" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Reference Number') }}</label>
                            <input id="numero_referencia" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="numero_referencia" value="{{ $profile->numero_referencia }}" />
                        </div>
                        <div class="mb-4">
                            <label for="last_login_at" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Last Login At') }}</label>
                            <input id="last_login_at" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="datetime-local" name="last_login_at" value="{{ $profile->last_login_at ? $profile->last_login_at->format('Y-m-d\TH:i') : '' }}" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="ml-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>