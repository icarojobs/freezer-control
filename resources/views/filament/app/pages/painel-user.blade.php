<x-filament-panels::page>
    {{-- Breadcrumb --}}
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


    <section>
        <div class="mx-auto max-w-7xl px-5 py-16 md:px-10 md:py-24 lg:py-32">
            <div class="grid justify-items-start gap-8 sm:gap-20 lg:grid-cols-2 lg:items-center">
                <!-- CTA Content -->
                <div class="flex flex-col items-start">
                    <h2 class="mb-4 text-3xl font-bold md:text-5xl">Under Maintenance</h2>
                    <p class="mb-6 max-w-[480px] text-[#647084] md:mb-10 lg:mb-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis, lectus</p>
                    <a href="#" class="flex flex-row items-center bg-[#276ef1] px-8 py-4 font-semibold text-white transition [box-shadow:rgb(171,_196,245)-8px_8px] hover:[box-shadow:rgb(171,_196,_245)_0px_0px]">
                        <p class="mr-6 font-bold">Get Started {{auth()->user()->name}}</p>
                        <svg fill="currentColor" class="h-4 w-4 flex-none" viewBox="0 0 20 21" xmlns="http://www.w3.org/2000/svg">
                            <title>Arrow Right</title>
                            <h3>Minhas ordens criadas: {{auth()->user()->customer->orders->count()}}</h3>
                            <p>{{$this->form}}</p>
                            <polygon points="16.172 9 10.101 2.929 11.515 1.515 20 10 19.293 10.707 11.515 18.485 10.101 17.071 16.172 11 0 11 0 9"></polygon>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="mx-auto w-full max-w-7xl px-5 py-16 md:px-10 md:py-24 lg:py-32">
            <!-- Title -->
            <h2 class="text-center text-3xl font-bold md:text-5xl">Portfolio</h2>
            <p class="msm:text-base mb-8 mt-4 text-center text-sm text-[#636262] md:mb-12 lg:mb-16">Lorem ipsum dolor sit amet elit ut aliquam</p>
            <!-- Content -->
            <div class="grid justify-items-center gap-4 sm:grid-cols-2 md:grid-cols-3">
                <!-- Item -->
                <a href="#" class="flex flex-col rounded-md p-4 lg:p-2">
                    <img src="https://images.unsplash.com/photo-1649261191624-ca9f79ca3fc6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NDd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                    <p class="mb-1 text-center font-bold">Project Name</p>
                    <p class="text-center text-sm mt-1 text-lg font-medium text-gray-900">$35</p>
                </a>
                <a href="#" class="flex flex-col rounded-md p-4 lg:p-2">
                    <img src="https://images.unsplash.com/photo-1649261191624-ca9f79ca3fc6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NDd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                    <p class="mb-1 text-center font-bold">Project Name</p>
                    <p class="text-center text-sm mt-1 text-lg font-medium text-gray-900">$35</p>
                </a>
                <a href="#" class="flex flex-col rounded-md p-4 lg:p-2">
                    <img src="https://images.unsplash.com/photo-1649261191624-ca9f79ca3fc6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NDd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                    <p class="mb-1 text-center font-bold">Project Name</p>
                    <p class="text-center text-sm mt-1 text-lg font-medium text-gray-900">$35</p>
                </a>
                <a href="#" class="flex flex-col rounded-md p-4 lg:p-2">
                    <img src="https://images.unsplash.com/photo-1649261191624-ca9f79ca3fc6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NDd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                    <p class="mb-1 text-center font-bold">Project Name</p>
                    <p class="text-center text-sm mt-1 text-lg font-medium text-gray-900">$35</p>
                </a>
                <a href="#" class="flex flex-col rounded-md p-4 lg:p-2">
                    <img src="https://images.unsplash.com/photo-1649261191624-ca9f79ca3fc6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NDd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                    <p class="mb-1 text-center font-bold">Project Name</p>
                    <p class="text-center text-sm mt-1 text-lg font-medium text-gray-900">$35</p>
                </a>
                <a href="#" class="flex flex-col rounded-md p-4 lg:p-2">
                    <img src="https://images.unsplash.com/photo-1649261191624-ca9f79ca3fc6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NDd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                    <p class="mb-1 text-center font-bold">Project Name</p>
                    <p class="text-center text-sm mt-1 text-lg font-medium text-gray-900">$35</p>
                </a>

            </div>
        </div>
    </section>

    <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
        <x-filament::button wire:click="submit" type="submit" form="submit" outlined>
            Atualizar dados
        </x-filament::button>
    </div>

</x-filament-panels::page>
