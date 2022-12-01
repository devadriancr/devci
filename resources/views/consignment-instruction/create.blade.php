<x-app-layout title="Consigna">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Consigna
        </h2>
        <div class="flex flew-row justify-end">
            <form method="POST" action="{{ route('consigment-instruction.check') }}">
                @csrf
                <input name="container_id" value="{{ $container->id }}" hidden>
                <input name="container_code" value="{{ $container->code }}" hidden>
                <input name="container_date" value="{{ $container->date }}" hidden>
                <input name="container_time" value="{{ $container->time }}" hidden>
                <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="ml-2">Validar</span>
                </button>
            </form>
        </div>

        <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('consigment-instruction.store') }}">
                <h4 class="my-2 text-center text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Recibó de Consigna
                </h4>
                @csrf
                <div class="grid grid-cols-12 gap-4 my-2 uppercase font-bold md:font-medium md:text-base sm:text-base sm:font-normal text-gray-600 dark:text-gray-300">
                    <div class="col-span-6">
                        <input name="container_id" value="{{ $container->id }}" hidden>
                        <span>
                            {{ $container->code }}
                        </span>
                    </div>
                    <div class="col-span-6 text-right">
                        <span>
                            {{ $container->arrival_date }} {{ $container->arrival_time }}
                        </span>
                    </div>
                </div>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Serial</span>
                    <input name="code_qr" class="block w-full my-2 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="S6030XXX1234XX" autofocus />
                </label>

                <div class="flex justify-end mt-2 gap-2">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="ml-2">Guardar</span>
                    </button>
                </div>
            </form>

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
            <div class="mb-4 text-lg font-bold text-green-600">
                {{ session('success') }}
            </div>
            @endif
        </div>


    </div>
</x-app-layout>
