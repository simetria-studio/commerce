@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contas de Acesso ao Painel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Contas</li>
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
                            <h3 class="card-title">Contas</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novaConta"><i class="fas fa-plus"></i> Nova Conta</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nome</th>
                                            <th>email</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accounts as $account)
                                            <tr class="tr-id-{{$account->id}}">
                                                <td>{{$account->id}}</td>
                                                <td>{{$account->name}}</td>
                                                <td>{{$account->email}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#atualizarSenha" data-dados="{{json_encode($account)}}"><i class="fas fa-edit"></i> Trocar Senha</a>
                                                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#editarConta" data-dados="{{json_encode($account)}}"><i class="fas fa-edit"></i> Editar Nome & Email</a>
                                                        <a href="#" class="btn btn-danger btn-sm btn-editar" data-toggle="modal" data-target="#excluirConta" data-dados="{{json_encode($account)}}"><i class="fas fa-trash"></i> Apagar Conta</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <th colspan="4">{{$accounts->count()}} Contas</th>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="container mt-2">{{$accounts->links()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novaConta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovaConta">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Nova Conta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome Completo</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome do Usuario">
                            </div>
                            <div class="form-group col-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email da Conta">
                            </div>
                            <div class="form-group col-12">
                                <label for="password">Senha</label>
                                <input type="password" name="password" class="form-control" placeholder="Senha da Conta">
                            </div>
                            <div class="form-group col-12">
                                <label for="password_confirmation">Comfirma Senha</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirma a senha digitada">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovaConta" data-save_route="{{route('novaConta')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarConta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarConta">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Atualizar Conta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome Completo</label>
                                <input type="text" name="name" class="form-control name" placeholder="Nome do Usuario">
                            </div>
                            <div class="form-group col-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control name" placeholder="Email da Conta">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarConta" data-save_route="{{route('atualizarConta')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirConta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postExcluirConta">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Conta de(a) <span class="_name"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Tem certeza que gostaria de apagar essa conta?</h5>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-salvar" data-trash="S" data-save_target="#postExcluirConta" data-save_route="{{route('excluirConta')}}"><i class="fas fa-trash"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="atualizarSenha">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postAtualizarSenha">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Atualizar senha de(a) <span class="_name"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="password">Nova Senha</label>
                                <input type="password" name="password" class="form-control" placeholder="Nova Senha">
                            </div>
                            <div class="form-group col-12">
                                <label for="password_confirmation">Confirma Nova Senha</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar Nova Senha">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-save_target="#postAtualizarSenha" data-save_route="{{route('atualizarSenha')}}"><i class="fas fa-save"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection