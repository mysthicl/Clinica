<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Consulta #{{ $consult->id_consult }} —
            {{ $consult->patient->last_name }}, {{ $consult->patient->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        {{-- Info de la consulta --}}
        @php
            $colors = [
                'Abierta'   => 'bg-blue-100 text-blue-700',
                'Cerrada'   => 'bg-green-100 text-green-700',
                'Cancelada' => 'bg-red-100 text-red-700',
            ];
        @endphp
        <div class="bg-white rounded shadow p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Paciente</p>
                <p class="font-medium text-gray-900">
                    {{ $consult->patient->last_name }}, {{ $consult->patient->name }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Doctor</p>
                <p class="font-medium text-gray-900">{{ $consult->user->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha de registro</p>
                <p class="font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($consult->date_register)->format('d/m/Y H:i') }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Estado</p>
                <span class="px-2 py-1 text-xs rounded-full {{ $colors[$consult->status] ?? '' }}">
                    {{ $consult->status }}
                </span>
            </div>
            @if($consult->appointment)
            <div>
                <p class="text-gray-500">Cita de origen</p>
                <p class="font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($consult->appointment->scheduled_at)->format('d/m/Y') }}
                </p>
            </div>
            @endif
            @if($consult->notes)
            <div class="sm:col-span-2">
                <p class="text-gray-500">Notas</p>
                <p class="text-gray-700 italic">{{ $consult->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Servicios --}}
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Servicios realizados</h3>

            {{-- Formulario para agregar servicio (solo si está Abierta) --}}
            @if($consult->status === 'Abierta')
            <form action="{{ route('shared.consults.services.store', $consult->id_consult) }}"
                  method="POST" class="flex flex-wrap gap-3 items-end mb-6">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Servicio</label>
                    <select name="id_service"
                        class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('id_service') border-red-500 @enderror">
                        <option value="">-- Selecciona --</option>
                        @foreach(\App\Models\Service::orderBy('name')->get() as $service)
                            <option value="{{ $service->id_service }}">
                                {{ $service->name }} — ${{ number_format($service->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_service') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Descuento ($)</label>
                    <input type="number" name="discount" value="0" min="0" step="0.01"
                        class="border rounded px-3 py-2 text-sm w-28 focus:outline-none focus:ring">
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700">
                    + Agregar
                </button>
            </form>
            @endif

            {{-- Tabla de servicios --}}
            @if($consult->services->count() > 0)
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descuento</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Final</th>
                        @if($consult->status === 'Abierta')
                            <th class="px-4 py-2"></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($consult->services as $cs)
                    <tr>
                        <td class="px-4 py-3 text-gray-900">{{ $cs->service->name }}</td>
                        <td class="px-4 py-3 text-gray-500">${{ number_format($cs->price, 2) }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $cs->discount > 0 ? '$' . number_format($cs->discount, 2) : '—' }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">${{ number_format($cs->final_price, 2) }}</td>
                        @if($consult->status === 'Abierta')
                        <td class="px-4 py-3">
                            <form action="{{ route('shared.consults.services.destroy', [$consult->id_consult, $cs->id_consult_service]) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar este servicio?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Eliminar</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="{{ $consult->status === 'Abierta' ? 3 : 3 }}"
                            class="px-4 py-3 text-right font-semibold text-gray-700">Total:</td>
                        <td class="px-4 py-3 font-bold text-gray-900 text-base">
                            ${{ number_format($consult->total, 2) }}
                        </td>
                        @if($consult->status === 'Abierta') <td></td> @endif
                    </tr>
                </tfoot>
            </table>
            @else
                <p class="text-sm text-gray-400 italic">Aún no se han registrado servicios.</p>
            @endif
        </div>

        {{-- Acciones de la consulta --}}
        @if($consult->status === 'Abierta')
        <div class="flex gap-3 justify-end">
            <form action="{{ route('shared.consults.cancel', $consult->id_consult) }}"
                  method="POST" onsubmit="return confirm('¿Cancelar esta consulta?')">
                @csrf @method('PATCH')
                <button type="submit"
                    class="px-4 py-2 text-sm border border-red-300 text-red-600 rounded hover:bg-red-50">
                    Cancelar consulta
                </button>
            </form>
            <form action="{{ route('shared.consults.close', $consult->id_consult) }}"
                  method="POST" onsubmit="return confirm('¿Cerrar esta consulta? Ya no podrás agregar más servicios.')">
                @csrf @method('PATCH')
                <button type="submit"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                    Cerrar consulta
                </button>
            </form>
        </div>
        @endif

        <div class="flex justify-start">
            <a href="{{ route('shared.consults.index') }}"
               class="px-4 py-2 text-sm border rounded hover:bg-gray-50">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-app-layout>