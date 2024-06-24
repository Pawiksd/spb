<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Artists</h3>
                    <div class="mb-4">
                        <span>Total Artists: <strong>{{ $totalArtists }}</strong></span>
                    </div>
                    <input type="text" id="search-box" placeholder="Search artists..." class="mt-4 mb-4 p-2 border border-gray-300 rounded">
                    <div id="search-info" class="mb-4"></div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instagram</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facebook</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">YouTube</th>
                            </tr>
                        </thead>
                        <tbody id="artists-table" class="bg-white divide-y divide-gray-200">
                            @foreach ($artists as $index => $artist)
                                <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $artist->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $artist->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $artist->instagram ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $artist->facebook ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $artist->website ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $artist->youtube ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <form action="{{ route('artists.download-report') }}" method="GET">
            <div>
                <label class="block font-medium text-sm text-gray-700" for="columns">Select Columns:</label>
                <select name="columns[]" id="columns" multiple class="form-multiselect mt-1 block w-full">
                    <option value="name">Name</option>
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

    <script>
        document.getElementById('search-box').addEventListener('input', function() {
            let query = this.value;
            fetch(`/artists/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById('artists-table');
                    let searchInfo = document.getElementById('search-info');
                    tableBody.innerHTML = '';
                    searchInfo.innerHTML = `Total Found: ${data.totalFound}`;

                    data.artists.forEach((artist, index) => {
                        let row = document.createElement('tr');
                        row.className = index % 2 == 0 ? 'bg-gray-100' : 'bg-white';
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">${artist.name ?? 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${artist.email ?? 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${artist.instagram ?? 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${artist.facebook ?? 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${artist.website ?? 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${artist.youtube ?? 'N/A'}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
        });
    </script>
</x-app-layout>
