<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Academico Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <strong>{{ __('User:') }}</strong> {{ $userAcademico->user->name }}
                    </div>
                    <div class="mb-4">
                        <strong>{{ __('Estado Academico:') }}</strong> {{ $userAcademico->estadoAcademico->name }}
                    </div>
                    <div class="mb-4">
                        <strong>{{ __('Acta Grado:') }}</strong> {{ $userAcademico->acta_grado }}
                    </div>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('user_academicos.edit', $userAcademico) }}" class="btn btn-secondary bg-blue-500 text-white px-4 py-2 rounded">{{ __('Edit') }}</a>
                        <form action="{{ route('user_academicos.destroy', $userAcademico) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger bg-red-500 text-white px-4 py-2 rounded">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>