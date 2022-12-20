<x-app-layout title="Entradas">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Entradas
        </h2>
        {{-- <div class="flex justify-end pt-2 pb-4 gap-2">
            <a href="{{ route('input.upload') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span class="ml-4">Cargar Alamacenes</span>
        </a>
        <a href="{{ route('input.create') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span class="ml-4">Agregar Entradas</span>
        </a>
    </div> --}}

    <div class="px-4 py-3 gap-x-2 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="text-sm">
            <div class="relative text-gray-500 focus-within:text-purple-600">
                <form method="GET" action="{{ route('input.index') }}">
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
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Cantidad</th>
                        <th class="px-4 py-3">Transacción</th>
                        <th class="px-4 py-3">Almacen</th>
                        <th class="px-4 py-3">Locación</th>
                        <th class="px-4 py-3">Proveedor</th>
                        <th class="px-4 py-3">Serial</th>
                        <th class="px-4 py-3">Contenedor</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($inputs as $input)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3 text-sm">
                            {{ $input->item->item_number ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->item_quantity ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->transaction->code ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->location->warehouse->code ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->location->code ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->supplier ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->serial ?? ''}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $input->container->code ?? ''}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">
                Mostrando {{ $inputs->firstItem() }} - {{ $inputs->lastItem() }} de {{ $inputs->total()}}
            </span>
            <span class="col-span-2"></span>
            <!-- Pagination -->
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                        {{ $inputs->withQueryString()->links()}}
                    </ul>
                </nav>
            </span>
        </div>
    </div>
    </div>
</x-app-layout>
