<div class="flex justify-center">

        @dump($paymentId)


        <x-filament::button wire:click="chargePix" color="success">
            Gerar QR Code
        </x-filament::button>


{{--    <div>--}}
{{--        <img src="data:image/png;base64,{{$qrCodeData['encodedImage']}}" />--}}

{{--        <p>Ou, utilize o Copia/Cola:</p>--}}
{{--        <span>{{ $qrCodeData['payload'] }}</span>--}}
{{--    </div>--}}
</div>
