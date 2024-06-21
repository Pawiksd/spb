<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Latest New Release</h3>
                    @if ($latestRelease)
                        <div class="mt-4">
                            <p><strong>Artist:</strong> {{ $latestRelease->artist->name }}</p>
                            <p><strong>Title:</strong> {{ $latestRelease->title }}</p>
                            <p><strong>Release Date:</strong> {{ $latestRelease->release_date }}</p>
                            <p><strong>Genre:</strong> {{ $latestRelease->genre }}</p>
                            <p><strong>Label:</strong> {{ $latestRelease->label }}</p>
                            <p><strong>Email:</strong> {{ $latestRelease->artist->email }}</p>
                            <p><strong>Instagram:</strong> {{ $latestRelease->artist->instagram }}</p>
                            <p><strong>Facebook:</strong> {{ $latestRelease->artist->facebook }}</p>
                            <p><strong>Website:</strong> {{ $latestRelease->artist->website }}</p>
                            <p><strong>YouTube:</strong> {{ $latestRelease->artist->youtube }}</p>
                        </div>
                    @else
                        <p>No new releases found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <form action="{{ route('download-report') }}" method="GET">
            <div>
                <label class="block font-medium text-sm text-gray-700" for="columns">Select Columns:</label>
                <select name="columns[]" id="columns" multiple class="form-multiselect mt-1 block w-full">
                    <option value="artist">Artist</option>
                    <option value="title">Title</option>
                    <option value="release_date">Release Date</option>
                    <option value="genre">Genre</option>
                    <option value="label">Label</option>
                    <option value="email">Email</option>
                    <option value="instagram">Instagram</option>
                    <option value="facebook">Facebook</option>
                    <option value="website">Website</option>
                    <option value="youtube">YouTube</option>
                </select>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Download Report</button>
            </div>
        </form>
    </div>
</x-app-layout>
