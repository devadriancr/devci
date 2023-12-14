<x-app-layout title="Reporte de Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Reporte de Consigna MC/MH
        </h2>

        <!-- General elements -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Elements
        </h4> -->

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

        <form action="{{ route('shipping-instruction.download-consignments') }}" method="post">
            @csrf
            <div class="flex flex-auto gap-4 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                {{-- <div class="flex-auto">
                    <label class="text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Tipo de Consigna
                        </span>
                        <select name="type" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option value="">Seleciona Una</option>
                            <option value="MC">MC</option>
                            <option value="MH">MH</option>
                        </select>
                    </label>
                </div> --}}
                <div class="flex-auto">
                    <label class="text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Fecha de Inicio</span>
                        <input name="start" type="date" class="w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                    </label>
                </div>
                <div class="flex-auto">
                    <label class="text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Fecha de Termino</span>
                        <input name="end" type="date" class="w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                    </label>
                </div>
                <div class="flex-auto">
                    <label class="text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Tipo de Consigna
                        </span>
                        <select name="type_consignment" class="w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option>Selecione una Opción</option>
                            <option values="MC">MC</option>
                            <option value="MH">MH</option>
                        </select>
                    </label>
                </div>
                <div class="flex flex-auto justify-center items-center">
                    <button type="submit" class="mt-6 flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-4">Descargar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
