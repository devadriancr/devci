<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <div class="max-w-sm w-full lg:max-w-full mt-5 lg:flex rounded-lg  bg-white">
            <div class=" py-8">
                <div class="text-gray-900 font-bold text-xl p-2 ">Busqueda de movimientos</div>
            </div>
        </div>
    </div>
    <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form method="get" action={{ route('output.search') }}>

            <div class="mt-4 text-sm">
                {{-- <span class="text-gray-700 dark:text-gray-400">
                    tipo de busqueda
                </span> --}}
                <div class="mt-2">
                    {{-- <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                        <input name='buscar' type="radio" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"  value="serial" required />
                        <span class="ml-2">Serial o QR </span>
                    </label> --}}
                    {{-- <label  class="inline-flex items-center ml-6 text-gray-600 dark:text-gray-400">
                        <input  name='buscar' type="radio" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"  value="parte" required />
                        <span class="ml-2">No de parte</span>
                    </label> --}}
                </div>
            </div>
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Serial o QR</span>
                <input name="serial" id="serial"
                    class="block w-full my-2 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    autofocus='enable' required>

                <div class="flex justify-end mt-2 gap-2">
                    <button
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="ml-2">Buscar</span>
                    </button>
                </div>
        </form>
    </div>
    @if (isset($error))
        @if ($error >= 1)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold"></strong>
                <span class="block sm:inline">{{ $msg }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @else
        @endif


    @endif
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Serial</th>
                        <th class="px-4 py-3">Numero de Parte</th>
                        <th class="px-4 py-3">Cantidad </th>

                        <th class="px-4 py-3">Movimiento Ubicación </th>
                        <th class="px-4 py-3">Fecha Mov </th>
                        <th class="px-4 py-3">Viaje</th>
                        <th class="px-4 py-3">Contenedor</th>
                        <th class="px-4 py-3">Devolución</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @php
                        $cont=$total+1;
                    @endphp
                    @foreach ($in as $consignment)
                    @php
                        $cont=$cont-1;
                    @endphp
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
                                {{ $consignment->location->code ?? ''}} {{ $consignment->location->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->created_at }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->travel_id ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->container->code ?? ' ' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                    @php
                                        $loc=$consignment->location->code ?? '';
                                    @endphp
                                @if ( trim($loc)=='L12' && $cont==$total)
                                <div class="flex items-center space-x-4 text-sm">
                                    <form method="POST" action="{{ route('output.return') }}">
                                        @csrf
                                        <input name="id" value='{{ $consignment->id }}' hidden>
                                        {{-- <input name="travel_id" value='{{ $travels->id }}' hidden>
                                        <input name="serial" value='{{ $consignment->serial }}' hidden> --}}
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                            </svg>

                                        </button>
                                    </form>
                                </div>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">

            </span>
            <span class="col-span-2"></span>
            <!-- Pagination -->
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">

                </nav>
            </span>
        </div>
    </div>
    </div>
</x-app-layout>
