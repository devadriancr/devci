<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Recepción Consigna MC/MH
        </h2>

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form id="consignment-form" action="{{ route('consigment-instruction.consignment-barcode-store') }}" method="post">
                @csrf
                <div>
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

                <div class="flex flex-row justify-end">
                    <button id="save-button" type="submit" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-4">Guardar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">No Parte</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Proveedor</th>
                            <th class="px-4 py-3">Tipo de Consigna</th>
                            <th class="px-4 py-3">Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white uppercase divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($mcmh as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->serial }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->item->item_number }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->item_quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->supplier }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->type_consignment }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->created_at }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    <!-- Mostrando {{ $mcmh->firstItem() }} - {{ $mcmh->lastItem() }} de {{ $mcmh->total()}} -->
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $mcmh->withQueryString()->links() }}
                    </nav>
                </span>
            </div>
        </div>
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
