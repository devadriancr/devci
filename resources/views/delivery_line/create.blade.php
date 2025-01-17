<x-app-layout title="Tables">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Salida de Material a Línea') }}
        </h2>

        <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('material-delivery.store-material') }}" method="POST">
                @csrf
                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        {{ __('Escanea o Ingresar Código') }}
                    </span>
                    <div class="relative text-gray-500 focus-within:text-purple-600">
                        <!-- Campo de entrada con estilos dinámicos -->
                        <input id="code" name="code" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input @if(session('status_type') == 'error') border-red-600 @elseif(session('status_type') == 'success') border-green-600 @endif" placeholder="{{ __('Ingrese el código') }}" value="{{ old('code') }}" />

                        <!-- Botón con estilos dinámicos -->
                        <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 @if(session('status_type') == 'error') bg-red-600 hover:bg-red-700 active:bg-red-600 focus:shadow-outline-red @elseif(session('status_type') == 'success') bg-green-600 hover:bg-green-700 active:bg-green-600 focus:shadow-outline-green @else bg-purple-600 hover:bg-purple-700 active:bg-purple-600 focus:shadow-outline-purple @endif border border-transparent rounded-r-md focus:outline-none">
                            {{ __('Guardar') }}
                        </button>

                    </div>
                    <!-- Mostrar notificación debajo del input -->
                    @if(session('status'))
                    <span class="text-xs @if(session('status_type') == 'error') text-red-600 dark:text-red-400 @elseif(session('status_type') == 'success') text-green-600 dark:text-green-400 @endif">
                        {{ session('status') }}
                    </span>
                    @endif
                </label>

            </form>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3"> {{ __('Serial') }} </th>
                            <th class="px-4 py-3"> {{ __('Número de Parte') }} </th>
                            <th class="px-4 py-3"> {{ __('Cantidad') }} </th>
                            <th class="px-4 py-3"> {{ __('Fecha de Salida') }} </th>
                            <th class="px-4 py-3"> {{ __('Acciones') }} </th>
                        </tr>
                    </thead>
                    <tbody class="uppercase bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($outputs as $output)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs">
                                {{ $output->supplier }}{{ $output->serial }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $output->item->item_number }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $output->item_quantity }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $output->created_at }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <button
                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                        aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button
                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                        aria-label="Delete">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $outputs->firstItem() }} - {{ $outputs->lastItem() }} de {{ $outputs->total()}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $outputs->withQueryString()->links()}}
                    </nav>
                </span>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {

            document.getElementById('code').focus();
        };
    </script>
</x-app-layout>
