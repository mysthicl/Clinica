<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios!') }}
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

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h3 class="text-lg font-medium text-white">Usuario del sistema</h3>
            <a href="{{ route('admin.users.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Crear usuario
            </a>
        </div>
        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->role->rol }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-sm rounded-full {{ $user->active ? 'bg-green-600 text-white' : 'bg-orange-600 text-white' }}">
                                    {{ $user->active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2 text-start">
                                {{-- Editar --}}
                                <x-info-button>
                                    <a href="{{ route('admin.users.edit', $user->id_user) }}"
                                    class="text-white">Editar</a>
                                </x-info-button>

                                {{-- Activar/Desactivar --}}
                                @if ($user->id_user !== auth()->id())
                                    <form action="{{ route('admin.users.toggle', $user->id_user) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->active)
                                        <x-unactive-button type="submit"
                                            >
                                            {{ $user->active ? 'Desactivar' : 'Activar' }}
                                        </x-unactive-button>
                                        @else
                                        <x-active-button type="submit" >
                                            {{ $user->active ? 'Desactivar' : 'Activar' }}
                                        </x-active-button>
                                        @endif
                                    </form>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Eliminar este usuario permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="submit" >Eliminar</x-danger-button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>