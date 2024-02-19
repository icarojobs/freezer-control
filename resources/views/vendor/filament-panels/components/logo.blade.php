@if(Route::current()->getName() === 'filament.admin.auth.login' || Route::current()->getName() === 'filament.app.auth.register' || Route::current()->getName() === 'filament.app.auth.login')
    <img src="{{asset('images/brands/logo-h-white-720.png')}}"
         alt="{{config('app.name')}}"
         title="{{config('app.name')}}"
         width="310"
         class="mb-2"
    >
@else
    <img src="{{asset('images/brands/logo-v-white-720.png')}}"
         alt="{{config('app.name')}}"
         title="{{config('app.name')}}"
         width="185"
         class="mb-2"
    >
@endif

{{-- Precisa somente de uma condição para trocar as logos quando sistema esta dark e light e retirar o style --}}
