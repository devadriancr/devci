<x-app-layout title="Items">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Items
        </h2>

        <!-- With actions -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4> -->
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
                                    {{ $item->iid }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->item_number }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->item_description }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->measurement_type_id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->item_type_id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->item_class_id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $item->standard_pack_id }}
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
