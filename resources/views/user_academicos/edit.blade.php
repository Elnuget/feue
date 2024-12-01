<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User Academico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('user_academicos.update', $userAcademico) }}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="user_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('User') }}</label>
                            <input id="user_id" class="block mt-1 w-full" type="text" name="user_id" value="{{ $userAcademico->user_id }}" required autofocus />
                        </div>
                        <div class="mt-4">
                            <label for="estado_academico_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Estado Academico') }}</label>
                            <input id="estado_academico_id" class="block mt-1 w-full" type="text" name="estado_academico_id" value="{{ $userAcademico->estado_academico_id }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="acta_grado" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Acta Grado') }}</label>
                            <input id="acta_grado" class="block mt-1 w-full" type="text" name="acta_grado" value="{{ $userAcademico->acta_grado }}" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button class="ml-4 bg-blue-500 text-white px-4 py-2 rounded">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>