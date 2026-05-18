<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard de Secretaria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bienvenido al Dashboard de Secretaria!!") }} <br>
                    <x-info-button>
                        <a href="{{ route('secretaria.patients.index') }}" >Gestionar Pacientes</a>
                    </x-info-button>
                    <x-active-button>
                        <a href="{{ route('secretaria.appointments.index') }}" >Gestionar Citas</a>
                    </x-active-button>
                    <x-warning-button>
                        <a href="{{ route('secretaria.payments.index') }}" >Gestionar Pagos</a>
                    </x-warning-button>
                    <x-moradic-button>
                        <a href="{{ route('shared.consults.index') }}" >Gestionar Consultas</a>
                    </x-moradic-button>            
                </div>
            </div>
        </div>
    </div>
</x-app-layout>