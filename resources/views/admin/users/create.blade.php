<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Nuevo Usuario</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4">
        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('password') border-red-500 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rol</label>
                <select required name="id_rol"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring @error('id_rol') border-red-500 @enderror">
                    <option value="">-- Selecciona un rol --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id_rol }}" {{ old('id_rol') == $role->id_rol ? 'selected' : '' }}>
                            {{ $role->rol }}
                        </option>
                    @endforeach
                </select>
                @error('id_rol') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 text-sm text-white border rounded bg-gray-500">Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700">Guardar</button>
            </div>
        </form>
    </div>
</x-app-layout>