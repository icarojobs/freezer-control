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
            <div class="flex flex-col ">
                <div class="flex flex-col items-center">
                    {{--<p>ID do Pagamento: {{ session('session_' . \Auth::id())['payment_id'] }}</p>
                    <p>Status: {{ session('session_' . \Auth::id())['payment_status'] }}</p>--}}
                    <img src="data:image/png;base64,{{ session('session_' . \Auth::id())['qrcode_image'] }}"
                         alt="Qr code para pagar com pix"
                         height="200"
                         width="200"
                    >
                </div>
                <div class="flex flex-col flex-grow max-w-sm items-center ">
                    <p>Ou, utilize o Copia/Cola:</p>
                    <p>{{ session('session_' . \Auth::id())['qrcode_link'] }}</p>
                </div>
            </div>

        @endif
    </div>
