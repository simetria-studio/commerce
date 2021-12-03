@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Serviços da Transportadora</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{asset('/admin/transportes')}}">Transportadoras</a></li>
                        <li class="breadcrumb-item">Serviços da Transportadora</li>
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
                            <h3 class="card-title">Transportadora <strong>{{$carrier_name}}</strong></h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novoServico"><i class="fas fa-plus"></i> Novo Serviço</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Valor da Entrega</th>
                                            <th>Estado de Entrega</th>
                                            <th>Cidade de Entrega</th>
                                            <th>Tempo de Entrega</th>
                                            <th>Peso MAX</th>
                                            <th>Altura MAX</th>
                                            <th>Largura MAX</th>
                                            <th>Comprimento MAX</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transport_values as $transport_value)
                                            <tr class="tr-id-{{$transport_value->id}}">
                                                <td>{{$transport_value->id}}</td>
                                                <td>R$ {{number_format($transport_value->price,2,',','.')}}</td>
                                                <td>{{$transport_value->state}}</td>
                                                <td>{{$transport_value->city}}</td>
                                                <td>{{$transport_value->time}} Dias</td>
                                                <td>{{$transport_value->weight}} kg</td>
                                                <td>{{$transport_value->height}} cm</td>
                                                <td>{{$transport_value->width}} cm</td>
                                                <td>{{$transport_value->length}} cm</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarServico" data-dados="{{json_encode($transport_value)}}"><i class="fas fa-edit"></i> Alterar</a>
                    
                                                        <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirServico" data-dados="{{json_encode($transport_value)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novoServico">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovoServico">
                    @csrf
                    <input type="hidden" name="shipping_company_id" value="{{$id}}">
                    <div class="modal-header">
                        <h4 class="modal-title">Novo Seriço da Transportadora</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12 col-md-6">
                                <label for="state">Estado</label>
                                <select name="state" class="form-control select2 state">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="city">Cidade de Entrega</label>
                                <select name="city" class="form-control select2 city entrega">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="price">Preço da Entrega</label>
                                <input type="text" name="price" class="form-control" placeholder="Preço">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="time">Tempo de Entrega</label>
                                <input type="text" name="time" class="form-control" placeholder="Dias">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="weight">Peso max. kg</label>
                                <input type="text" name="weight" class="form-control" placeholder="KG">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="height">Altura max. cm</label>
                                <input type="text" name="height" class="form-control" placeholder="Centimetro">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="width">Largra max. cm</label>
                                <input type="text" name="width" class="form-control" placeholder="Centimetro">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="length">Comprimento max. cm</label>
                                <input type="text" name="length" class="form-control" placeholder="Centimetro">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovoServico" data-save_route="{{route('novoServico')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarServico">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarServico">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Atualizar Categoria</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12 col-md-6">
                                <label for="state">Estado</label>
                                <select name="state" class="form-control select2 state">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="city">Cidade de Entrega</label>
                                <select name="city" class="form-control select2 city entrega">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="price">Preço da Entrega</label>
                                <input type="text" name="price" class="form-control" placeholder="Preço">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="time">Tempo de Entrega</label>
                                <input type="text" name="time" class="form-control" placeholder="Dias">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="weight">Peso max. kg</label>
                                <input type="text" name="weight" class="form-control" placeholder="KG">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="height">Altura max. cm</label>
                                <input type="text" name="height" class="form-control" placeholder="Centimetro">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="width">Largra max. cm</label>
                                <input type="text" name="width" class="form-control" placeholder="Centimetro">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="length">Comprimento max. cm</label>
                                <input type="text" name="length" class="form-control" placeholder="Centimetro">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarServico" data-save_route="{{route('atualizarServico')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirServico">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postExcluirServico">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Excluir Serviço da Transportadora {{$carrier_name}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Tem certeza que gostaria de apagar esse Seriço da Transportadora?</h5>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-salvar" data-trash="S" data-save_target="#postExcluirServico" data-save_route="{{route('excluirServico')}}"><i class="fas fa-trash"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection