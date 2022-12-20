
<table >
    <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th Style=" border:1px solid black; border-collapse: collapse">Numero parte </th>
            <th Style=" border:1px solid black; border-collapse: collapse">Cantidad </th>
            <th Style=" border:1px solid black; border-collapse: collapse">id de orden  </th>
        </tr>
    </thead>
    <tbody >
        @foreach ($order as $consignment)
            <tr class="text-gray-700 dark:text-gray-400  border:1px solid black; border-collapse: collapse">
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->item->item_number }}
                </td>
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->item_quantity }}
                </td>
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->orden_information_id }}
                </td>


            </tr>
        @endforeach
    </tbody>
</table>
