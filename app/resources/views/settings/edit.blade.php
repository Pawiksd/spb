<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="spotify_api_key" class="block text-sm font-medium text-gray-700">Spotify API Key</label>
                            <input type="text" name="spotify_api_key" id="spotify_api_key" class="form-input mt-1 block w-full" value="{{ env('SPOTIFY_API_KEY') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="spotify_api_secret" class="block text-sm font-medium text-gray-700">Spotify API Secret</label>
                            <input type="text" name="spotify_api_secret" id="spotify_api_secret" class="form-input mt-1 block w-full" value="{{ env('SPOTIFY_API_SECRET') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                            <input type="text" name="smtp_host" id="smtp_host" class="form-input mt-1 block w-full" value="{{ env('MAIL_HOST') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                            <input type="number" name="smtp_port" id="smtp_port" class="form-input mt-1 block w-full" value="{{ env('MAIL_PORT') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                            <input type="text" name="smtp_username" id="smtp_username" class="form-input mt-1 block w-full" value="{{ env('MAIL_USERNAME') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                            <input type="password" name="smtp_password" id="smtp_password" class="form-input mt-1 block w-full" value="{{ env('MAIL_PASSWORD') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
