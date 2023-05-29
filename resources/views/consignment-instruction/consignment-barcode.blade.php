<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Recepción Consigna MC/MH
        </h2>

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">¡Oh no! Algo salió mal.</div>

            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 mb-2 rounded relative" role="alert">
            <strong class="font-bold">¡Escaneado Correctamente!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if (session('warning'))
        <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 mb-2 rounded relative" role="alert">
            <strong class="font-bold">¡Advertencia!</strong>
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
        @endif

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('consigment-instruction.consignment-barcode-store') }}" method="post">
                @csrf
                <label class="block text-sm my-2">
                    <span class="text-gray-700 dark:text-gray-400">Código QR</span>
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input name="scan" class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" autocomplete="off" autofocus />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                    </div>
                </label>
                <div class="flex flex-row justify-end">
                    <button type="submit" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-4">Guardar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">

                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">No Parte</th>
                            <!-- <th class="px-4 py-3">Sequencia</th> -->
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Proveedor</th>
                            <th class="px-4 py-3">Tipo de Consigna</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white uppercase divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($mcmh as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->serial }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->item->item_number }}
                            </td>
                            <!-- <td class="px-4 py-3 text-sm">
                                {{ $consignment->sequence }}
                            </td> -->
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->item_quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->supplier }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->type_consignment }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->created_at }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $mcmh->firstItem() }} - {{ $mcmh->lastItem() }} de {{ $mcmh->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $mcmh->withQueryString()->links()}}
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
