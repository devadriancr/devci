<table class="w-full whitespace-no-wrap">
    <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Serial</th>
            <th class="px-4 py-3">Numero de Parte</th>
            <th class="px-4 py-3">Cantidad </th>
            <th class="px-4 py-3">Proveedor </th>

        </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($scan as $consignment)
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

            </tr>
        @endforeach
    </tbody>
</table>
