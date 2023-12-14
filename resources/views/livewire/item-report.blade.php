<div>

    <label class="block text-sm">
        <span class="text-gray-700 dark:text-gray-400">Número de Parte</span>
        <input type="text" wire:model="search" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
    </label>

    <div class="col-span-12 px-4 py-3">
        <div class="grid grid-cols-3 gap-4">
            @foreach ($partNumbers as $partNumber)
            <div>
                <label class="flex items-center dark:text-gray-400">
                    <input type="checkbox" wire:model="selectedPartNumbers" value="{{ $partNumber->id }}"  class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                    <span class="ml-2 text-xs">
                        {{ $partNumber->item_number }}
                    </span>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">
            Mostrando {{ $partNumbers->firstItem() }} - {{ $partNumbers->lastItem() }} de {{ $partNumbers->total()}}
        </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
                <ul class="inline-flex items-center">
                    {{ $partNumbers->links() }}
                </ul>
            </nav>
        </span>
    </div>

    <!-- <div class="px-4 py-3 ">
        <label class="block mt-4 text-sm">
            <span class="font-bold text-gray-700 dark:text-gray-400">
                Seleccionados:
            </span>
            <span class="text-xs text-gray-600 dark:text-gray-400">
                @foreach ($selectedPartNumbers as $selectedPartNumber)
                {{ $selectedPartNumber }},
                @endforeach
            </span>
        </label>
    </div> -->

    @if (!empty($selectedPartNumbers))
    <div class="flex flex-row gap-4">

        <!-- <label class="text-sm">
                <input type="date" wire:model="startDate" class="text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                <input type="date" wire:model="endDate" class="text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
            </label> -->
        <div class="basis-1/4">

            <label class="text-sm">
                <input type="date" wire:model="startDate" class="w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
            </label>
        </div>

        <div class="basis-1/4">
            <label class="text-sm">
                <input type="date" wire:model="endDate" class="w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
            </label>
        </div>

        <div class="basis-1/4">
            <!-- <button wire:click="exportSelected" class="bg-blue-500 text-white px-4 py-2 rounded">Exportar Seleccionados</button> -->

            <button wire:click="exportSelected" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                </svg>
                <span>Descargar Reporte</span>
            </button>
        </div>

        <div class="basis-1/4">
            <!-- <button wire:click="clearSelection" class="bg-gray-500 text-white px-4 py-2 rounded">Limpiar Selección</button> -->

            <button wire:click="clearSelection" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Limpiar Selección</span>
            </button>
        </div>

    </div>
    @endif

    @if ($errors->any())
    <div class="text-red-500">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
