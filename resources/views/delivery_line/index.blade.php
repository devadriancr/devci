<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Entrega de Material
        </h2>

        <form method="POST" action="{{ route('Delivery.create') }}">
            <div class="mt-4 text-sm">
                @csrf
                <span class="text-gray-700 dark:text-gray-400">
                    Nueva Entrega
                </span>

                @if ($errors->any())
                    <p>Hay errores!</p>
                @endif

                <div class="mt-2 group flex items-center">

                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">No de Nomina </span>
                        <input id='number_control' name='number_control' maxlength='5' max='99999' type='number'
                            class="block w-30 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                    <button
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="ml-4">crear</span>
                    </button>
                </div>
            </div>

        </form>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3"> Id de entrega</th>
                            <th class="px-4 py-3">Nomina</th>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                        @foreach ($travels as $travelss)
                            <tr class="text-gray-700 dark:text-gray-400">

                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->control_number }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $travelss->created_at }}
                                </td>

                                <td class="px-4 py-3 text-sm">

                                    @if ($travelss->finish !=1)

                                    <form method="POST" action={{ route('Delivery.scanqr') }}>
                                        @csrf

                                        <input name="Delivery_id" value={{$travelss->id }} hidden>
                                        <input name="location_id" value={{  $travelss->location_id}} hidden>
                                        <div class="flex justify-end mt-2 gap-2">
                                            <button
                                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                                                  </svg>

                                                <span class="ml-2">Escanear</span>
                                            </button>
                                        </div>
                                    </form>
                                        @else
                                         Entrega finalizado
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">

                                    <form method="POST" action="{{ route('Delivery.export') }}">
                                        @csrf
                                        <input name="delivery_id" value={{ $travelss->id }} hidden>

                                        <div class="flex justify-end mt-2 gap-2">
                                            <button
                                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="ml-2">imprimir Reporte </span>
                                            </button>
                                        </div>
                                    </form>


                                </td>




                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $travels->firstItem() }} - {{ $travels->lastItem() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{ $travels->withQueryString()->links() }}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
