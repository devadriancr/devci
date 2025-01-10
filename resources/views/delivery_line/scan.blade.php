<x-app-layout title="Entrega a Producción">
    <div class="container mx-auto px-6 py-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg shadow-md my-4">
            <div class="col-span-2 text-center">
                <h4 class="mb-4 text-lg font-bold  dark:text-gray-300">
                    Entrega de Material
                </h4>
            </div>

            <div class="col-span-2">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-4 text-base text-gray-600">
                    <div class="flex">
                        <span class="font-semibold mr-2">No de Entrega:</span>
                        <span class="font-normal">{{ $entrega->id }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-semibold mr-2">No de Nómina:</span>
                        <span class="font-normal">{{ $entrega->control_number }}</span>
                    </div>
                </div>
            </div>

            <div class="col-span-2">
                <div class="flex justify-end gap-4 mt-4">
                    <!-- Finalizar escaneo -->
                    <form method="POST" action="{{ route('Delivery.store') }}">
                        @csrf
                        <input name="Delivery_id" value="{{ $entrega->id }}" hidden>
                        <button type="submit"
                            class="flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg transition-colors duration-150 hover:bg-purple-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-2">Finalizar escaneo</span>
                        </button>
                    </form>

                    <!-- Escaneo con código de barras -->
                    <form method="POST" action="{{ route('Delivery.scanbar') }}">
                        @csrf
                        <input name="Delivery_id" value="{{ $entrega->id }}" hidden>
                        <input name="location_id" value="{{ $entrega->location_id }}" hidden>
                        <button type="submit"
                            class="flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg transition-colors duration-150 hover:bg-purple-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span class="ml-2">Escaneo con código de barras</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formulario de ingreso de serial -->
        <div class="my-6 bg-white p-4 rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('Delivery.update') }}">
                @csrf
                <input name="delivery_id" value="{{ $entrega->id }}" hidden>
                <input name="location_id" value="{{ $entrega->location_id }}" hidden>


                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Código Qr</span>
                        <input name="serial" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autofocus />
                    </label>

                    <!-- Mensajes de error -->
                    @if (isset($error))
                    <div class="{{ $error == 5 ? 'bg-green-100' : 'bg-red-100' }} border {{ $error == 5 ? 'border-green-400' : 'border-red-400' }} text-{{ $error == 5 ? 'green' : 'red' }}-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">{{ $error == 5 ? 'Mensaje informativo!' : 'Alerta!' }}</strong>
                        <span class="block sm:inline">{{ $msg }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-{{ $error == 5 ? 'green' : 'red' }}-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                            </svg>
                        </span>
                    </div>
                    @endif


                <div class="flex justify-end mt-4 gap-2">
                    <button type="submit" id="finish"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="ml-2">Guardar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de consignaciones -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">Número de Parte</th>
                            <th class="px-4 py-3">Tipo Consigna</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Proveedor</th>
                            <th class="px-4 py-3">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($scan as $consignment)
                        <tr class="text-sm text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $consignment->serial }}</td>
                            <td class="px-4 py-3">{{ $consignment->item->item_number }}</td>
                            <td class="px-4 py-3">{{ $consignment->type_consignment ?? '' }}</td>
                            <td class="px-4 py-3">{{ $consignment->item_quantity }}</td>
                            <td class="px-4 py-3">{{ $consignment->supplier }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('output.returnitem') }}">
                                    @csrf
                                    <input name="id" value="{{ $consignment->id }}" hidden>
                                    <input name="delivery_id" value="{{ $entrega->id }}" hidden>
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
