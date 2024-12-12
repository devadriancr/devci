<x-app-layout title="{{ __('Consigna MY') }}">
    <div class="container mx-auto px-2">

        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('consigment-instruction.store') }}" id="consignment-form">
                @csrf
                <div>
                    <div class="grid grid-cols-12 gap-2 my-2 uppercase text-lg font-bold md:text-base sm:text-base sm:font-bold text-gray-600 dark:text-gray-300">
                        <div class="col-span-6">
                            <input name="container_id" value="{{ $container->id }}" hidden>
                            <span>
                                {{ $container->code }}
                            </span>
                        </div>
                        <div class="col-span-6 text-right">
                            <span>
                                {{ \Carbon\Carbon::parse($container->arrival_date . ' ' . $container->arrival_time)->format('d-m-Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Ingrese Código QR') }}</span>
                            <input id="code_qr" name="code_qr" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none form-input {{ session('success') ? 'border-green-600 focus:border-green-400 focus:shadow-outline-green' : '' }} {{ session('warning') ? 'border-red-600 focus:border-red-400 focus:shadow-outline-red' : '' }}" autocomplete="off" />

                            @if (session('success'))
                            <span class="block mt-2 text-xs text-green-600 dark:text-green-400">
                                {{ session('success') }}
                            </span>
                            @endif

                            @if (session('warning'))
                            <span class="block mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ session('warning') }}
                            </span>
                            @endif
                        </label>
                    </div>
                    <div class="flex justify-between mt-2 gap-2">
                        <span class="block mt-2 text-md text-blue-600 dark:text-blue-400">
                            Escaneado: {{ $count }}
                        </span>
                        <button type="submit" id="save-button" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="ml-2 text-xs">{{ __('Guardar') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-full grid grid-cols-3 gap-2 my-4">
            <a href="{{ route('consignment-instruction.container') }}" class="flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                <span class="ml-2 text-xs">{{ __('Regresar') }}</span>
            </a>

            <form method="POST" action="{{ route('consigment-instruction.barcode') }}" class="flex items-center justify-center w-full">
                @csrf
                <input name="container_id" value="{{ $container->id }}" hidden>
                <input name="container_code" value="{{ $container->code }}" hidden>
                <input name="container_date" value="{{ $container->arrival_date }}" hidden>
                <input name="container_time" value="{{ $container->arrival_time }}" hidden>
                <button class="flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-2 text-xs">{{ __('Código de Barras') }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('consigment-instruction.check') }}" class="flex items-center justify-center w-full">
                @csrf
                <input name="container_id" value="{{ $container->id }}" hidden>
                <input name="container_code" value="{{ $container->code }}" hidden>
                <input name="container_date" value="{{ $container->arrival_date }}" hidden>
                <input name="container_time" value="{{ $container->arrival_time }}" hidden>
                <button class="flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                    </svg>
                    <span class="ml-2 text-xs">{{ __('Validar Escaneo') }}</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("code_qr").focus();
        });

        document.getElementById("consignment-form").addEventListener("submit", function(event) {
            var button = document.getElementById("save-button");
            var svgIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>`;
            button.disabled = true;
            button.innerHTML = svgIcon + "Guardando...";
        });
    </script>
</x-app-layout>
