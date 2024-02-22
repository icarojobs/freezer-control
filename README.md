# Freezer Control 1.0
Sistema de gestão de freezers

## *** TEM PR PARA ACEITAR!!! ***

---
### DEPENDENCIAS DO PROJETO
 - Docker + docker-compose
 - curl
 - Make 4.x

---
### CONFIGURANDO PROJETO EM UM NOVO AMBIENTE
Simplesmente execute o comando `make` no seu terminal:
```bash
make
```

Agora, basta acessar a URL `http://laravel.test`

*OBS.:* Verifique se no seu arquivo `hosts` existe o alias para `127.0.0.1 laravel.test`.

---
### LINK DO ANTEPROJETO
[https://www.youtube.com/watch?v=-Jf9hgt-Fj4&list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&index=2](https://www.youtube.com/watch?v=-Jf9hgt-Fj4&list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&index=2)

---
### PENDENCIAS
 - Ao criar um novo customer, verificar se ele não está cadastrado no asaas. Se não tiver, cadastrar e adicionar o "customerId" no nosso banco.
   - Criar a coluna "customerId" na tabela customers
   - Sincronizar o cliente com o ASAAS no observer que já temos (em todos os métodos do CRUD)
 - Criar integração com o recurso "Cobrança" do ASAAS (Charge).
 - Finalizar a tela de Venda do nosso sistema (Checkout).
 - Dashboards, Relatórios, Gráficos, ETECETARASS!!!!
 - Adicionar validação de maioridade na tela de vendas (ao adicionar novo cliente).
 - Criar painel APP com a tela de "Venda" (que pode chamar "Comprar")
 - Criar um globalScope/policies para que um cliente não tenha acesso a informações de outro.
 - Criar Dashboards no painel APP
 - Testes finais
 - Correr pro abraço!

### INTEGRAÇÃO COM GATEWAY DE PAGAMENTOS
Listar Clientes:
```php
$customers = (new App\Services\AsaasPhp\Customer\CustomerList)->handle();

dd($customers);
```

Criar Novo Cliente:
```php
$data = [
    'name' => 'Rick Tortorelli',
    'cpfCnpj' => '21115873709',
    'email' => 'rick@test.com.br',
    'mobilePhone' => '16992222222',
];

$customer = (new App\Services\AsaasPhp\Customer\CustomerCreate(data: $data))->handle();

dd($customer);
```

### PARTE 02
 - Criar APIs para aplicativo Mobile (sanctum).

### PARTE 03
 - Criar Aplicativo Mobile (Administrador + Consumidor)

https://youtu.be/-Jf9hgt-Fj4?list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&t=509

---
### CONSTRIBUIÇÕES
 - Checkout by @fabianosfbr
