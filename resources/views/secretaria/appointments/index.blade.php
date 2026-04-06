{{-- resources/views/secretaria/appointments/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Citas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        {{-- Filtros y botón --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-3 mb-4">
            <form method="GET"
                  action="{{ auth()->user()->role->rol === 'Doctor' ? route('doctor.appointments.index') : route('secretaria.appointments.index') }}"
                  class="flex flex-wrap gap-2">

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Fecha</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Estado</label>
                    <select name="status" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring">
                        <option value="">-- Todos --</option>
                        @foreach(['Agendada', 'Cancelada', 'Completada'] as $s)
                            <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-gray-700 text-white px-4 py-2 text-sm rounded hover:bg-gray-800">
                        Filtrar
                    </button>
                    @if($date || $status)
                        <a href="{{ auth()->user()->role->rol === 'Doctor' ? route('doctor.appointments.index') : route('secretaria.appointments.index') }}"
                           class="px-4 py-2 text-sm border rounded hover:bg-gray-50">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            @if(auth()->user()->role->rol === 'Secretaria')
                <a href="{{ route('secretaria.appointments.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700 whitespace-nowrap">
                    + Nueva cita
                </a>
            @endif
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notas</th>
                        @if(auth()->user()->role->rol === 'Secretaria')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $appointment->patient->last_name }}, {{ $appointment->patient->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $appointment->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'Agendada'   => 'bg-blue-100 text-blue-700',
                                    'Cancelada'  => 'bg-red-100 text-red-700',
                                    'Completada' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $colors[$appointment->status] ?? '' }}">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400 italic">
                            {{ $appointment->notes ?? '—' }}
                        </td>
                        @if(auth()->user()->role->rol === 'Secretaria')
                        <td class="px-6 py-4 text-sm space-x-2">
                            @if($appointment->status === 'Agendada')
                                <a href="{{ route('secretaria.appointments.edit', $appointment->id_appointment) }}"
                                   class="text-blue-600 hover:underline">Editar</a>

                                <form action="{{ route('secretaria.appointments.cancel', $appointment->id_appointment) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('¿Cancelar esta cita?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:underline">Cancelar</button>
                                </form>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-400">
                            No se encontraron citas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</x-app-layout>