<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Escaneo almacen {{$texto}}
        </h2>

        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('WHExtInOutController.store') }}">
                <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
                    @php
                        if($id==1)
                        {
                           $te=' Recibó de Consigna almacen a '.$texto;
                        }else {
                            $te=' Envio de Consigna almacen '.$texto;
                        }
                    @endphp

                        {{$te}}
                </h4>
                @csrf
                <input name="id" value={{$id}} hidden>
                <div class="grid grid-cols-12 gap-4 my-2 uppercase font-bold md:font-medium md:text-base sm:text-base sm:font-normal text-gray-600 dark:text-gray-300">
                    <div class="col-span-6">
                        <input name="container_id" value="" hidden>
                        <span>

                        </span>
                    </div>
                    <div class="col-span-6 text-right">
                        <span>

                        </span>
                    </div>
                </div>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Serial</span>
                    <input name="serial" class="block w-full my-2 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="S6030XXX1234XX" autofocus />
                </label>

                <div class="flex justify-end mt-2 gap-2">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="ml-2">Guardar</span>
                    </button>
                </div>
            </form>

            @if ($error!=0)
            <div class="mb-4">
                <div class="font-medium text-red-600">{{$msgerror}}</div>
                <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                    {{-- @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach --}}
                </ul>
            </div>
            @endif

            {{-- @if (sess1ion('success'))
            <div class="mb-4 text-lg font-bold text-green-600">
                {{ session('success') }}
            </div>
            @endif --}}
        </div>

        <div class="flex flew-row justify-end my-2">
            <form method="POST" action="{{ route('WHExtInOutController.shipping') }}">
                @csrf

                <input name="id" value={{$id}} hidden>
                <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <span class="ml-2">Crear Shipping</span>
                </button>
            </form>
        </div>


        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">Numero de Parte</th>
                            <th class="px-4 py-3">Cantidad </th>
                            <th class="px-4 py-3">Hora de Escaneo </th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Shipping </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($scan as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->serial }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->part_no }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->part_qty}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->date_Scan}}  {{$consignment->time_scan}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    if($consignment->status==0)
                                    {
                                        $sta='Abierto';
                                    }else {
                                        $sta='Cerrado';
                                    }
                                @endphp
                                {{ $sta}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $consignment->shippign}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{-- Mostrando {{ $consignments->firstItem() }} - {{ $consignments->lastItem() }} de {{ $consignments->total()}}
                </span> --}}
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
