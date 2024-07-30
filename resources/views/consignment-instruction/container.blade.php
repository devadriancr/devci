<x-app-layout title="{{ __('Consigna MY') }}">
    <div class="container grid px-6 mx-auto">
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
        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="my-2 text-center text-xl uppercase font-bold text-gray-600 dark:text-gray-300">
                {{ __('Unidad de Transporte') }}
            </h4>
            <div>
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

        @can('admin')

        @if (session('success'))
        <div class="bg-purple-100 border border-purple-400 text-purple-700 px-4 py-3 mb-2 rounded relative">
            {{ session('success') }}
            @if (session('import_summary'))
            <div>
                Se importaron {{ session('import_summary.imported') }} de {{ session('import_summary.total') }} registros.
            </div>
            @endif
        </div>
        @endif

        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="my-2 text-center text-xl uppercase font-bold text-gray-600 dark:text-gray-300">
                {{ __('Códigos QR MY') }}
            </h4>
            <div>
                <div class="w-full sm:w-1/2 md:w-1/4 mb-2">
                    <form method="POST" action="{{ route('consigment-instruction.import-qr-my') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="relative">
                            <input type="file" name="import_file" accept=".csv, .xlsx" class="block w-full text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="{{ __('Seleccionar archivo') }}" />
                            <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                {{ __('Cargar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    </div>
</x-app-layout>
