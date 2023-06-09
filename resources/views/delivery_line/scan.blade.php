<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <div class="bg-blue-500 bg-opacity-75  py-2">
            <div class="text-gray-900 font-bold text-xl p-2 dark:text-white">Información de entrega</div>
        </div>
        <div class="flex items-center bg-white">
            <div class="text-base m-5">
                <p class="font-semibold">No de entrega: </p>
                <p class="font-normal">{{ $entrega->id }}<br></p>
            </div>
            <div class="text-basem-5 bg-white">
                <p class="font-semibold">No de nomina: </p>
                <p class="font-normal">{{ $entrega->control_number }}<br></p>
            </div>
        </div>
        <form method="POST" action="{{ route('Delivery.store') }}">
            @csrf
            <input name="Delivery_id" value="{{ $entrega->id }}" hidden>
            <div class="flex justify-end mt-2 gap-2">
                <button
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="ml-2">Finalizar escaneo</span>
                </button>
            </div>
        </form>
        <form method="POST" action="{{ route('Delivery.scanbar') }}">
            @csrf
            <input name="Delivery_id" value="{{ $entrega->id }}" hidden>
            <input name="location_id" value="{{ $entrega->location_id }}" hidden>
            <div class="flex justify-end mt-2 gap-2">
                <button
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                    </svg>
                    <span class="ml-2">Escaneo con codigo de barras</span>
                </button>
            </div>
        </form>
        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('Delivery.update') }}">
                <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
                </h4>
                @csrf
                <input name="delivery_id" value="{{ $entrega->id }}" hidden>
                <input name="location_id" value="{{ $entrega->location_id }}" hidden>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Serial</span>
                    <input name="serial" id="serial" minlength='35'
                        class="block w-full my-2 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        autofocus='enable'>
                    @if (isset($error))
                        @if ($error != 0)
                            @if ($error == 5)
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <strong class="font-bold">Mensaje informativo!</strong>
                                    <span class="block sm:inline">{{ $msg }}</span>
                                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                        <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <title>Close</title>
                                            <path
                                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                        </svg>
                                    </span>
                                </div>
                            @else
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <strong class="font-bold">Alerta!</strong>
                                    <span class="block sm:inline">{{ $msg }}</span>
                                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                        <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <title>Close</title>
                                            <path
                                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                        </svg>
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3"
                                role="alert">
                                <p class="font-bold">Escaneo Correcto</p>

                            </div>
                        @endif
                    @endif

                    <div class="flex justify-end mt-2 gap-2">
                        <button id="finish"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="ml-2">Guardar</span>
                        </button>
                    </div>
                </label>
            </form>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">Numero de Parte</th>
                            <th class="px-4 py-3">Tipo consigna</th>
                            <th class="px-4 py-3">Cantidad </th>
                            <th class="px-4 py-3">Proveedor </th>
                            <th class="px-4 py-3">Eliminar</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @php
                            $CONT = 0;
                        @endphp
                        @foreach ($scan as $consignment)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">
                                    {{ $CONT = $CONT + 1 }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $consignment->serial }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $consignment->item->item_number }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $consignment->type_consignment ?? '' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $consignment->item_quantity }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $consignment->supplier }}
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('output.returnitem') }}">
                                        @csrf
                                        <input name="id" value="{{ $consignment->id }}" hidden>
                                        <input name="type" value="1" hidden>
                                        <input name="delivery_id" value="{{ $entrega->id }}" hidden>
                                        <div class="flex justify-end mt-2 gap-2">
                                            <button class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-2 px-4 rounded inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                                </svg>
                                              </button>
                                        </div>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        (function() {
            var allowSubmit = true;
            finish.onsubmit = function() {

                if (allowSubmit)
                    allowSubmit = false;
                else
                    return false;
            }
        })();
    </script>
 </x-app-layout>
