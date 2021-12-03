@extends('layouts.site')

@section('container')
    <div class="container my-5">
        <div class="row my-3 justify-content-center">
            <div class="col-12 col-md-8">
                <div class="row py-3 px-2 border border-dark rounded">
                    <div class="col-12 col-md-4 text-center">
                        <b>Saldo Disponivel:</b> <span class="text-success">R$ {{number_format($user->bank->balance_available, 2, ',', '.')}}</span>
                    </div>
                    <div class="col-12 col-md-4 text-center">
                        <b>Saldo Retirado:</b> <span class="text-danger">R$ {{number_format($user->bank->balance_withdrawn, 2, ',', '.')}}</span>
                    </div>
                    <div class="col-12 col-md-4 text-center">
                        <b>Total:</b> <span class="text-dark">R$ {{number_format($user->bank->accumulated_total, 2, ',', '.')}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive my-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="3">Historico da Conta</th>
                    </tr>
                    <tr>
                        <th>Titulo</th>
                        <th>Descrição</th>
                        <th>Cupom</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                        <tr>
                            <td>{{$history->type}}</td>
                            <td>{{$history->history}}</td>
                            <td>{{$history->coupon}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection