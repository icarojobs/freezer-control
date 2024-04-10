<!DOCTYPE html>
<html lang="pt-br">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! 403 | {{env('app_name')}}</title>
    <meta name="description" content="Sistema de gestão de freezers - Revolucionando a forma como você compra bebidas">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="leading-normal tracking-normal text-gray-900" style="font-family: 'Source Sans Pro', sans-serif;">
<div id="bg-cover">
    <div class="flex items-center justify-center py-48">
        <div class="flex flex-col">
            <div class="flex flex-col items-center relative">
                <a class="flex items-center text-indigo-400 no-underline hover:no-underline font-bold text-2xl lg:text-2xl logo opacity-50"  href="{{route('frontend.home')}}">
                    <img src="{{asset('images/brands/icon-340.png')}}" class="mr-3 h-3 sm:h-9" alt="{{env('APP_NAME')}}"/> {{env('APP_NAME')}}
                </a>

                <div class="mb-2 text-2xl font-bold text-center text-gray-500 md:text-3xl" style="position: absolute;">
                    <img class="cone" src="{{asset('images/errors/cone.png')}}" alt="{{env('APP_NAME')}}"/>
                </div>

                <div class="absolute bottom-0 left-0" style="position: relative;">
                    <img class="placa" src="{{asset('images/errors/placa.png')}}" alt="{{env('APP_NAME')}}"/>
                </div>



                <div class="mb-2 text-2xl font-bold text-center text-gray-500 md:text-3xl">
                    <span style="color: #DD7B7D;">Oops!</span> Sem permissão de acesso
                </div>

                <div class="mb-8 text-center text-gray-500 md:text-lg">
                    Você não tem permissão para acessar esta página.
                </div>

                <a href="{{route('filament.app.auth.login')}}" class="btn-403 px-6 py-2 rounded-lg text-sm font-semibold text-blue-800 bg-blue-200">
                    Voltar à página inicial
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
