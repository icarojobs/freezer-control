@if(session()->has('session_' . \Auth::id()))
    <div class="flex justify-center" wire:poll.3000ms="checkPayment">
@else
    <div class="flex justify-center">
@endif
        @if(!session()->has('session_' . \Auth::id()))
            <x-filament::button wire:click="chargePix" color="success">
                Gerar QR Code
            </x-filament::button>
        @else
            <div>
                <div class="flex flex-col">
                    <p>ID do Pagamento: {{ session('session_' . \Auth::id())['payment_id'] }}</p>
                    <p>Status: {{ session('session_' . \Auth::id())['payment_status'] }}</p>
                    <img src="data:image/png;base64,{{ session('session_' . \Auth::id())['qrcode_image'] }}" />

                    <p>Ou, utilize o Copia/Cola:</p>
                    <span>{{ session('session_' . \Auth::id())['qrcode_link'] }}</span>
                </div>
            </div>
        @endif
</div>
