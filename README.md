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
Instanciar o conector (adapter) do gateway de pagamento que deseja
```bash
$adapter = new App\Services\Gateway\Connectors\AsaasConnector();
```

Instanciar o cliente Gateway usando a do adapter cirado préviamente
```bash
$gateway = new App\Services\Gateway\Gateway($adapter);
```


Clientes:
```php
// Insere um novo cliente
$data = [
    'name' => 'Fabiano Fernandes',
    'cpfCnpj' => '21115873709',
    'email' => 'fabianofernandes@test.com.br',
    'mobilePhone' => '16992222222',
];

$customer = $gateway->customer()->create($data);

// Atualizar um cliente
$newData = [
    'name' => 'Tio Jobs',
    'cpfCnpj' => '21115873709',
    'email' => 'tiojobs@test.com.br',
    'mobilePhone' => '16992222222',
];
$customer = $gateway->customer()->update('cus_000005891625', $newData);

// Retorna a listagem de clientes
$customers = $gateway->customer()->list();

// Retorna clientes utilizando filtros
$customers = $gateway->customer()->list(['cpfCnpj' => '21115873709']);
    
// Remove um cliente
$customer = $gateway->customer()->delete('cus_000005891625');
```

Cobrança:
```php
// Criar uma nova cobrança
 $data = [
        "billingType" => "BOLETO", // "CREDIT_CARD", "PIX", "BOLETO"
        "discount" => [
            "value" => 10,
            "dueDateLimitDays" => 0
        ],
        "interest" => [
            "value" => 2
        ],
        "fine" => [
            "value" => 1
        ],
        "customer" => "cus_000005891625",
        "dueDate" => "2024-02-29",
        "value" => 100,
        "description" => "Pedido 056984",
        "daysAfterDueDateToCancellationRegistration" => 1,
        "externalReference" => "056984",
        "postalService" => false
    ];
$payment = $gateway->payment()->create($data);

// Atualiza uma cobrança
$payment = $gateway->payment()->update('cus_000005891625', $newData);

// Retorna a listagem de cobranças
$payments = $gateway->payment()->list();

// Retorna cobranças utilizando filtros
$payments = $gateway->payment()->list(['customer' => 'cus_000005891625', 'status' => 'RECEIVED']);

// Remove uma cobrança
$customer = $gateway->payment()->delete('cus_000005891625');
```

### PARTE 02
 - Criar APIs para aplicativo Mobile (sanctum).

### PARTE 03
 - Criar Aplicativo Mobile (Administrador + Consumidor)

https://youtu.be/-Jf9hgt-Fj4?list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&t=509

---
### CONSTRIBUIÇÕES
 - Checkout by @fabianosfbr
