<x-app-layout title="Request list">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Lista de orden
        </h2>
        {{-- <form method="GET" action={{ route('RequestList.order_detail') }}>
            <div class="flex justify-end mt-2 gap-2">
                <button
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="ml-2">Ver orden</span>
                </button>
            </div>
        </form> --}}
        <form method="GET" action={{ route('RequestList.receipt') }}>
            <input type='hidden'id='type_order' name='type_order' value='I'>
            <div class="flex justify-end mt-2 gap-2">
                <button
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="ml-2">Solicitar Material</span>
                </button>
            </div>
        </form>
        <form method="GET" action={{ route('RequestList.send') }}>
            <div class="flex justify-end mt-2 gap-2">
                <input type='hidden' id='type_order' name='type_order' value='O'>
                <button
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="ml-2">Enviar Material</span>
                </button>
            </div>
        </form>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Quantity</th>
                            <th class="px-4 py-3">Opening balance</th>
                            <th class="px-4 py-3">Safety Stok</th>
                            <th class="px-4 py-3">Almacen Ext</th>
                            <th class="px-4 py-3">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($report as $inventory)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory['item'] }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory['quantity'] }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory['opening'] }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory['safety'] }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory['qtyexterno'] }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $sum = $inventory['quantity'] + $inventory['opening'];
                                    @endphp
                                    @if ($sum > $inventory['safety'])
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            &nbsp; {{ $sum - $inventory['safety'] }} &nbsp;
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                            &nbsp; {{ $sum - $inventory['safety'] }} &nbsp;
                                        </span>
                                    @endif

                                </td>
                                <td>
                                    {{-- <form method="POST" action="{{ route('RequestList.order') }}">
                                        @csrf
                                        <input name="item_id" value={{ $inventory['item_id'] }} hidden>
                                        <input name="quantity" value={{ $sum - $inventory['safety'] }} hidden>
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                              </svg>
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{-- Mostrando {{ $report->firstItem() }} - {{ $report->lastItem() }} de {{ $report->total() }} --}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{-- {{ $report->withQueryString()->links() }} --}}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
