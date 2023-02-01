<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-4 text-2xl font-semibold text-center text-gray-700 dark:text-gray-200">
            Recepción de Consigan MY
        </h2>

        <div class="flex justify-end mb-3">
            <a href="{{ route('shipping-instruction.scan-barcode') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="ml-2">Código de Barras</span>
            </a>
        </div>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('shipping-instruction.store-scan') }}" method="post">
                @csrf
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400">Codgigo Qr</span>
                    <input id="qr" name="qr" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="off" />
                </label>
                <div class="flex justify-end mt-2">
                    <button type="submit" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="ml-2">Guardar</span>
                    </button>
                </div>
            </form>
            @if (session('success'))
            <div class="mb-4 font-medium text-green-600">
                {{ session('success') }}
            </div>
            @endif

            @if (session('warning'))
            <div class="mb-4 font-medium text-red-600">
                {{ session('warning') }}
            </div>
            @endif
        </div>
    </div>
    <script>
        window.onload = function() {
            document.getElementById("qr").focus();
        };
    </script>
</x-app-layout>
