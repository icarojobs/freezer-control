<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--LOGO ICON -->
    <link rel="icon" href="{{asset('images/brands/icon-340.png')}}">
    <title>Oops! 403 | {{env('app_name')}}</title>
    <meta name="description" content="Sistema de gestão de freezers - Revolucionando a forma como você compra bebidas">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

    <link href="{{asset('css/style-qr-code.css')}}" rel="stylesheet">
</head>
<body class="leading-normal tracking-normal text-gray-900" style="font-family: 'Source Sans Pro', sans-serif;">
    <div class="h-screen pb-14 relative bg-right bg-cover" style="background-image:url('{{asset('images/landing-page/bg.svg')}}');">
        <div class="flex items-center justify-center py-48">
            <div class="flex flex-col">
                <div class="flex flex-col items-center relative">
                    <a class="flex items-center text-indigo-400 no-underline hover:no-underline font-bold text-2xl lg:text-2xl logo opacity-50"  href="{{route('frontend.home')}}">
                        <img src="{{asset('images/brands/icon-340.png')}}" class="mr-3 h-3 sm:h-9" alt="{{env('APP_NAME')}}"/> {{env('APP_NAME')}}
                    </a>

                    <div class="mb-2 text-2xl font-bold text-center text-gray-500 md:text-3xl" style="position: absolute;">
                        <img src="{{asset('images/info_images/cone.png')}}" style="position: relative; top: 0; left: 0; z-index: 1;" alt="{{env('APP_NAME')}}"/>
                    </div>

                    <div class="absolute bottom-0 left-0" style="position: relative;">
                        <img class="placa" src="{{asset('images/info_images/placa.png')}}" style="position: relative; top: 0; left: 0; z-index: 2;" alt="{{env('APP_NAME')}}"/>
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

    <!--=======================  SCROLLREVEAL  =======================-->
    <script src="https://unpkg.com/scrollreveal"></script>
    <!--=======================  JS  =======================-->
    <script type="text/javascript" rel="script" src="{{asset('js/script-scrollreveal.js')}}"></script>
</body>
</html>
