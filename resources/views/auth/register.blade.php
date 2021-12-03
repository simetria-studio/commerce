@extends('layouts.site')

@section('container')
    <div class="container py-3">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="login">
                <div class="row">
                    <div class="form-group col-12">
                        <label for="name">Nome Completo</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" placeholder="Nome do Usuario">
                        @error('name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-12">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" placeholder="Email da Conta">
                        @error('email')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-12">
                        <label for="cnpj_cpf">CNPJ/CPF</label>
                        <input type="text" name="cnpj_cpf" class="form-control @error('cnpj_cpf') is-invalid @enderror" value="{{old('cnpj_cpf')}}" placeholder="CNPJ ou CPF">
                        @error('cnpj_cpf')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-12">
                        <label for="password">Senha</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Senha da Conta">
                        @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-12">
                        <label for="password_confirmation">Comfirma Senha</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirma a senha digitada">
                    </div>


                    <div class="form-group col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="terms_of_service" name="terms_of_service" class="@error('terms_of_service') is-invalid @enderror">
                            <label for="terms_of_service"><a target="_blank" href="{{route('terms.show')}}" class="btn btn-link">Termos de serviço</a></label>
                            @error('terms_of_service')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="privacy_policy" name="privacy_policy" class="@error('privacy_policy') is-invalid @enderror">
                            <label for="privacy_policy"><a target="_blank" href="{{route('policy.show')}}" class="btn btn-link">Politicas de privacidade</a></label>
                            @error('privacy_policy')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                    </div>

                    <div class="form-group col-12 text-center">
                        <a href="{{route('login')}}" class="btn btn-link">Já possuo uma conta!</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection