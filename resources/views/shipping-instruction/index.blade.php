<x-app-layout title="Formato SI">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Shipping Instruction
        </h2>

        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4> -->
        <div class="px-4 py-3 mt-2 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
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
            <div class="mb-4 font-medium text-green-600">
                {{ session('success') }}
            </div>
            @endif
            <form method="POST" action="{{ route('shipping-instruction.store') }}" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm">
                    <div class="relative text-gray-500 focus-within:text-purple-600">
                        <input type="file" name="import_file" class="block w-full pr-20 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                        <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Cargar
                        </button>
                    </div>
                </label>
            </form>
        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">No. Contenedor</th>
                            <th class="px-4 py-3">Factura</th>
                            <th class="px-4 py-3">Serial</th>
                            <th class="px-4 py-3">No. Parte</th>
                            <th class="px-4 py-3">No. Parte</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Time</th>
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
                    Mostrando {{ $shippings->firstItem() }} - {{ $shippings->lastItem() }} de {{ $shippings->total()}}
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
