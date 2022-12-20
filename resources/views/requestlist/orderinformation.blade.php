<x-app-layout title="Orden de viaje">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-4 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Orden de viaje
        </h2>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Numero de orden</th>
                            <th class="px-4 py-3">Usuario </th>
                            <th class="px-4 py-3">Fecha de creacion</th>
                            <th class="px-4 py-3">Numero de Viaje</th>
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($order as $inventory)
                            <tr class="text-gray-700 dark:text-gray-400">

                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory->id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory->user->name ?? '' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory->created_at }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $inventory->travel_id ?? '' }}
                                </td>
                                <td>
                                    @if( $inventory->travel_id==null)
                                    <form method="POST" action="{{ route('travel.new') }}">
                                        @csrf
                                        <input name="order_id" value={{ $inventory->id }} hidden>
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                            </svg>
                                        </button>
                                    </form>
                                    @else
                                    @if($inventory->finish==null)
                                    <form method="POST" action="{{ route('output.store')}}">
                                        @csrf
                                        <input name="travel_id" value={{ $inventory->travel_id }} hidden>
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('RequestList.export') }}">
                                        @csrf
                                        <input name="order_id" value={{ $inventory->id }} hidden>
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Mostrando {{ $order->firstItem() }} - {{ $order->lastItem() }} de {{ $order->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{ $order->withQueryString()->links() }}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
