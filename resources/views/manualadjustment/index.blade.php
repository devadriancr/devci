<x-app-layout title="Escaneo CI">
    <div class="container grid px-6 mx-auto">

        <div class="bg-blue-500 bg-opacity-75  py-2">
            <div class="dark:text-gray-900 font-bold text-xl p-2 text-white">Ajuste Manual</div>
        </div>
        <form method="POST" action="{{ route('travel.store') }}">


                @csrf
                @method('POST')
                <span class="text-gray-700 dark:text-gray-400">
                    Nueva Entrada
                </span>
                <div >
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400"> QR </span>
                        <input id='QR' name='QR'
                            class="block mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                </div>
                <div class="mt-2 group flex items-center">
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Numero de parte</span>
                        <input id='Part' name='Part'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Cantidad</span>
                        <input id='Cantidad' name='Cantidad'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Supplier</span>
                        <input id='supplier' name='supplier'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>

                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Serial</span>
                        <input id='Serial' name='Serial'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                </div>
                    <div class="mt-2 group flex items-center">
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Ubicacion
                        </span>
                        <select id='location_id' name='location_id'
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-multiselect focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                            required>
                            <option value=''>--Seleccionar---</option>
                            @foreach ($locations as $location)
                                <option value={{ $location->id }}>{{ $location->code }}//{{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <button
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="ml-4">Guardar</span>
                    </button>

                </div>


        </form>

    </div>
    <div class="container grid px-6 mx-auto">
        <form method="POST" action="{{ route('travel.store') }}">
            <div class="text-sm">
                @csrf
                @method('POST')
                <span class="text-gray-700 dark:text-gray-400">
                    Movimiento
                </span>
                <div >
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400"> QR </span>
                        <input id='QR' name='QR'
                            class="block mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                </div>
                <div class="mt-2 group flex items-center">
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Numero de parte</span>
                        <input id='Part' name='Part'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>


                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Cantidad</span>
                        <input id='Cantidad' name='Cantidad'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Supplier</span>
                        <input id='supplier' name='supplier'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>

                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Serial</span>
                        <input id='Serial' name='Serial'
                            class="block w-80 mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            required />
                    </label>
                </div>
                <div class="mt-2 group flex items-center">
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Locacion de salida
                        </span>
                        <select id='location_id_out' name='location_id_out'
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-multiselect focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                            required>
                            <option value=''>--Seleccionar---</option>
                            @foreach ($locations as $location)
                                <option value={{ $location->id }}>{{ $location->code }}//{{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block text-sm m-3">
                        <span class="text-gray-700 dark:text-gray-400">Locacion de entrada
                        </span>
                        <select id='location_id_in' name='location_id_in'
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-multiselect focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                            required>
                            <option value=''>--Seleccionar---</option>
                            @foreach ($locations as $location)
                                <option value={{ $location->id }}>{{ $location->code }}//{{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <button
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="ml-4">Guardar </span>
                    </button>

                </div>
            </div>

        </form>

    </div>



</x-app-layout>
