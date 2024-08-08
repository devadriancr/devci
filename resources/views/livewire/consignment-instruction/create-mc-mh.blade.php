<div>
    <form wire:submit.prevent="save">
        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div>
                <label class="block text-sm my-2">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Ingrese Código de Barras') }}</span>
                    <input wire:model.defer="code_qr" id="code_qr" name="code_qr" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none form-input {{ session('success') ? 'border-green-600 focus:border-green-400 focus:shadow-outline-green' : '' }} {{ session('warning') ? 'border-red-600 focus:border-red-400 focus:shadow-outline-red' : '' }}" autocomplete="off" />

                    @if (session('success'))
                    <span class="block mt-2 text-xs text-green-600 dark:text-green-400">
                        {{ session('success') }}
                    </span>
                    @endif

                    @if (session('warning'))
                    <span class="block mt-2 text-xs text-red-600 dark:text-red-400">
                        {{ session('warning') }}
                    </span>
                    @endif

                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <span class="block mt-2 text-xs text-red-600 dark:text-red-400">
                        {{ $error }}
                    </span>
                    @endforeach
                    @endif
                </label>
            </div>

            <div class="flex flex-row justify-between">
                <div>
                    <h4 class="my-2 text-center text-lg uppercase font-bold text-gray-600 dark:text-gray-300">
                        {{ __('Conteo de Escaneos') }} : {{ session('scan_count', 0) -1 }}
                    </h4>
                </div>
                <div>
                    <input type="submit" value="Guardar" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" />
                </div>
            </div>
        </div>
    </form>
</div>
