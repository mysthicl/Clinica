{{-- resources/views/secretaria/payments/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pagos</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        {{-- Filtros --}}
        <form method="GET" action="{{ route('secretaria.payments.index') }}"
              class="flex flex-wrap gap-3 items-end mb-6">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Paciente o DUI</label>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Buscar paciente..."
                    class="border rounded px-3 py-2 text-sm w-56 focus:outline-none focus:ring">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Estado</label>
                <select name="status" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring">
                    <option value="">-- Todos --</option>
                    @foreach(['Pendiente', 'Pagado', 'Anulado'] as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-gray-700 text-white px-4 py-2 text-sm rounded hover:bg-gray-800">
                    Filtrar
                </button>
                @if($search || $status)
                    <a href="{{ route('secretaria.payments.index') }}"
                       class="px-4 py-2 text-sm border rounded hover:bg-gray-50">Limpiar</a>
                @endif
            </div>
        </form>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consulta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total consulta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto pagado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha pago</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    @php
                        $colors = [
                            'Pendiente' => 'bg-yellow-100 text-yellow-700',
                            'Pagado'    => 'bg-green-100 text-green-700',
                            'Anulado'   => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-400">
                            <a href="{{ route('shared.consults.show', $payment->consult->id_consult) }}"
                               class="text-blue-600 hover:underline">
                                #{{ $payment->consult->id_consult }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $payment->consult->patient->last_name }},
                            {{ $payment->consult->patient->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            ${{ number_format($payment->consult->total, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            ${{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $payment->payment_date
                                ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $colors[$payment->status] ?? '' }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($payment->status !== 'Anulado')
                                <form action="{{ route('secretaria.payments.void', $payment->id_payment) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('¿Anular este pago?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:underline">Anular</button>
                                </form>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-400">
                            No se encontraron pagos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $payments->links() }}</div>
    </div>
</x-app-layout>