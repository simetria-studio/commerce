@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pedido Finalizados</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Pedidos FInalizados</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        {{-- Header do Card --}}
                        <div class="card-header">
                            <h3 class="card-title">Pedidos Finalizados</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Corpo do Card --}}
                        <div class="card-body pad table-responsive">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nª Pedido</th>
                                            <th>Nome do Comprador</th>
                                            <th>Email do Comprador</th>
                                            <th>CNPJ/CPF do Comprador</th>
                                            <th>Data de Nascimento do Comprador</th>
                                            <th>Sub.Total</th>
                                            <th>Valor do Frete</th>
                                            <th>Valor Total</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr class="tr-id-{{$order->id}}">
                                                <td>{{$order->id}}</td>
                                                <td>{{$order->order_number}}</td>
                                                <td>{{$order->user_name}}</td>
                                                <td>{{$order->user_email}}</td>
                                                <td>{{$order->user_cnpj_cpf}}</td>
                                                <td>{{date('d/m/Y', strtotime(str_replace('-','/', $order->birth_date)))}}</td>
                                                <td>{{number_format($order->product_value, 2, ',', '.')}}</td>
                                                <td>{{number_format($order->cost_freight, 2, ',', '.')}}</td>
                                                <td>{{number_format($order->total_value, 2, ',', '.')}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-sm btn-visualizar" data-toggle="modal" data-target="#visualizarPedido" data-dados="{{json_encode($order)}}"><i class="fas fa-eye"></i> Dados</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="container mt-2">{{$orders->links()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="visualizarPedido">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Dados do Pedido</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row my-2">
                        <div class="col-12"><h4>Dados do Comprador</h4></div>
                        <div class="col-12 col-md-4"><b>Nome do Comprador:</b> <span class="_user_name"></span></div>
                        <div class="col-12 col-md-4"><b>Email do Comprador:</b> <span class="_user_email"></span></div>
                        <div class="col-12 col-md-4"><b>CNPJ/CPF do Comprador:</b> <span class="_user_cnpj_cpf"></span></div>
                        <div class="col-12 col-md-4"><b>Data de Nascimento do Comprador:</b> <span class="_birth_date"></span></div>
                        <div class="col-12 col-md-4"><b>Sub. Total:</b> <span class="_product_value"></span></div>
                        <div class="col-12 col-md-4"><b>Valor do Frete:</b> <span class="_cost_freight"></span></div>
                        <div class="col-12 col-md-4"><b>Valor Total:</b> <span class="_total_value"></span></div>
                    </div>
                    <div class="row my-2">
                        <div class="col-12"><h4>Produtos Comprados</h4></div>
                        <div class="col-12 produtos_pedido"></div>
                    </div>
                    <div class="row my-2">
                        <div class="col-12"><h4>Endereço de Entrega</h4></div>
                        <div class="col-12 entrega_pedido"></div>
                    </div>
                    <div class="row my-2">
                        <div class="col-12"><h4>Pagamentos</h4></div>
                        <div class="col-12 pagamento_pedido"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection