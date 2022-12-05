<x-app-layout title="Roles & Permisos">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Editar de Rol
        </h2>

        <!-- General elements -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Elements
        </h4> -->

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">¡Oh no! Algo salió mal.</div>

            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form class="grid grid-cols-12 gap-2" method="POST" action="{{ route('role.update', $role->id) }}">
                @csrf
                @method('PUT')
                <div class="col-span-12">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Nombre</span>
                        <input name="name" value="{{ $role->name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Administrador" />
                    </label>
                </div>

                <div class="col-span-12">
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($permissions as $permission)
                        <div>
                            <label class="flex items-center dark:text-gray-400">
                                <input type="checkbox"
                                    @if (isset($role->permissions))
                                        @foreach ($role->permissions as $i => $role_permissions)
                                            @if ($role_permissions->id == $permission->id)
                                                {{ "checked" }}
                                            @endif
                                        @endforeach
                                    @endif
                                value="{{ $permission->id }}" name="permissions[]" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                                <span class="ml-2">
                                    {{ $permission->name }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-span-12">
                    <div class="flex justify-end my-4">
                        <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-4">Guardar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
