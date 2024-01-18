<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Seu acesso ao {{ config('app.name') }}</title>
</head>
<body>
    <div>
        <h1>Dados de Acesso</h1>
        <p><strong>Link:</strong> {{ route("filament.app.auth.login") }}</p>
        <p><strong>Usuário:</strong> {{ $customer->email }}</p>
        <p><strong>Senha:</strong> {{ $secret }}</p>
    </div>
</body>
</html>
