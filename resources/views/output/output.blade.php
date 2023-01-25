<x-app-layout title="Salidas">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Salidas
        </h2>
        <div class="px-4 py-3 gap-x-2 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <label class="text-sm">
                <div class="relative text-gray-500 focus-within:text-purple-600">
                    <form method="GET" action="{{ route('output.index') }}">
                        <input name="search" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="" autocomplete="off" />
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
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Transacción</th>
                            <th class="px-4 py-3">Almacen</th>
                            <th class="px-4 py-3">Locación</th>
                            <th class="px-4 py-3">Contenedor</th>
                            <th class="px-4 py-3">Fecha de Salida</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($outputs as $output)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $output->supplier ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->serial ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->item->item_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->item_quantity ?? ''}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->transactiontype->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->location->warehouse->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->location->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->container->code ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $output->created_at ?? '' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $outputs->firstItem() }} - {{ $outputs->lastItem() }} de {{ $outputs->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{ $outputs->withQueryString()->links()}}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
</x-app-layout>
