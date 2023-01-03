<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
         Lista de viajes
        </h2>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3"> </th>
                            <th class="px-4 py-3">Factura</th>
                            <th class="px-4 py-3">Carta Porte</th>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Locación</th>
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($travels as $travelss)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->invoice_number }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->carta_porte }}
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->created_at }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->location->code }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($travelss->finish == true)
                                        Finalizado
                                    @else
                                        <form method="POST" action="{{ route('output.store') }}">
                                            @csrf
                                            <input name="travel_id" value={{ $travelss->id }} hidden>

                                            <div class="flex justify-end mt-2 gap-2">
                                                <button
                                                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    <span class="ml-2">Escanear </span>
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">

                                    <form method="POST" action="{{ route('travel.export') }}">
                                        @csrf
                                        <input name="travel_id" value={{ $travelss->id }} hidden>

                                        <div class="flex justify-end mt-2 gap-2">
                                            <button
                                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="ml-2">imprimir Reporte </span>
                                            </button>
                                        </div>
                                    </form>


                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $travels->firstItem() }} - {{ $travels->lastItem() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{ $travels->withQueryString()->links() }}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
