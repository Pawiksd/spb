<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endcan

                    <x-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-nav-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
