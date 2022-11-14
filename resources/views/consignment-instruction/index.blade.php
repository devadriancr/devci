<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Escaneo CI
        </h2>

        <h4 class="mb-4 text-lg font-semibold text-center text-gray-600 dark:text-gray-300">
            Validacion de Escaneo
        </h4>
        <div class="flex flew-row justify-end mb-2">
            <form method="POST" action="{{ route('consigment-instruction.finish') }}">
                @csrf
                <input name="container_id" value="{{ $container_id }}" hidden>
                <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <span class="ml-2">Terminar Escaneo</span>
                </button>
            </form>
        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Contenedor</th>
                            <th class="px-4 py-3">Factura</th>
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
                                {{ $data['invoice'] }}
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
                    <!--  -->
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
