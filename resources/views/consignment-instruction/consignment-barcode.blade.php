<x-app-layout title="Consigna MC/MH">
    <div class="container grid px-6 mx-auto">
        @if (session('scan_count', 0) > 0)
        <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form id="consignment-form" action="{{ route('consigment-instruction.consignment-barcode-store') }}" method="post">
                @csrf
                <div>
                    <h4 class="my-2 text-center text-lg uppercase font-bold text-gray-600 dark:text-gray-300">
                        {{ __('Consigna MC/MH') }}
                    </h4>
                    <label class="block text-sm my-2">
                        <span class="text-gray-700 dark:text-gray-400">{{ __('Ingrese Código de Barras') }}</span>
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

                        @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <span class="block mt-2 text-xs text-red-600 dark:text-red-400">
                            {{ $error }}
                        </span>
                        @endforeach
                        @endif
                    </label>
                </div>

                <div class="flex flex-row justify-between">
                    <div>
                        <h4 class="my-2 text-center text-lg uppercase font-bold text-gray-600 dark:text-gray-300">
                            {{ __('Conteo de Escaneos') }} : {{ session('scan_count', 0) -1 }}
                        </h4>
                    </div>
                    <div>
                        <button id="save-button" type="submit" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <span class="mr-4">Guardar</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <a href="{{ route('consigment-instruction.consigment-barcode-finish') }}" class="block w-full px-10 py-2 text-lg font-semibold leading-tight text-purple-700 transition-colors duration-150 bg-purple-100 border border-transparent rounded-lg active:bg-purple-100 hover:bg-purple-200 focus:outline-none focus:shadow-outline-purple dark:bg-purple-700 dark:text-white">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="ml-2">Finalizar Escaneo</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">No Orden</th>
                            <th class="px-4 py-3">No Parte</th>
                            <th class="px-4 py-3">SNP</th>
                            <th class="px-4 py-3">Conteo</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Proveedores</th>
                            <th class="px-4 py-3">Tipo de Consigna</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white uppercase divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($mcmh as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ $consignment->no_order }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->item_number }}</td>
                            <td class="px-4 py-3 text-sm">{{ intval($consignment->total_quantity / $consignment->row_count) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->row_count }}</td>
                            <td class="px-4 py-3 text-sm">{{ intval($consignment->total_quantity) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->suppliers }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->type_consignment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">Mostrando {{ $mcmh->firstItem() }} - {{ $mcmh->lastItem() }} de {{ $mcmh->total() }}</span>
                <span class="col-span-2"></span>
                <!-- Paginación -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">{{ $mcmh->withQueryString()->links() }}</nav>
                </span>
            </div>
        </div>
        @else
        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <a href="{{ route('consigment-instruction.consigment-barcode-start') }}" class="block w-full px-10 py-2 text-lg font-semibold leading-tight text-purple-700 transition-colors duration-150 bg-purple-100 border border-transparent rounded-lg active:bg-purple-100 hover:bg-purple-200 focus:outline-none focus:shadow-outline-purple dark:bg-purple-700 dark:text-white">
                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="ml-2">Iniciar Escaneo</span>
                </div>
            </a>
        </div>
        @endif
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("code_qr").focus();
            document.getElementById("consignment-form").addEventListener("submit", function(event) {
                var button = document.getElementById("save-button");
                var svgIcon = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>`;
                button.disabled = true;
                button.innerHTML = svgIcon + "Guardando...";
            });
        });
    </script>
</x-app-layout>
