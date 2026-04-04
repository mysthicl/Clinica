<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pacientes') }}
        </h2>
    </x-slot>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 ls:px-8">
        {{-- Mensaje --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Barra superior --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            {{-- Busqueda --}}
            <form action="{{ request()->routeIs('doctor.*') ? route('doctor.patients.index') : route('secretaria.patients.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o DUI..." class="border rounded px-3 py-2 text-sm w-full sm:w-72 focus:outline-none focus:ring">
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 text-sm rounded hover:bg-gray-800">Buscar</button>
                @if ($search)
                    <a href="{{ request()->routeIs('doctor.*') ? route('doctor.patients.index') : route('secretaria.patients.index') }}" class="px-4 py-2 text-sm border rounded hover:bg-gray-500 text-white">Limpiar</a>
                @endif
            </form>
            {{-- Botón solo para secretaria --}}
            @if(auth()->user()->role->rol === 'Secretaria')
                <a href="{{ route('secretaria.patients.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700 whitespace-nowrap">
                    + Nuevo paciente
                </a>
            @endif
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DUI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha de nacimiento</th>
                        @if(auth()->user()->role->rol === 'Secretaria')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($patients as $patient)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $patient->last_name }}, {{ $patient->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $patient->dui }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $patient->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($patient->birthdate)->format('d/m/Y') }}
                        </td>
                        @if(auth()->user()->role->rol === 'Secretaria')
                        <td class="px-6 py-4 text-sm space-x-2">
                            <x-warning-button>
                                <a href="{{ route('secretaria.patients.edit', $patient->id_patient) }}"
                               class="text-white">Editar</a>
                            </x-warning-button>

                            <form action="{{ route('secretaria.patients.destroy', $patient->id_patient) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('¿Eliminar este paciente permanentemente?')">
                                @csrf @method('DELETE')
                                <x-danger-button type="submit">Eliminar</x-danger-button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-400">
                            No se encontraron pacientes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </div>
</x-app-layout>
