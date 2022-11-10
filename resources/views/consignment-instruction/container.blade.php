<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Escaneo CI
        </h2>
        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="GET" action="{{ route('consigment-instruction.create') }}">
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        Selecciona un Contenedor
                    </span>
                    <select name="container" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option></option>
                        @foreach ($containers as $container)
                        <option value="{{ $container->id }}">{{ $container->code }} {{ $container->date }} {{ $container->time }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex justify-end mt-2">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Iniciar Escaneo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
