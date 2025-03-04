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

                    @if(auth()->user()->subscriptions()->exists())
                        <form action="{{ route('unsubscribe') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Unsubscribe from Notifications</button>
                        </form>
                    @else
                        <form action="{{ route('subscribe') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Subscribe to Notifications</button>
                        </form>
                    @endif

                    <h3 class="text-lg font-medium text-gray-900 header">Latest 50 New Releases</h3>
                    @if ($latestReleases->isNotEmpty())
                        <div class="table-container mt-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            <a href="{{ route('dashboard', ['sortField' => 'artist_name', 'sortOrder' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                                Artist
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            <a href="{{ route('dashboard', ['sortField' => 'title', 'sortOrder' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                                Title
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            <a href="{{ route('dashboard', ['sortField' => 'release_date', 'sortOrder' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                                Release Date
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($latestReleases as $release)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->artist->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $release->release_date }}</td>
                                            <td class="hidden">{{ $release->genre }}</td>
                                            <td class="hidden">{{ $release->label }}</td>
                                            <td class="hidden">{{ $release->artist->email }}</td>
                                            <td class="hidden">{{ $release->artist->instagram }}</td>
                                            <td class="hidden">{{ $release->artist->facebook }}</td>
                                            <td class="hidden">{{ $release->artist->website }}</td>
                                            <td class="hidden">{{ $release->artist->youtube }}</td>
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

    <div class="popup-overlay"></div>
    <div class="popup">
        <div class="popup-header"></div>
        <div class="popup-body"></div>
        <div class="popup-close">&times;</div>
    </div>

</x-app-layout>
