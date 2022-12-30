<x-app-layout title="Carga de Datos Consiga">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Carga de Datos Consigna
        </h2>

        <!-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4> -->
        <div class="px-4 py-3 mt-2 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
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

            <form method="POST" action="{{ route('consigment-instruction.data-upload-store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-10">
                        <label class="block text-sm">
                            <div class="relative text-gray-500 focus-within:text-purple-600">
                                <input type="file" name="import_file" class="block w-full pr-20 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                                <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                    Cargar
                                </button>
                            </div>
                        </label>
                    </div>
                    <div class="col-span-2 flex justify-end">
                        <a href="{{ route('consigment-instruction.data-upload-inventory') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="ml-4">Registrar</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
