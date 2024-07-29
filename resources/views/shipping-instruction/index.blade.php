<x-app-layout title="{{ __('Shipping Instruction') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Shipping Instruction') }}
        </h2>

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">{{ __('¡Oh no! Algo salió mal.') }}</div>
            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 mb-2 rounded relative" role="alert">
            <strong class="font-bold">Advertencia</strong>
            <span class="block sm:inline">
                {{ session('warning') }}
            </span>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-2 rounded relative" role="alert">
            <strong class="font-bold">Éxito</strong>
            <span class="block sm:inline">
                {{ session('success') }}
            </span>
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-2 rounded relative" role="alert">
            <strong class="font-bold">Error</strong>
            <span class="block sm:inline">
                {{ session('error') }}
            </span>
        </div>
        @endif

        <div class="flex flex-wrap gap-2 px-4 py-3 mt-2 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full sm:w-1/2 md:w-1/4 mb-2">
                <form method="POST" action="{{ route('shipping-instruction.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="relative">
                        <input type="file" name="import_file" accept=".csv, .xlsx" class="block w-full text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="{{ __('Seleccionar archivo') }}" />
                        <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            {{ __('Cargar') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="w-full sm:w-1/2 md:w-3/4 flex gap-2">
                <div class="w-full md:w-1/3 mb-2">
                    <a href="{{ route('input.item-report') }}" class="flex items-center justify-end px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-4 text-xs">{{ __('Reporte No. Parte') }}</span>
                    </a>
                </div>
                <div class="w-full md:w-1/3 mb-2">
                    <a href="{{ route('shipping-instruction.report-consignments') }}" class="flex items-center justify-end px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-4 text-xs">{{ __('Reporte MC/MH') }}</span>
                    </a>
                </div>
                <div class="w-full md:w-1/3 mb-2">
                    <a href="{{ route('shipping-instruction.report-si') }}" class="flex items-center justify-end px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-4 text-xs">{{ __('Reporte MY') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">{{ __('No. Contenedor') }}</th>
                            <th class="px-4 py-3">{{ __('Factura') }}</th>
                            <th class="px-4 py-3">{{ __('Serial') }}</th>
                            <th class="px-4 py-3">{{ __('No. Parte') }}</th>
                            <th class="px-4 py-3">{{ __('Cantidad Parte') }}</th>
                            <th class="px-4 py-3">{{ __('Fecha') }}</th>
                            <th class="px-4 py-3">{{ __('Hora') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($shippings as $shipping)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->container }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->invoice }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->serial }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->part_no }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->part_qty }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->arrival_date }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $shipping->arrival_time }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $shippings->firstItem() }} - {{ $shippings->lastItem() }} {{ __('de') }} {{ $shippings->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $shippings->withQueryString()->links() }}
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
