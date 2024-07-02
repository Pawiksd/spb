<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight header">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">User Profile</h3>
                    <div class="table-container mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">Name:</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">Email:</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">Roles:</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
