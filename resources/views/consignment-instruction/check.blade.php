<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Consigna
        </h2>

        <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
            Material no Encontrado
        </h4>
        <div class="flex flew-row justify-end gap-2 mb-2">
            <div>
                <form method="POST" action="{{ route('consigment-instruction.found') }}">
                    @csrf
                    <input name="container_id" value="{{ $container_id }}" hidden>
                    <input name="container_code" value="{{ $container_code }}" hidden>
                    <input name="container_date" value="{{ $container_date }}" hidden>
                    <input name="container_time" value="{{ $container_time }}" hidden>
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ml-2">Reporte de Registrados</span>
                    </button>
                </form>
            </div>
            <div>
                <form method="POST" action="{{ route('consigment-instruction.not-found') }}">
                    @csrf
                    <input name="container_id" value="{{ $container_id }}" hidden>
                    <input name="container_code" value="{{ $container_code }}" hidden>
                    <input name="container_date" value="{{ $container_date }}" hidden>
                    <input name="container_time" value="{{ $container_time }}" hidden>
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ml-2">Reporte de Faltantes</span>
                    </button>
                </form>
            </div>
            <div>
                <form method="POST" action="{{ route('consigment-instruction.finish') }}">
                    @csrf
                    <input name="container_id" value="{{ $container_id }}" hidden>
                    <input name="container_code" value="{{ $container_code }}" hidden>
                    <input name="container_date" value="{{ $container_date }}" hidden>
                    <input name="container_time" value="{{ $container_time }}" hidden>
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <span class="ml-2">Terminar</span>
                    </button>
                </form>
            </div>

        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Contenedor</th>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Hora</th>
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">No. Parte</th>
                            <th class="px-4 py-3">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($dataArray as $data)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">

                                {{ $data['container'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">

                                {{ $data['arrival_date'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">

                                {{ $data['arrival_time'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">

                                {{ $data['serial'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">

                                {{ $data['part_no'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">

                                {{ $data['part_qty'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Faltantes {{ $found }}
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
