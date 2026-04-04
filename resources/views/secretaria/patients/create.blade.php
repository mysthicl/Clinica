<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Paciente</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4">
        <form action="{{ route('secretaria.patients.store') }}" method="POST"
              class="bg-white p-6 rounded shadow space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="mt-1 w-full border rounded px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                        class="mt-1 w-full border rounded px-3 py-2 text-sm @error('last_name') border-red-500 @enderror">
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">DUI</label>
                    <input type="text" name="dui" id="dui" value="{{ old('dui') }}" placeholder="00000000-0" min="10"
                        class="mt-1 w-full border rounded px-3 py-2 text-sm @error('dui') border-red-500 @enderror">
                    @error('dui') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="0000-0000" min="9"
                        class="mt-1 w-full border rounded px-3 py-2 text-sm @error('phone') border-red-500 @enderror">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate') }}"
                        class="mt-1 w-full border rounded px-3 py-2 text-sm @error('birthdate') border-red-500 @enderror">
                    @error('birthdate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                        class="mt-1 w-full border rounded px-3 py-2 text-sm @error('address') border-red-500 @enderror">
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('secretaria.patients.index') }}"
                   class="px-4 py-2 text-sm border rounded hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const duiInput = document.querySelector('#dui');
        const phoneInput = document.querySelector('#phone');

        duiInput.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 9);
            if (value.length >= 8) value = value.slice(0, 8) + '-' + value.slice(8);
            this.value = value;
        });

        phoneInput.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            if (value.length > 4) value = value.slice(0, 4) + '-' + value.slice(4);
            this.value = value;
        });
    });
</script>