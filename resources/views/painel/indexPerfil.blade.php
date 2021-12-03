@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Perfil</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Perfil</li>
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
                            <h3 class="card-title">Perfil do(a) {{auth()->user()->name}}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Corpo do Card --}}
                        <div class="card-body pad table-responsive">
                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th>Nome do Perfil</th>
                                            <td>{{auth()->user()->name}}</td>
                                            <td><a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalName"><i class="fas fa-edit"></i> Trocar</a></td>
                                        </tr>
                                        <tr>
                                            <th>Email de Login</th>
                                            <td>{{auth()->user()->email}}</td>
                                            <td><a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEmail"><i class="fas fa-edit"></i> Trocar</a></td>
                                        </tr>
                                        <tr>
                                            <th>Senha de Login</th>
                                            <td>*************</td>
                                            <td><a href="#" class="bt btn-info btn-sm" data-toggle="modal" data-target="#modalPassword"><i class="fas fa-edit"></i> Trocar</a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">Obs: os dados da sessão serão alterados no proximo login!</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalName">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postName">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Alterar nome do Perfil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome do Perfil</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome do Perfil" value="{{auth()->user()->name}}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-save_target="#postName" data-save_route="{{route('nomePerfil')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEmail">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEmail">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Alterar Email de Login</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="email">Email de Login</label>
                                <input type="text" name="email" class="form-control" placeholder="Email de Login" value="{{auth()->user()->email}}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-save_target="#postEmail" data-save_route="{{route('emailPerfil')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPassword">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postSenha">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Alterar Senha de Login</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="current_password">Senha Atual</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Senha Atual">
                            </div>
                            <div class="form-group col-12">
                                <label for="password">Nova Senha</label>
                                <input type="password" name="password" class="form-control" placeholder="Nova Senha">
                            </div>
                            <div class="form-group col-12">
                                <label for="password_confirmation">Comfirme a Nova Senha</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Comfirmar Senha">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-save_target="#postSenha" data-save_route="{{route('senhaPerfil')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection