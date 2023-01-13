<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Consigna
        </h2>
        <div class="flex flew-row justify-end my-2 gap-x-2">
            <div>
                <form method="POST" action="{{ route('consigment-instruction.barcode') }}">
                    @csrf
                    <input name="container_id" value="{{ $container->id }}" hidden>
                    <input name="container_code" value="{{ $container->code }}" hidden>
                    <input name="container_date" value="{{ $container->arrival_date }}" hidden>
                    <input name="container_time" value="{{ $container->arrival_time }}" hidden>
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ml-2">Codigo de Barras</span>
                    </button>
                </form>
            </div>
            <div>
                <form method="POST" action="{{ route('consigment-instruction.check') }}">
                    @csrf
                    <input name="container_id" value="{{ $container->id }}" hidden>
                    <input name="container_code" value="{{ $container->code }}" hidden>
                    <input name="container_date" value="{{ $container->arrival_date }}" hidden>
                    <input name="container_time" value="{{ $container->arrival_time }}" hidden>
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                        </svg>
                        <span class="ml-2">Validar</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('consigment-instruction.store') }}">
                <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Recibo de Consigna
                </h4>
                @csrf
                <div class="grid grid-cols-12 gap-4 my-2 uppercase font-bold md:font-medium md:text-base sm:text-base sm:font-normal text-gray-600 dark:text-gray-300">
                    <div class="col-span-6">
                        <input name="container_id" value="{{ $container->id }}" hidden>
                        <span>
                            {{ $container->code }}
                        </span>
                    </div>
                    <div class="col-span-6 text-right">
                        <span>
                            {{ $container->arrival_date }} {{ $container->arrival_time }}
                        </span>
                    </div>
                </div>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Código QR</span>
                    <input name="code_qr" id="code_qr" class="block w-full my-2 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="off" />
                </label>

                <div class="flex justify-end mt-2 gap-2">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="ml-2">Guardar</span>
                    </button>
                </div>
            </form>

            @if (session('success'))
            <div class="mb-4 font-medium text-green-600">
                {{ session('success') }}
            </div>
            @endif

            @if (session('warning'))
            <div class="mb-4 font-medium text-yellow-600">
                {{ session('warning') }}
            </div>
            @endif
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Proveedor</th>
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">No Parte</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Contenedor</th>
                            <th class="px-4 py-3">Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white uppercase divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($consignments as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->supplier }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->serial }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->part_no }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->part_qty }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->container->code }}
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
                    Mostrando {{ $consignments->firstItem() }} - {{ $consignments->lastItem() }} de {{ $consignments->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $consignments->withQueryString()->links()}}
                    </nav>
                </span>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            document.getElementById("code_qr").focus();
        };
    </script>
</x-app-layout>
