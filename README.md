# Freezer Control 1.0
Sistema de gestão de freezers

---
### CONFIGURANDO PROJETO EM UM NOVO AMBIENTE
1. Copie o seu `.env.example` para `.env`
2. Instale as dependências do composer com o seguinte comando:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
3. Suba o seu projeto com o comando `sail up -d`
4. Execute o comando `sail art key:generate` para criar sua nova chave de aplicação.
5. Acesse o browser em `http://laravel.test` para conferir o resultado.
*OBS.:* Verifique se no seu arquivo `hosts` existe o alias para `127.0.0.1 laravel.test`.

---
### LINK DO ANTEPROJETO
[https://www.youtube.com/watch?v=-Jf9hgt-Fj4&list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&index=2](https://www.youtube.com/watch?v=-Jf9hgt-Fj4&list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&index=2)

---
### PENDENCIAS
 - Instalar lib de geração de QRCODE
A ideia é ter uma URL, por exemplo: `http://laravel.test/qr-code` que vai exibir um QRCode para redirecionamento da
tela de login. ( com base no config('app.url') )

https://youtu.be/-Jf9hgt-Fj4?list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&t=473
