@extends('layouts.site')

@section('container')
    <div class="container">
        <div class="row my-5 justify-content-center">
            <div class="col-12">
                @if (session()->has('success'))
                    <div class="alert alert-success text-center">
                        {{session()->get('success')}}
                    </div>
                @endif

                @if (session()->has('destroy'))
                    <div class="alert alert-danger text-center">
                        {{session()->get('destroy')}}
                    </div>
                @endif
            </div>

            <div class="col-12 col-md-8 col-lg-4">
                <h3 class="mb-3">Dados Pessoais</h3>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Nome: {{auth()->user()->name}}</li>
                    <li class="list-group-item">Email: {{auth()->user()->email}}</li>
                    <li class="list-group-item">CNPJ/CPF: {{auth()->user()->cnpj_cpf}}</li>
                    <li class="list-group-item">Data de Nascimento: {{date("d/m/Y", strtotime(str_replace('-','/', auth()->user()->birth_date)))}}</li>
                    <li class="list-group-item">
                        <a href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#alterarDados"><i class="fa fa-user-cog"></i> Alterar Dados</a> 
                        <a href="#" class="btn btn-block btn-warning" data-toggle="modal" data-target="#alterarSenha"><i class="fa fa-user-lock"></i> Alterar Senha</a>
                    </li>
                </ul>
            </div>

            @foreach ($addresses as $address)
                <div class="col-12 col-md-8 col-lg-4">
                    <h3 class="mb-3">Endereço</h3>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Cep: {{$address->post_code}}</li>
                        <li class="list-group-item">Cidade: {{$address->city}} - UF: {{$address->state}}</li>
                        <li class="list-group-item">Bairro: {{$address->address2}}</li>
                        <li class="list-group-item">{{$address->address}} - Nº {{$address->number}}</li>
                        <li class="list-group-item">Complemento: {{$address->complement}}</li>
                        <li class="list-group-item">Telefone: {{$address->phone1}}</li>
                        <li class="list-group-item">Celular: {{$address->phone2}}</li>
                        <li class="list-group-item">
                            <a href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#enderecos" data-dados="{{json_encode($address)}}"><i class="fa fa-user-cog"></i> Alterar Endereço</a> 
                            <a href="#" class="btn btn-block btn-danger btn-excluir-address" data-id="{{$address->id}}" data-url="{{asset('apagarEndereco')}}"><i class="fa fa-user-lock"></i> Apagar Endereço</a>
                        </li>
                    </ul>
                </div>
            @endforeach

            @if ($addresses->count() <= 1)
                <div class="col-12 col-md-8 col-lg-4"><a href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#enderecos"><i class="fa fa-map-marker-alt"></i> Adicionar Novo Endereço</a></div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="alterarDados">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{asset('perfilSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Dados</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome Completo</label>
                                <input type="text" name="name" class="form-control" value="{{auth()->user()->name}}" placeholder="Nome do Usuario">
                            </div>
                            <div class="form-group col-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{auth()->user()->email}}" placeholder="Email da Conta" disabled>
                            </div>
                            <div class="form-group col-12">
                                <label for="cnpj_cpf">CNPJ/CPF</label>
                                <input type="text" name="cnpj_cpf" class="form-control" value="{{auth()->user()->cnpj_cpf}}" placeholder="CNPJ ou CPF">
                            </div>
                            <div class="form-group col-12">
                                <label for="birth_date">Data de Nascimento</label>
                                <input type="text" name="birth_date" class="form-control" value="{{date("d/m/Y", strtotime(str_replace('-','/', auth()->user()->birth_date)))}}" placeholder="Data de Nascimento">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="alterarSenha">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{asset('senhaSave')}}" action="#" method="post">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Alterar senha</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="current_password">Senha Antiga</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Senha Antiga">
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="password">Nova Senha</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nova Senha">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="password_confirmation">Confirma Nova Senha</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar Nova Senha">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="enderecos">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{asset('enderecoSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Endereço <div class="spinner-border d-none loadCep" role="status"><span class="sr-only">Loading...</span></div></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-5 col-md-4">
                                <label for="post_code">CEP:</label>
                                <input type="text" class="form-control @error('post_code') is-invalid @enderror" name="post_code" placeholder="00000-000">
    
                                @error('post_code')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-7 col-md-8">
                                <label for="address">Endereço:</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" placeholder="Endereço/Rua/Avenida" >
        
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-3">
                                <label for="number">Nª:</label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" placeholder="0000">
        
                                @error('number')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-9">
                                <label for="complement">Complemento:</label>
                                <input type="text" class="form-control" name="complement" placeholder="Complemento">
                            </div>

                            <div class="form-group col-12">
                                <label for="address2">Bairro:</label>
                                <input type="text" class="form-control @error('address2') is-invalid @enderror" name="address2" placeholder="Bairro">
        
                                @error('address2')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="state">Estado</label>
                                <select name="state" class="form-control select2 state @error('state') is-invalid @enderror"">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>

                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="city">Cidade</label>
                                <select name="city" class="form-control select2 city @error('city') is-invalid @enderror">
                                    <option value="">::Selecione uma Opção::</option>
                                </select>

                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-12">
                                <label for="phone1">Telefone:</label>
                                <input type="text" class="form-control" name="phone1" placeholder="Telefone">
                            </div>
                            <div class="form-group col-12">
                                <label for="phone2">Celular:</label>
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" name="phone2" placeholder="Celular">

                                @error('phone2')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            if($('#alterarSenha').find('.is-invalid').length > 0) $('#alterarSenha').modal('show');
            if($('#enderecos').find('.is-invalid').length > 0) $('#enderecos').modal('show');
        });
    </script>
@endsection