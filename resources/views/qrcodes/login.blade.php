<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acesse Agora</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-300 to-blue-500">
    <div class="w-1/3 flex flex-col mx-auto p-8 gap-4 bg-blue-100 justify-center items-center mt-16 rounded-xl shadow-lg shadow-gray-500/40">
        <h1 class="text-3xl">{{ config('app.name') }}</h1>
        <p class="-mt-4">Revolucionando a forma como você compra bebidas</p>
        {{ $qrcode }}
        <a href="{{ route('filament.app.auth.login') }}" class="text-2xl text-blue-600">Acesse Agora</a>
    </div>
</body>
</html>
