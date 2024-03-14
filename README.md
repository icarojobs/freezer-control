<p align="center">
	<a href="#"  target="_blank" title="Sistema de gestão de freezers">
		<img src="/public/images/brands/logo-v-1024.png" alt="Sistema de gestão de freezers" width="340px">
	</a>
</p>

<br>

<p align="center">:rocket: Revolucionando a forma como você compra bebidas :sparkles: <a href="https://github.com/icarojobs/freezer-control">Freezer Control</a></p>

<p align="center">
	<img src="https://img.shields.io/badge/version project-1.0-brightgreen" alt="version project">
    <img src="https://img.shields.io/badge/Php-8.3.3-informational" alt="stack php">
    <img src="https://img.shields.io/badge/Laravel-10.46-informational&color=brightgreen" alt="stack laravel">
    <img src="https://img.shields.io/badge/Filament-3.2-informational" alt="stack Filament">
    <img src="https://img.shields.io/badge/TailwindCss-3.1-informational" alt="stack Tailwind">
	<a href="https://opensource.org/licenses/GPL-3.0">
		<img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="GPLv3 License">
	</a>
</p>

<h4 align="center"> 
	🚧  Projeto 🚀 em construção...  🚧
</h4>

<br>


---
### :package: dependências do projeto
 - Docker + docker-compose
 - curl
 - Make 4.x

---
### :books: Configurando o projeto em um novo ambiente
Simplesmente execute o comando `make` no seu terminal:
```bash
make
```

Agora, basta acessar a URL `http://laravel.test`

> *OBS.:* Verifique se no seu arquivo `hosts` existe o alias para `127.0.0.1 laravel.test`.

---
### :sparkles: Link do projeto anterior
:wrench: [Versão antiga](https://www.youtube.com/watch?v=-Jf9hgt-Fj4&list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&index=2)

---
### :recycle: Pendências
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

### :bricks: Como integrar com gateway de pagamento

Instanciar o conector (adapter) do gateway de pagamento que deseja
```bash
$adapter = new App\Services\Gateway\Connectors\AsaasConnector();
```

Instanciar o cliente Gateway utilizando o adapter criado préviamente
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

### :soon: Parte 02
 - Criar APIs para aplicativo Mobile (sanctum).

### :soon: Parte 03
 - Criar Aplicativo Mobile (Administrador + Consumidor)

https://youtu.be/-Jf9hgt-Fj4?list=PLbjKo3xK3gjcOz9Ocn3H6aTtTRBypCAaA&t=509

---
### :sparkles: Contribuidores
<table>
  <tr>
    <td align="center"><a href="https://github.com/icarojobs">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/16943171?v" width="100px;" alt=""/>
    <br /><sub><b>Icaro William</b></sub></a></td>
    <td align="center"><a href="https://github.com/fabianosfbr">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/4691302?v=4" width="100px;" alt=""/>
    <br /><sub><b>Fabiano Fernandes</b></sub></a></td>
    <td align="center"><a href="https://github.com/RafaelBlum">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/41844692?v=4" width="100px;" alt=""/>
    <br /><sub><b>Rafael Blum</b></sub></a></td>   
    <td align="center"><a href="https://github.com/wesleysouza-dev">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/52400075?v=4" width="100px;" alt=""/>
    <br /><sub><b>Wesley</b></sub></a></td>   
    <td align="center"><a href="https://github.com/Deathpk">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/40901963?v=4" width="100px;" alt=""/>
    <br /><sub><b>Michel Versiani</b></sub></a></td>
    <td align="center"><a href="https://github.com/TiagoLemosNeitzke">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/75308967?v=4" width="100px;" alt=""/>
    <br /><sub><b>Tiago Lemos Neitzke</b></sub></a></td>
  </tr>
</table>

> Ninguém é tão sábio que não tenha algo para aprender e nem tão tolo que não tenha algo pra ensinar. `Blaise Pascal`.
