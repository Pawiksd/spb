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
                    <h3 class="text-lg font-medium text-gray-900">Latest 50 New Releases</h3>
                    @if ($latestReleases->isNotEmpty())
                        <div class="mt-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Release Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instagram</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facebook</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">YouTube</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($latestReleases as $release)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->release_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->genre }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->label }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->instagram }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->facebook }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->website }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->youtube }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
