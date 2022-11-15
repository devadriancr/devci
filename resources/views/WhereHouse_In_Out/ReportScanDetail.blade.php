
<table class="w-full whitespace-no-wrap">
    <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Serial</th>
            <th class="px-4 py-3">Numero de Parte</th>
            <th class="px-4 py-3">Cantidad </th>
            <th class="px-4 py-3">Hora de Escaneo </th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3">Shipping </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($scan as $consignment)
            <tr class="text-gray-700 dark:text-gray-400">
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
                    {{ $consignment->date_Scan }} {{ $consignment->time_scan }}
                </td>
                <td class="px-4 py-3 text-sm">
                    @php
                        if ($consignment->status == 0) {
                            $sta = 'Abierto';
                        } else {
                            $sta = 'Cerrado';
                        }
                    @endphp
                    {{ $sta }}
                </td>
                <td class="px-4 py-3 text-sm">
                    {{ $consignment->shippign }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
