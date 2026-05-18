<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard de Doctor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bienvenido al Dashboard de Doctor!") }} <br>
                     <x-info-button>
                        <a href="{{ route('doctor.patients.index') }}" >Ver Pacientes</a>
                    </x-info-button>
                    <x-active-button>
                        <a href="{{ route('doctor.appointments.index') }}">Ver Citas</a>
                    </x-active-button>
                    <x-warning-button>
                        <a href="{{ route('shared.consults.index') }}">Ver Consultas</a>
                    </x-warning-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>