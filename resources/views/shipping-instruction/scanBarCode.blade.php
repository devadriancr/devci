<x-app-layout title="Recepcion Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Código de Barras
        </h2>

        <!-- General elements -->
        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Código de Barras
        </h4> -->
        <div class="px-4 py-3 mb-8 gap-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
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

            @if (session('warning'))
            <div class="mb-4 font-medium text-yellow-600">
                {{ session('warning') }}
            </div>
            @endif

            <form action="{{ route('shipping-instruction.save-barcode') }}" method="post">
                @csrf
                <div>
                    <label class="block text-sm mt-2">
                        <span class="text-gray-700 dark:text-gray-400">No. Parte</span>
                        <input name="part" type="text" class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autofocus required />
                    </label>
                    <label class="block text-sm mt-2">
                        <span class="text-gray-700 dark:text-gray-400">Cantidad</span>
                        <input name="quantity" type="text" class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required />
                    </label>
                    <label class="block text-sm mt-2">
                        <span class="text-gray-700 dark:text-gray-400">Proveedor</span>
                        <input name="supplier" type="text" class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required />
                    </label>
                    <label class="block text-sm mt-2">
                        <span class="text-gray-700 dark:text-gray-400">Serial</span>
                        <input name="serial" type="text" class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required />
                    </label>
                </div>

                <div class="flex flex-row justify-end mt-2 gap-4">
                    <a href="{{ route('shipping-instruction.scan') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        <span class="ml-2">Regresar</span>
                    </a>
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="ml-4">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        window.onload = function() {
            document.getElementById("part").focus();
        };
    </script>
</x-app-layout>
