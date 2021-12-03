@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transportadoras</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Transportadoras</li>
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
                            <h3 class="card-title">Transportadoras</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novaTransportadora"><i class="fas fa-plus"></i> Nova Transportadora</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nome da Transportadora</th>
                                            <th>Razao Social</th>
                                            <th>Nome Fantasia</th>
                                            <th>CNPJ/CPF</th>
                                            <th>Serviços</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($shipping_companies as $shipping_company)
                                            <tr class="tr-id-{{$shipping_company->id}}">
                                                <td>{{$shipping_company->id}}</td>
                                                <td>{{$shipping_company->carrier_name}}</td>
                                                <td>{{$shipping_company->corporate_name}}</td>
                                                <td>{{$shipping_company->fantasy_name}}</td>
                                                <td>{{$shipping_company->cnpj_cpf}}</td>
                                                <td><a href="{{url('admin/transportes', $shipping_company->id)}}" class="btn btn-info btn-sm">({{$shipping_company->transportValues->count()}}) <i class="fas fa-eye"></i> Visualizar</a></td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarTrasnportadora" data-dados="{{json_encode($shipping_company)}}"><i class="fas fa-edit"></i> Alterar</a>
                    
                                                        <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirTrasnportadora" data-dados="{{json_encode($shipping_company)}}"><i class="fas fa-trash"></i> Apagar</a>
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

    <div class="modal fade" id="novaTransportadora">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovaTransportadora">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Nova Transportadora</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="carrier_name">Nome da Transportadora</label>
                                <input type="text" name="carrier_name" class="form-control" placeholder="Nome da Transportadora">
                            </div>
                            <div class="form-group col-12">
                                <label for="corporate_name">Razao Social</label>
                                <input type="text" name="corporate_name" class="form-control" placeholder="Razao Social">
                            </div>
                            <div class="form-group col-12">
                                <label for="fantasy_name">Nome Fantasia</label>
                                <input type="text" name="fantasy_name" class="form-control" placeholder="Nome Fantasia">
                            </div>
                            <div class="form-group col-12">
                                <label for="cnpj_cpf">CNPJ/CPF</label>
                                <input type="text" name="cnpj_cpf" class="form-control" placeholder="CNPJ/CPF">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovaTransportadora" data-save_route="{{route('novaTransportadora')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarTrasnportadora">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarTrasnportadora">
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
                            <div class="form-group col-12">
                                <label for="carrier_name">Nome da Transportadora</label>
                                <input type="text" name="carrier_name" class="form-control" placeholder="Nome da Transportadora">
                            </div>
                            <div class="form-group col-12">
                                <label for="corporate_name">Razao Social</label>
                                <input type="text" name="corporate_name" class="form-control" placeholder="Razao Social">
                            </div>
                            <div class="form-group col-12">
                                <label for="fantasy_name">Nome Fantasia</label>
                                <input type="text" name="fantasy_name" class="form-control" placeholder="Nome Fantasia">
                            </div>
                            <div class="form-group col-12">
                                <label for="cnpj_cpf">CNPJ/CPF</label>
                                <input type="text" name="cnpj_cpf" class="form-control" placeholder="CNPJ/CPF">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarTrasnportadora" data-save_route="{{route('atualizarTransportadora')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirTrasnportadora">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postExcluirTrasnportadora">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Transportadora <span class="_carrier_name"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Tem certeza que gostaria de apagar essa Transportadora?</h5>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-salvar" data-trash="S" data-save_target="#postExcluirTrasnportadora" data-save_route="{{route('excluirTransportadora')}}"><i class="fas fa-trash"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection