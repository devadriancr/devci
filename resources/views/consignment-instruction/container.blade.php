<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Consigna
        </h2>
        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
                Unidad de Transporte
            </h4>

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

            @if (session('success'))
            <div class="mb-4 text-lg font-bold text-green-600">
                {{ session('success') }}
            </div>
            @endif

            <form method="GET" action="{{ route('consignment-instruction.create') }}">
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        Selecciona un Contenedor
                    </span>
                    <select name="container" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option></option>
                        @foreach ($containers as $container)
                        <option value="{{ $container->id }}">Contenedor: {{ $container->code }} &nbsp Fecha: {{ $container->arrival_date }} {{ $container->arrival_time }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex justify-end mt-2 mb-4">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Iniciar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
