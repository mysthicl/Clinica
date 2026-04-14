<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Consultas</h2>
    </x-slot>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
        {{-- Filtros y boton --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-3 mb-4">
            <form action="{{ route('shared.consults.index') }}" method="GET" class="flex flex-wrap gap-2">
                <div>
                    <label for="" class="block text-xs text-gray-500 mb-1">Paciente o DUI</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar paciente..." class="border rounded px-3 py-2 text-sm w-56 focus:outline-none focus:ring">
                </div>
                <div>
                    <label for="" class="block text-xs text-gray-500 mb-1">Estado</label>
                    <select name="status" id="status" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring">
                        <option value=""> -- Todos -- </option>
                        @foreach (['Abierta', 'Cerrada', 'Cancelada'] as $s)
                            <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-gray-700 text-white px-4 py-2 text-sm rounded hover:bg-gray-800">Filtrar</button>
                    @if ($search || $status)
                        <a href="{{ route('shared.consults.index') }}" class="px-4 py-2 text-sm border rounded hover:bg-gray-50">Limpiar</a>
                    @endif
                </div>
            </form>
            <a href="{{ route('shared.consults.create') }}" class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-500">+ Nueva consulta</a>
        </div>
        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($consults as $consult)
                    @php
                        $colors = [
                            'Abierta' => 'bg-green-100 text-green-700',
                            'Cerrada' => 'bg-gray-100 text-gray-700',
                            'Cancelada' => 'bg-red-100 text-red-700',
                        ];
                        @endphp
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-400">#{{ $consult->id_consult }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $consult->patient->last_name }}, {{ $consult->patient->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $consult->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($consult->date_register)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">${{ number_format($consult->total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $colors[$consult->status] ?? '' }}">
                                {{ $consult->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('shared.consults.show', $consult->id_consult) }}"
                               class="text-blue-600 hover:underline">Ver detalle</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-400">No se encontraron consultas</td>
                        </tr>
                        @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $consults->links() }}</div>
    </div>
</x-app-layout>