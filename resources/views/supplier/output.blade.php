<x-app-layout title="Salidas Proveedores">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Salidas Proveedores
        </h2>

        <!-- With actions -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4> -->

        <div class="px-4 py-3 gap-x-2 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800"">
            <label class="text-sm">
                <div class="relative text-gray-500 focus-within:text-purple-600">
                    <form action="{{ route('supplier-output.index') }}" method="get">
                        <input name="search" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" autocomplete="off"/>
                        <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Buscar
                        </button>
                    </form>
                </div>
            </label>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Proveedor</th>
                            <th class="px-4 py-3">No. Orden</th>
                            <th class="px-4 py-3">Secuencia</th>
                            <th class="px-4 py-3">No. Parte</th>
                            <th class="px-4 py-3">SNP</th>
                            <th class="px-4 py-3">Transacción</th>
                            <th class="px-4 py-3">Almacen</th>
                            <th class="px-4 py-3">Localidad</th>
                            <th class="px-4 py-3">Fecha de Recepción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($suppliers as $supplier)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->supplier ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->order_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->sequence ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->item->item_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->quantity ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->transactiontype->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->location->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->location->warehouse->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $supplier->updated_at ?? '' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} de {{ $suppliers->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{ $suppliers->withQueryString()->links()}}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
