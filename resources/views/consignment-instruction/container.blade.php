<x-app-layout title="{{ __('Consigna MY') }}">
    <div class="container grid px-6 mx-auto">
        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="my-2 text-center text-xl uppercase font-bold text-gray-600 dark:text-gray-300">
                {{ __('Unidad de Transporte') }}
            </h4>

            <form method="GET" action="{{ route('consignment-instruction.create') }}">
                <label class="block mt-4 text-sm">
                    <!-- <span class="text-gray-700 dark:text-gray-400">
                        {{ __('Contenedor') }}
                    </span> -->
                    <select name="container" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" aria-label="{{ __('Selecciona un Contenedor') }}">
                        <option value="">{{ __('Selecciona un Contenedor') }}</option>
                        @foreach ($containers as $container)
                        <option value="{{ $container->id }}">{{ __('Contenedor:') }} {{ $container->code }} &nbsp {{ __('Fecha:') }} {{ $container->arrival_date }} {{ $container->arrival_time }}</option>
                        @endforeach
                    </select>
                    @error('container')
                        <p class="text-red-600 text-sm mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </label>
                <div class="flex justify-end mt-2 mb-4">
                    <button type="submit" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>{{ __('Iniciar Escaneo') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
