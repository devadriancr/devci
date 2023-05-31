<x-app-layout title="Busqueda de Movimientos">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Busqueda de Movimientos
        </h2>

        <!-- With avatar -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with avatars
        </h4> -->

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="get" action="{{ route('output.search') }}">
                <label class="block text-sm">
                    <!-- <span class="text-gray-700 dark:text-gray-400">Name</span> -->
                    <input name="search" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="" />
                </label>
                <div class="flex justify-end mt-2 gap-2">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Buscar</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Proveedor</th>
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">No Parte </th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Tipo de Cosigna</th>
                            <th class="px-4 py-3">Locación</th>
                            <th class="px-4 py-3">Contenedor</th>
                            <th class="px-4 py-3">Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($data as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->supplier ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->serial ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->item_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->item_quantity ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->type_consignment ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->location ?? '' }} - {{ $consignment->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->container ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->date ?? '' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $data->firstItem() }} - {{ $data->lastItem() }} de {{ $data->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $data->withQueryString()->links() }}
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
