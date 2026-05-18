<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();

        $dashboardRoute = match($user->role->rol) {
            'Admin' => 'admin.dashboard',
            'Doctor' => 'doctor.dashboard',
            'Secretaria' => 'secretaria.dashboard',
            default => 'dashboard',
            };
            @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{ __("You're logged in!") }}

                    <div class="mt-4">
                        <a href="{{ route($dashboardRoute) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Ir a mi Dashboard
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>