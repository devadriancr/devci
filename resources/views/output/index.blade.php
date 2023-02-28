<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">
        <form method="post" action="{{ route('output.create') }}">
            @csrf
            <input name="travel_id" value="{{ $travels->id }}" hidden>
            <input name="location_id" value="{{ $travels->location_id }}" hidden>
            <span class="text-gray-700 dark:text-gray-400">Serial</span>
            <input name="serial" id="serial" minlength='35'
                class="block w-full my-2 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                autofocus='enable'>
                <button id="finish11" name="finish11"
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="ml-2">Guardar</span>
                </button>

        </form>
    </div>
    <script>
        (function() {
            var allowSubmit = true;
            finish11.onsubmit = function() {
                if (allowSubmit)
                    allowSubmit = false;
                else
                    return false;
            }
        })();
    </script>
</x-app-layout>
