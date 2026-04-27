<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Crear una consulta</h2>
    </x-slot>
    <div class="py-6 max-w-2xl mx-auto px-4">
        <form action="{{ route('shared.consults.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            {{-- Si viene desde una cita, pasamos el id oculto --}}
            @if($appointment)
                <input type="hidden" name="id_appointment" value="{{ $appointment->id_appointment }}">
                <div class="p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                    Consulta generada desde la cita agendada el <strong>{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}</strong> para <strong>{{ $appointment->patient->last_name }}, {{ $appointment->patient->name }} </strong>
                </div>
            @endif
            <div>
                <label for="" class="block text-sm font-medium text-gray-700">Paciente</label>
                <select name="id_patient" id="" {{ $appointment ? 'disabled' : '' }} class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('id_patient' ) border-red-500 @enderror">
                    <option value=""> --Selecciona un paciente-- </option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id_patient }}" {{ old('id_patient', $appointment?->id_patient) == $patient->id_patient ? 'selected' : '' }}>
                        {{ $patient->last_name }}, {{ $patient->name }} - {{ $patient->dui }}
                        </option>
                    @endforeach
                </select>
                {{-- Si esta deshabilitado mandamos el valor igual --}}
                @if($appointment)
                    <input type="hidden" name="id_patient" value="{{ $appointment->id_patient }}">
                @endif
                @error('id_patient') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="" class="block text-sm font-medium text-gray-700">Doctor responsable</label>
                <select name="id_user" id="" {{ $appointment ? 'disabled' : '' }} class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('id_user') border-red-500 @enderror">
                    <option value=""> --Selecciona un doctor-- </option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id_user }}">
                            {{ old('id_user', $appointment?->id_user) == $doctor->id_user ? 'selected' : '' }}
                        {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                @if($appointment)
                    <input type="hidden" name="id_user" value="{{ $appointment->id_user }}">
                @endif
                @error('id_user')
                    <p class="text-red-500 text-xs mt-1">{{ $mesage }}</p>
                @enderror
            </div>
            <div>
                <label for="" class="block text-sm font-medium text-gray-700">Fecha y hora de la consulta</label>
                <input type="datetime-local" name="date_register" value="{{ old('date_register', now()->format('Y-m-d\TH:i')) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('date_register') border-red-500 @enderror">
                @error('date_register')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="" class="block text-sm font-medium text-gray-700">
                    Notas <span class="text-gray-400 text-xs">(opcional)</span>
                </label>
                <textarea name="notes" id="" rows="3" maxlength="150" class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('shared.consults.index') }}" class="px-4 py-2 text-sm border rounded hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                    Abrir consulta
                </button>
            </div>
        </form>
    </div>
</x-app-layout>