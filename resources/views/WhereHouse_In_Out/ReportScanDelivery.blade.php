<table style="width:100%; border:1px solid black; border-collapse: collapse">
    <thead>
        <tr Style=" border:1px solid black; border-collapse: collapse">
            <th></th>
            <th Style=" border:1px solid black; border-collapse: collapse">Folio</th>
            <th Style=" border:1px solid black; border-collapse: collapse">Empleado</th>
            <th Style=" border:1px solid black; border-collapse: collapse">Fecha</th>
        </tr>
    </thead>
    <tbody>
        <tr Style=" border:1px solid black; border-collapse: collapse">
            <th></th>
            <td Style=" border:1px solid black; border-collapse: collapse">
                {{ $travel->id }}
            </td>
            <td Style=" border:1px solid black; border-collapse: collapse">
                {{ $travel->control_number }}
            </td>
            <td Style=" border:1px solid black; border-collapse: collapse">
                {{ $travel->created_at }}
            </td>
        </tr>

    </tbody>
</table>


<table>
    <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th Style=" border:1px solid black; border-collapse: collapse"></th>
            <th Style=" border:1px solid black; border-collapse: collapse">Proveedor </th>
            <th Style=" border:1px solid black; border-collapse: collapse">Serial</th>
            <th Style=" border:1px solid black; border-collapse: collapse">Numero de Parte</th>
            <th Style=" border:1px solid black; border-collapse: collapse">Cantidad </th>

        </tr>
    </thead>
    <tbody>
        @php
        $cont=0;
        @endphp
        @foreach ($scan as $consignment)
            <tr class="text-gray-700 dark:text-gray-400  border:1px solid black; border-collapse: collapse">
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{$cont=$cont+1}}
                </td>
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->supplier }}
                </td>
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->serial }}
                </td>
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->item->item_number }}
                </td>
                <td Style=" border:1px solid black; border-collapse: collapse">
                    {{ $consignment->item_quantity }}
                </td>




            </tr>
        @endforeach
    </tbody>
</table>
