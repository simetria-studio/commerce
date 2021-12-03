@extends('layouts.site')

@section('container')
    <div class="container my-5">
        <div class="row">
            {{-- Contatos --}}
            <div class="col-12 col-md-6">
                <h2><strong>CONTATO</strong></h2>
                <br />

                <p><strong>Whatsapp:</strong></p>
                <p>(41) 9 9999-9999</p>
                <br />

                <p><strong>E-mail:</strong></p>
                <p>contato@contato.com.br</p>
                <br />

                <p><strong>Endereço</strong></p>
                <p>R. Teste de Teste, 9999</p>
            </div>

            {{-- Formulario de email --}}
            <div class="col-12 col-md-6 form-email">
                <form action="#" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="name">Nome Completo</label>
                            <input type="text" name="name" class="form-control" placeholder="Nome Completo">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="email">E-Mail</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@exemplo.com.br">
                        </div>
                        <div class="form-group col-12">
                            <label for="assunto">Assunto</label>
                            <input type="text" name="assunto" class="form-control" placeholder="Assunto">
                        </div>
                        <div class="form-group col-12">
                            <label for="mensagem">Mensagem</label>
                            <textarea name="mensagem" cols="30" rows="10" class="form-control" placeholder="Escreva sua mensagem"></textarea>
                        </div>
                        <div class="form-group col-12">
                            <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection