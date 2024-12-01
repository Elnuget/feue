<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Delete User Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('user_profiles.destroy', $profile->user_id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Are you sure you want to delete this profile?') }}</label>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="ml-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>