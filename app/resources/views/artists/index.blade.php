<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight header">
            {{ __('Artists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 header">Artists</h3>
                    <div class="mb-4">
                        <span>Total Artists: <strong>{{ $totalArtists }}</strong></span>
                    </div>
                    <input type="text" id="search-box" placeholder="Search artists..." class="mt-4 mb-4 p-2 border border-gray-300 rounded">
                    <div class="mt-4">
                        <form action="{{ route('artists.download-report') }}" method="GET">
                            <input type="hidden" id="download-query" name="query" value="">
                            <div style="display: none;">
                                <label class="block font-medium text-sm text-gray-700" for="columns">Select Columns:</label>
                                <select name="columns[]" id="columns" multiple class="form-multiselect mt-1 block w-full">
                                    <option value="name">Name</option>
                                    <option value="email">Email</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="website">Website</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="twitter">Twitter</option>
                                </select>
                            </div>
                            <div class="mt-4"  style="display: none;">
                                <label class="block font-medium text-sm text-gray-700" for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" class="form-input mt-1 block w-full">
                                <label class="block font-medium text-sm text-gray-700 mt-4" for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date" class="form-input mt-1 block w-full">
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Download Current Search
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="search-info" class="mb-4"></div>
                    <div class="table-container mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Instagram</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Facebook</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Twitter</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">YouTube</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Website</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                                </tr>
                            </thead>
                            <tbody id="artists-table" class="bg-white divide-y divide-gray-200">
                                @foreach ($artists as $index => $artist)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->instagram ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->facebook ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->twitter ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->youtube ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->website ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $artist->email ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('artists.download-report-all') }}" method="GET">
                            <div>
                                <input type="hidden" id="download-query-all" name="query" value="">
                                <input type="hidden" id="download-start-date" name="start_date" value="">
                                <input type="hidden" id="download-end-date" name="end_date" value="">
                                <label class="block font-medium text-sm text-gray-700" for="columns-all">Select Columns:</label>
                                <select name="columns[]" id="columns-all" multiple class="form-multiselect mt-1 block w-full">
                                    <option value="name">Name</option>
                                    <option value="email">Email</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="website">Website</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="twitter">Twitter</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <label class="block font-medium text-sm text-gray-700" for="start_date_all">Start Date:</label>
                                <input type="date" id="start_date_all" class="form-input mt-1 block w-full">
                                <label class="block font-medium text-sm text-gray-700 mt-4" for="end_date_all">End Date:</label>
                                <input type="date" id="end_date_all" class="form-input mt-1 block w-full">
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Download All Artists
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-overlay"></div>
    <div class="popup">
        <div class="popup-header"></div>
        <div class="popup-body"></div>
        <div class="popup-close">&times;</div>
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
                    document.getElementById('download-query').value = query;
                    document.getElementById('download-query-all').value = query;

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
                            <td class="px-6 py-4 whitespace-nowrap">${artist.twitter ?? 'N/A'}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
        });

        document.getElementById('start_date_all').addEventListener('change', function() {
            document.getElementById('download-start-date').value = this.value;
        });

        document.getElementById('end_date_all').addEventListener('change', function() {
            document.getElementById('download-end-date').value = this.value;
        });
    </script>
</x-app-layout>
