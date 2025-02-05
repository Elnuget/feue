<x-app-layout>
    @section('page_title', 'QR')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('QR Code for ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 flex flex-col items-center justify-center space-y-6">
                    <!-- QR Container with white background for better contrast -->
                    <div class="bg-white p-4 rounded-lg shadow-sm" id="qr-container">
                        {!! QrCode::size(250)->generate($user->id) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
