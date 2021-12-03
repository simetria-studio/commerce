@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Endereços de Usuario</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Endereços</li>
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
                            <h3 class="card-title">Endereços de {{$user->name}}</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novoEndereco"><i class="fas fa-plus"></i> Novo Endereço</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>CEP</th>
                                            <th>Endereço-Numero</th>
                                            <th>Complemento</th>
                                            <th>Bairro</th>
                                            <th>Cidade</th>
                                            <th>Estado</th>
                                            <th>Telefones</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($addresses as $address)
                                            <tr class="tr-id-{{$address->id}}">
                                                <td>{{$address->id}}</td>
                                                <td>{{$address->post_code}}</td>
                                                <td>{{$address->address}} - {{$address->number}}</td>
                                                <td>{{$address->complement}}</td>
                                                <td>{{$address->address2}}</td>
                                                <td>{{$address->city}}</td>
                                                <td>{{$address->state}}</td>
                                                <td>{{$address->phone1}} // {{$address->phone2}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#editarEndereco" data-dados="{{json_encode($address)}}"><i class="fas fa-edit"></i> Editar</a>
                                                        <a href="#" class="btn btn-danger btn-sm btn-editar" data-toggle="modal" data-target="#excluirEndereco" data-dados="{{json_encode($address)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        {{-- <th colspan="6">{{$accounts->count()}} Contas</th> --}}
                                    </tfoot>
                                </table>
                            </div>

                            {{-- <div class="container mt-2">{{$accounts->links()}}</div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novoEndereco">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovoEndereco">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="modal-header">
                        <h4 class="modal-title">Novo Endereço <div class="spinner-border d-none loadCep" role="status"><span class="sr-only">Loading...</span></div></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-5 col-md-4">
                                <label for="post_code">CEP</label>
                                <input type="text" name="post_code" class="form-control" placeholder="00000-000">
                            </div>
                            <div class="form-group col-7 col-md-8">
                                <label for="address">Endereço</label>
                                <input type="text" name="address" class="form-control" placeholder="Endereço/Rua/Avenida">
                            </div>
                            <div class="form-group col-3">
                                <label for="number">Nº</label>
                                <input type="text" name="number" class="form-control" placeholder="0000">
                            </div>
                            <div class="form-group col-9">
                                <label for="complement">Complemento</label>
                                <input type="text" name="complement" class="form-control" placeholder="Complemento">
                            </div>
                            <div class="form-group col-12">
                                <label for="address2">Bairro</label>
                                <input type="text" name="address2" class="form-control" placeholder="Bairro">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="state">Estado</label>
                                <select name="state" class="form-control select2 state">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="city">Cidade</label>
                                <select name="city" class="form-control select2 city">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="phone1">Telefone</label>
                                <input type="text" name="phone1" class="form-control" placeholder="(00) 0000-0000">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="phone2">Celular</label>
                                <input type="text" name="phone2" class="form-control" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovoEndereco" data-save_route="{{route('novoEndereco')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarEndereco">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarEndereco">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Atualizar Cliente</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-5 col-md-4">
                                <label for="post_code">CEP</label>
                                <input type="text" name="post_code" class="form-control" placeholder="00000-000">
                            </div>
                            <div class="form-group col-7 col-md-8">
                                <label for="address">Endereço</label>
                                <input type="text" name="address" class="form-control" placeholder="Endereço/Rua/Avenida">
                            </div>
                            <div class="form-group col-3">
                                <label for="number">Nº</label>
                                <input type="text" name="number" class="form-control" placeholder="0000">
                            </div>
                            <div class="form-group col-9">
                                <label for="complement">Complemento</label>
                                <input type="text" name="complement" class="form-control" placeholder="Complemento">
                            </div>
                            <div class="form-group col-12">
                                <label for="address2">Bairro</label>
                                <input type="text" name="address2" class="form-control" placeholder="Bairro">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="state">Estado</label>
                                <select name="state" class="form-control select2 state">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="city">Cidade</label>
                                <select name="city" class="form-control select2 city">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="phone1">Telefone</label>
                                <input type="text" name="phone1" class="form-control" placeholder="(00) 0000-0000">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="phone2">Celular</label>
                                <input type="text" name="phone2" class="form-control" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarEndereco" data-save_route="{{route('atualizarEndereco')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirEndereco">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postExcluirEndereco">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Excluir Endereço</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Tem certeza que gostaria de apagar esse endereço?</h5>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-salvar" data-trash="S" data-save_target="#postExcluirEndereco" data-save_route="{{route('excluirEndereco')}}"><i class="fas fa-trash"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection