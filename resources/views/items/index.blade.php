<x-app-layout title="Items">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Items
        </h2>

        <!-- With actions -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4> -->
        <div class="flex justify-end pt-2 pb-4">
            <a href="{{ route('item.upload') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="ml-4">Cargar Items</span>
            </a>
        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Record ID</th>
                            <th class="px-4 py-3">No. Parte</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Unidad de Medida</th>
                            <th class="px-4 py-3">Tipo de Item</th>
                            <th class="px-4 py-3">Clase de Item</th>
                            <th class="px-4 py-3">Standard Pack</th>
                            <!-- <th class="px-4 py-3">Fecha de Creación</th>
                            <th class="px-4 py-3">Hora de Creación</th> -->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($items as $item)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $item->iid ?? ''}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->item_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->item_description ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->measurementType->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->itemType->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->itemClass->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->standard_pack_id ?? '' }}
                            </td>
                            <!-- <td class="px-4 py-3 text-sm">
                                    {{ $item->creation_date }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->creation_time }}
                                </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $items->firstItem() }} - {{ $items->lastItem() }} de {{ $items->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $items->withQueryString()->links()}}
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
