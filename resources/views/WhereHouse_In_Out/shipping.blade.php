<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
             Shipping
        </h2>

        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">

                <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
                   Reportes de escaneo de Shipping

        </div>


        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Numero de shipping</th>
                            <th class="px-4 py-3">Usuario</th>
                            <th class="px-4 py-3">Transacción</th>
                            <th class="px-4 py-3">Almacen</th>
                            <th class="px-4 py-3">Fecha y Hora  </th>
                            <th class="px-4 py-3"> </th>
                            <th class="px-4 py-3"> </th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                        @foreach ($scan as $shippings)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{$shippings->id}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shippings->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shippings->transfer_flag }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shippings->wharehouse }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shippings->fecha_shi}} {{ $shippings->hora_shi}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form method="get" action="{{ route('WHExtInOutController.export') }}">
                                    <div class="flex  justify-end">
                                        <span class="text-gray-700 dark:text-gray-400">
                                            <input type="hidden" name="id" id="id" value={{$shippings->id }}>
                                        </span>
                                        <button
                                            class="flex items-center justify-between px-4 py-4 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                            Imprimir <br>General
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form method="get" action="{{ route('WHExtInOutController.exportDetail') }}">
                                    <div class="flex  justify-end">
                                        <span class="text-gray-700 dark:text-gray-400">
                                            <input type="hidden" name="id" id="id" value={{$shippings->id }}>
                                        </span>
                                        <button
                                            class="flex items-center justify-between px-4 py-4 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                            Imprimir <br> Detalle
                                        </button>
                                    </div>
                                </form>
                            </td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{-- Mostrando {{ $consignments->firstItem() }} - {{ $consignments->lastItem() }} de {{ $consignments->total()}}
                </span> --}}
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">

                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
