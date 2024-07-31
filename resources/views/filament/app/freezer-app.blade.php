<x-filament-panels::page>
    <div class="mb-4 col-span-full xl:mb-2">
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="#"
                       class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                        </svg>
                        Minha área
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <a href="#"
                           class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">{{auth()->user()->name}}</a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{--{{auth()->user()->name}} {{auth()->user()->customer->orders->count()}} {{$this->form}}--}}

    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start mb-5">
                <h2 class="mb-4 text-3xl font-bold md:text-5xl">Cardápio de bebidas</h2>
            </div>

{{--            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8 mx-auto max-w-screen-xl px-4 2xl:px-0">--}}
{{--                @foreach(\App\Models\Product::where('in_stock', '>', 0)->get() as $product)--}}
{{--                    <a href="#" class="relative bg-cover group rounded-xl bg-center overflow-hidden mx-auto sm:mr-0 xl:mx-auto cursor-pointer">--}}
{{--                        <img src="{{Storage::url($product->image)}}" alt="image" class="rounded-xl h-13 w-13">--}}
{{--                        <div class="flex flex-col items-center justify-between mb-3">--}}
{{--                            <h6 class="font-semibold text-base leading-7 text-black mt-2">{{$product->name}}</h6>--}}
{{--                            <h6 class="font-semibold text-base leading-7 text-indigo-600 text-right">R$ {{$product->sale_price}}</h6>--}}
{{--                            <div class="inline-flex items-center mt-2">--}}
{{--                                <x-filament::button wire:click="openNewUserModal">--}}
{{--                                    Adicionar--}}
{{--                                </x-filament::button>--}}
{{--                            </div>--}}
{{--                            <p class="text-xs leading-5 text-gray-500 mt-1">Estoque {{$product->in_stock}}</p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                @endforeach--}}
{{--            </div>--}}


{{--            <div class="mt-40 border-t-gray-200 pt-16 flex items-center justify-center gap-x-6 lg:justify-start">--}}
{{--                <x-filament::button wire:click="submit" type="submit" form="submit" outlined>--}}
{{--                    Finalizar pedido--}}
{{--           </x-filament::button>--}}

                <form wire:submit="create">
                    {{$this->table}}
                    {{ $this->form }}

                    <button type="submit" type="button" style="background-color: #1e40af; padding: 6px; border-radius: 5px;">
                        Finalizar pedido
                    </button>
                </form>
            </div>
        </div>
    </section>

</x-filament-panels::page>
