@extends('layouts.site')

@section('container')
    <div class="container py-3">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="login">
                <div class="row">
                    <div class="form-group col-12">
                        <label for="email">E-Mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" name="email">
                        @error('email')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-12">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="form-group col-6">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me">Lembrar-me?</label>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <a href="{{route('password.request')}}" class="btn btn-link">Esqueceu a senha?</a>
                    </div>

                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary btn-block">Logar</button>
                    </div>

                    <div class="form-group col-12 text-center">
                        <a href="{{route('register')}}" class="btn btn-link">Registre-se aqui!</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            setTimeout(()=>{
                window.location.reload();
            },(60000*5));
        });
    </script>
@endsection