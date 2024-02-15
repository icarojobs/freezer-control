@if(Route::current()->getName() === 'filament.admin.auth.login')
    <img src="{{asset('images/brands/logo-h-white-720.png')}}"
         alt="{{config('app.name')}}"
         title="{{config('app.name')}}"
         width="310"
         class="mb-2"
         style="filter: drop-shadow(rgb(10,20,10) 1px 2px 1px)"
    >
@else
    <img src="{{asset('images/brands/logo-v-white-720.png')}}"
         alt="{{config('app.name')}}"
         title="{{config('app.name')}}"
         width="185"
         class="mb-2"
         style="filter: drop-shadow(rgb(10,20,10) 1px 2px 1px)"
    >
 @endif

{{-- Precisa somente de uma condição para trocar as logos quando sistema esta dark e light e retirar o style --}}
