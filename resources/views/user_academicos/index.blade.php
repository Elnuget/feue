<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Academicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('user_academicos.create') }}" class="btn btn-primary bg-green-500 text-white px-4 py-2 rounded">{{ __('Create User Academico') }}</a>
                    <table class="table-auto w-full mt-4">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th class="px-4 py-2">{{ __('ID') }}</th>
                                <th class="px-4 py-2">{{ __('User') }}</th>
                                <th class="px-4 py-2">{{ __('Estado Academico') }}</th>
                                <th class="px-4 py-2">{{ __('Acta Grado') }}</th>
                                <th class="px-4 py-2">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userAcademicos as $userAcademico)
                                <tr class="bg-white dark:bg-gray-800">
                                    <td class="border px-4 py-2">{{ $userAcademico->id }}</td>
                                    <td class="border px-4 py-2">{{ $userAcademico->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $userAcademico->estadoAcademico->nombre ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ $userAcademico->acta_grado }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('user_academicos.edit', $userAcademico) }}" class="btn btn-secondary bg-blue-500 text-white px-4 py-2 rounded">{{ __('Edit') }}</a>
                                        <form action="{{ route('user_academicos.destroy', $userAcademico) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger bg-red-500 text-white px-4 py-2 rounded">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>