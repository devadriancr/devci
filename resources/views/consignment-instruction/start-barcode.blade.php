<x-app-layout title="Iniciar Escaneo">
    <div class="container grid px-6 mx-auto">
        <div class="px-4 py-3 my-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="my-2 text-center text-lg uppercase font-bold text-gray-600 dark:text-gray-300">
                {{ __('Iniciar Escaneo') }}
            </h4>
            <div class="flex justify-center">
                <a href="{{ route('consignment-instruction.consignment-barcode-index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Iniciar Escaneo
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
