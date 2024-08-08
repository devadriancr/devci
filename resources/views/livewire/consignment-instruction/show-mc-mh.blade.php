<div>
    <div class="container grid px-6 mx-auto my-6">
        <h2 class="my-2 text-center text-xl uppercase font-bold text-gray-600 dark:text-gray-300">
            {{ __('Consigna MC/MH') }}
        </h2>

        @if ($scanEnabled)
        @livewire('consignment-instruction.create-mc-mh')

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">No Orden</th>
                            <th class="px-4 py-3">No Parte</th>
                            <th class="px-4 py-3">SNP</th>
                            <th class="px-4 py-3">Conteo</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Proveedores</th>
                            <th class="px-4 py-3">Tipo de Consigna</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($mcmh as $consignment)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ $consignment->no_order }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->item_number }}</td>
                            <td class="px-4 py-3 text-sm">{{ intval($consignment->total_quantity / $consignment->row_count) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->row_count }}</td>
                            <td class="px-4 py-3 text-sm">{{ intval($consignment->total_quantity) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->suppliers }}</td>
                            <td class="px-4 py-3 text-sm">{{ $consignment->type_consignment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">Mostrando {{ $mcmh->firstItem() }} - {{ $mcmh->lastItem() }} de {{ $mcmh->total() }}</span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">{{ $mcmh->links() }}</nav>
                </span>
            </div>
        </div>
        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <x-button wire:click="finishScanning" class="w-full flex items-center justify-center">
                <i class="fa-solid fa-qrcode mr-2"></i> <!-- Icono con margen derecho -->
                Finalizar Escaneo
            </x-button>
        </div>
        @else
        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <x-button wire:click="startScanning" class="w-full flex items-center justify-center">
                <i class="fa-solid fa-qrcode mr-2"></i> <!-- Icono con margen derecho -->
                Iniciar Escaneo
            </x-button>
        </div>
        @endif

    </div>
</div>
