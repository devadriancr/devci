<x-app-layout title="Tipo de Transacciones">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Tipo de Transaciones
        </h2>

        <!-- With actions -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4> -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Código</th>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($transactions as $transaction)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">
                                    {{ $transaction->code }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $transaction->name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $transaction->description }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} de {{ $transactions->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $transactions->withQueryString()->links()}}
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
