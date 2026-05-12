<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Historial Clínico — {{ $patient->last_name }}, {{ $patient->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Datos del paciente --}}
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Datos del paciente</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Nombre completo</p>
                    <p class="font-medium text-gray-900">{{ $patient->last_name }}, {{ $patient->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">DUI</p>
                    <p class="font-medium text-gray-900">{{ $patient->dui }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Teléfono</p>
                    <p class="font-medium text-gray-900">{{ $patient->phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Fecha de nacimiento</p>
                    <p class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($patient->birthdate)->format('d/m/Y') }}
                        <span class="text-gray-400 text-xs ml-1">
                            ({{ \Carbon\Carbon::parse($patient->birthdate)->age }} años)
                        </span>
                    </p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-gray-500">Dirección</p>
                    <p class="font-medium text-gray-900">{{ $patient->address }}</p>
                </div>
            </div>
        </div>

        {{-- Resumen rápido --}}
        @php
            $totalConsultas = $patient->consults->count();
            $totalPagado    = $patient->consults->flatMap->payments
                                ->where('status', 'Pagado')
                                ->sum('amount');
            $totalServicios = $patient->consults->flatMap->services->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded shadow p-4 text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ $totalConsultas }}</p>
                <p class="text-sm text-gray-500 mt-1">Consultas totales</p>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalServicios }}</p>
                <p class="text-sm text-gray-500 mt-1">Servicios realizados</p>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <p class="text-2xl font-bold text-green-600">${{ number_format($totalPagado, 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total pagado</p>
            </div>
        </div>

        {{-- Consultas --}}
        <div class="space-y-4">
            <h3 class="text-base font-semibold text-gray-800">Consultas</h3>

            @forelse($patient->consults as $consult)
            @php
                $statusColors = [
                    'Abierta'   => 'bg-blue-100 text-blue-700',
                    'Cerrada'   => 'bg-green-100 text-green-700',
                    'Cancelada' => 'bg-red-100 text-red-700',
                ];
                $pago = $consult->payments->where('status', 'Pagado')->first();
            @endphp
            <div class="bg-white rounded shadow overflow-hidden">

                {{-- Cabecera de consulta --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between
                            gap-2 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-700">
                            Consulta #{{ $consult->id_consult }}
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$consult->status] ?? '' }}">
                            {{ $consult->status }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                        <span>
                            📅 {{ \Carbon\Carbon::parse($consult->date_register)->format('d/m/Y H:i') }}
                        </span>
                        <span>👨‍⚕️ {{ $consult->user->name }}</span>
                        <a href="{{ route('shared.consults.show', $consult->id_consult) }}"
                           class="text-indigo-600 hover:underline text-sm">
                            Ver detalle →
                        </a>
                    </div>
                </div>

                {{-- Servicios de la consulta --}}
                <div class="px-6 py-4">
                    @if($consult->services->count() > 0)
                        <table class="min-w-full text-sm divide-y divide-gray-100">
                            <thead>
                                <tr class="text-xs text-gray-400 uppercase">
                                    <th class="pb-2 text-left">Servicio</th>
                                    <th class="pb-2 text-left">Precio</th>
                                    <th class="pb-2 text-left">Descuento</th>
                                    <th class="pb-2 text-left">Final</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($consult->services as $cs)
                                <tr>
                                    <td class="py-2 text-gray-800">{{ $cs->service->name }}</td>
                                    <td class="py-2 text-gray-500">${{ number_format($cs->price, 2) }}</td>
                                    <td class="py-2 text-gray-500">
                                        {{ $cs->discount > 0 ? '$' . number_format($cs->discount, 2) : '—' }}
                                    </td>
                                    <td class="py-2 font-medium text-gray-900">
                                        ${{ number_format($cs->final_price, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-400 italic">Sin servicios registrados.</p>
                    @endif
                </div>

                {{-- Footer con total y pago --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between
                            gap-2 px-6 py-3 bg-gray-50 border-t border-gray-100 text-sm">
                    <div class="text-gray-600">
                        Total:
                        <span class="font-bold text-gray-900 ml-1">
                            ${{ number_format($consult->total, 2) }}
                        </span>
                    </div>
                    <div>
                        @if($pago)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                Pagado el {{ \Carbon\Carbon::parse($pago->payment_date)->format('d/m/Y') }}
                                — ${{ number_format($pago->amount, 2) }}
                            </span>
                        @elseif($consult->status === 'Cerrada')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                Pago pendiente
                            </span>
                        @elseif($consult->status === 'Cancelada')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                Cancelada
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                Consulta abierta
                            </span>
                        @endif
                    </div>
                    @if($consult->notes)
                        <div class="text-gray-400 italic text-xs sm:col-span-2">
                            📝 {{ $consult->notes }}
                        </div>
                    @endif
                </div>

            </div>
            @empty
                <div class="bg-white rounded shadow p-6 text-center text-sm text-gray-400">
                    Este paciente no tiene consultas registradas.
                </div>
            @endforelse
        </div>

        {{-- Volver --}}
        <div>
            <a href="{{ auth()->user()->role->rol === 'Doctor'
                        ? route('doctor.patients.index')
                        : route('secretaria.patients.index') }}"
               class="px-4 py-2 text-sm border rounded hover:bg-gray-50">
                ← Volver al listado
            </a>
        </div>

    </div>
</x-app-layout>