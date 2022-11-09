<x-app-layout title="Contenedores">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Editar Contenedor
        </h2>

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
            <form class="grid grid-cols-12 gap-2" method="POST" action="{{ route('container.update', $container->id) }}">
                @csrf
                @method('PUT')
                <div class="col-span-12">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Unidad</span>
                        <input name="code" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="A1B2C3D4XXX" value="{{ $container->code }}" />
                    </label>
                </div>
                <div class="col-span-6">
                    <label class=" text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Fecha</span>
                        <input name="date" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="{{ $container->date }}" />
                    </label>
                </div>
                <div class="col-span-6">
                    <label class=" text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Hora</span>
                        <input name="time" type="time" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="{{ $container->time }}" />
                    </label>
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
