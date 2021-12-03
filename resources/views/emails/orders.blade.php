@component('mail::message')
{{$msg1}}

<strong>Dados do Comprador:</strong>

    Nome Comprador: {{$orders->user_name}}
    Email Comprador: {{$orders->user_email}}
    Cnpj/Cpf Comprador: {{$orders->user_cnpj_cpf}}

<strong>Dados do Pedido: {{$order_number}}</strong>

    Sub Total do Pedido: {{number_format($orders->product_value,2,',','.')}}
    Custo do Frete: {{number_format($orders->cost_freight,2,',','.')}}
    Valor Total do Pedido: {{number_format($orders->total_value,2,',','.')}}

<strong>Produtos Comprados: {{$order_number}}</strong>

@foreach ($orders->orderProducts as $orderProducts)
    Codigo do Produto: {{$orderProducts->product_code}}
    Nome do Produto: {{$orderProducts->product_name}}
    Preço do Produto: {{number_format($orderProducts->product_price,2,',','.')}}
    Quantidade: {{$orderProducts->quantity}}
@if ($orderProducts->has_preparation == 'S')
    Tempo de Preparo: {{$orderProducts->preparation_time}} Dias
@endif
@if ($orderProducts->product_sales_unit == 'M²')
    Largura: {{$orderProducts->project_width}}
    Altura: {{$orderProducts->project_height}}
    Comprimento: {{($orderProducts->product_length * count($orderProducts->project))}}
    Metro Quadrado: {{$orderProducts->project_meters}}
@endif
@if ($orderProducts->product_sales_unit == 'M')
    Largura: {{$orderProducts->project_meters}}
    Altura: {{$orderProducts->product_height}}
    Comprimento: {{$orderProducts->product_length}}
@endif
@if ($orderProducts->product_sales_unit == 'UN')
    Largura: {{$orderProducts->product_height}}
    Altura: {{$orderProducts->product_height}}
    Comprimento: {{$orderProducts->product_length}}
@endif
@if ($orderProducts->product_sales_unit == 'M²')
<strong>Projeto Calculado:</strong>

@foreach ($orderProducts->project as $projectValue)
    Largura: {{explode('|', $projectValue)[0]}}
    Altura: {{explode('|', $projectValue)[1]}}
    Metro Quadrado: {{explode('|', $projectValue)[2]}}
@endforeach
@endif
<strong>Atributos do Produto:</strong>

@foreach ($orderProducts->attributes as $attributesValue)
    Attributo: {{explode('|', $attributesValue)[1]}}
@if (explode('|', $attributesValue)[2])
    Valor: {{number_format(explode('|', $attributesValue)[2],2,',','.')}}
@endif
@endforeach

<strong>Observação</strong>

    {{$orderProducts->note}}

##---------------------------------------------##
@endforeach

<strong>Dados de Entrega:</strong>

@foreach ($orders->shippingCustomer as $shippingCustomer)
    CEP: {{$shippingCustomer->post_code}}
    Endereço: {{$shippingCustomer->address}} - Nª: {{$shippingCustomer->number}}
    Bairro: {{$shippingCustomer->address2}}
    Cidade: {{$shippingCustomer->city}} - Estado: {{$shippingCustomer->state}}
    Complemento: {{$shippingCustomer->complement}}
    Telefone 1: {{$shippingCustomer->phone1}} - Telefone 2: {{$shippingCustomer->phone2}}
@endforeach

@endcomponent