{{-- resources/views/secretaria/appointments/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Cita</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4">
        <form action="{{ route('secretaria.appointments.update', $appointment->id_appointment) }}" method="POST"
              class="bg-white p-6 rounded shadow space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Paciente</label>
                <select name="id_patient"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('id_patient') border-red-500 @enderror">
                    <option value="">-- Selecciona un paciente --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id_patient }}"
                            {{ old('id_patient', $appointment->id_patient) == $patient->id_patient ? 'selected' : '' }}>
                            {{ $patient->last_name }}, {{ $patient->name }} — {{ $patient->dui }}
                        </option>
                    @endforeach
                </select>
                @error('id_patient') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Doctor</label>
                <select name="id_user"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('id_user') border-red-500 @enderror">
                    <option value="">-- Selecciona un doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id_user }}"
                            {{ old('id_user', $appointment->id_user) == $doctor->id_user ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                @error('id_user') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha de la cita</label>
                <input type="date" name="scheduled_at"
                    value="{{ old('scheduled_at', $appointment->scheduled_at) }}"
                    min="{{ date('Y-m-d') }}"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('scheduled_at') border-red-500 @enderror">
                @error('scheduled_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Notas <span class="text-gray-400 text-xs">(opcional)</span>
                </label>
                <textarea name="notes" rows="3" maxlength="150"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('notes') border-red-500 @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('secretaria.appointments.index') }}"
                   class="px-4 py-2 text-sm border rounded hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Actualizar</button>
            </div>
        </form>
    </div>
</x-app-layout>