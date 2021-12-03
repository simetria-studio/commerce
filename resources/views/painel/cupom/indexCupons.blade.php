@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cupons</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Cupons</li>
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
                            <h3 class="card-title">Cupons Disponiveis</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novoCupom"><i class="fas fa-plus"></i> Novo Cupom</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nome</th>
                                            <th>Codigo</th>
                                            <th>Valor do Desconto</th>
                                            <th>Data Inicial</th>
                                            <th>Data Final</th>
                                            <th>Valor Minimo/Maximo</th>
                                            <th>Descontos Aceitos</th>
                                            <th>Maximo Parcelado Aceito</th>
                                            <th>Usuarios Vinculado</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($coupons as $coupon)
                                            <tr class="tr-id-{{$coupon->id}}">
                                                <td>{{$coupon->id}}</td>
                                                <td>{{$coupon->name}}</td>
                                                <td>{{$coupon->code}}</td>
                                                <td>{{$coupon->value.($coupon->discount_type == 'P' ? '%' : 'R$')}}</td>
                                                <td>{{date('d/m/Y', strtotime(str_replace('-','/',  $coupon->start_date)))}}</td>
                                                <td>{{date('d/m/Y', strtotime(str_replace('-','/',  $coupon->final_date)))}}</td>
                                                <td>{{$coupon->min_value}} R$ | {{$coupon->max_value}} R$</td>
                                                <td>{{implode(',', json_decode($coupon->discount_accepted))}}</td>
                                                <td>{{$coupon->installemnts}}</td>
                                                <td>{{count(json_decode($coupon->user_id))}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCupom" data-dados="{{json_encode($coupon)}}"><i class="fas fa-edit"></i> Alterar</a>
                                                        <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirCupom" data-dados="{{json_encode($coupon)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <th colspan="11">{{$coupons->count()}} Cupons</th>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="container mt-2">{{$coupons->links()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novoCupom">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovoCupom">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Novo Cupom</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome do Cupom</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome do Cupom">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="code">Codigo do Cupom</label>
                                <input type="text" name="code" class="form-control text-uppercase" placeholder="Codigo do Cupom">
                            </div>
                            <div class="form-group col-12 col-md-8">
                                <label for="start_end_date">Data Inicial e Final</label>
                                <input type="text" name="start_end_date" class="form-control date-mask">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="discount_type">Tipo de Desconto</label>
                                <select name="discount_type" class="form-control select2">
                                    <option value="">- Selecione uma Opção -</option>
                                    <option value="P">Porcentagem %</option>
                                    <option value="V">Valor R$</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="value">Valor</label>
                                <input type="text" name="value" class="form-control" placeholder="Valor">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="min_value">Valor Minimo</label>
                                <input type="text" name="min_value" class="form-control" placeholder="Valor">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="max_value">Valor Maximo</label>
                                <input type="text" name="max_value" class="form-control" placeholder="Valor">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="discount_accepted">Descontos Aceitos</label>
                                <select name="discount_accepted[]" multiple="multiple" data-placeholder="- Selecione uma Opção -" class="form-control select2">
                                    <option value="debito">Debito</option>
                                    <option value="credito">Credito</option>
                                    <option value="pix">PIX</option>
                                    <option value="parcelado">Parcelado</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="installemnts">Maximo de Parcela</label>
                                <select name="installemnts" class="form-control select2">
                                    @for ($i = 1; $i < 25; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="user_id">Afiliados Vinculados</label>
                                <select name="user_id[]" multiple="multiple" data-placeholder="- Selecione uma Opção -" class="form-control select2">
                                    @foreach ($afiliados as $afiliado)
                                        <option value="{{$afiliado->id}}">{{$afiliado->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovoCupom" data-save_route="{{route('novoCupom')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarCupom">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarCupom">
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
                            <div class="form-group col-12">
                                <label for="name">Nome do Cupom</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome do Cupom">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="code">Codigo do Cupom</label>
                                <input type="text" name="code" class="form-control text-uppercase" placeholder="Codigo do Cupom">
                            </div>
                            <div class="form-group col-12 col-md-8">
                                <label for="start_end_date">Data Inicial e Final</label>
                                <input type="text" name="start_end_date" class="form-control date-mask">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="discount_type">Tipo de Desconto</label>
                                <select name="discount_type" class="form-control select2">
                                    <option value="">- Selecione uma Opção -</option>
                                    <option value="P">Porcentagem %</option>
                                    <option value="V">Valor R$</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="value">Valor</label>
                                <input type="text" name="value" class="form-control" placeholder="Valor">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="min_value">Valor Minimo</label>
                                <input type="text" name="min_value" class="form-control" placeholder="Valor">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="max_value">Valor Maximo</label>
                                <input type="text" name="max_value" class="form-control" placeholder="Valor">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="discount_accepted">Descontos Aceitos</label>
                                <select name="discount_accepted[]" multiple="multiple" data-placeholder="- Selecione uma Opção -" class="form-control discount_accepted select2">
                                    <option value="debito">Debito</option>
                                    <option value="credito">Credito</option>
                                    <option value="pix">PIX</option>
                                    <option value="parcelado">Parcelado</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="installemnts">Maximo de Parcela</label>
                                <select name="installemnts" class="form-control select2">
                                    @for ($i = 1; $i < 25; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="user_id">Afiliados Vinculados</label>
                                <select name="user_id[]" multiple="multiple" data-placeholder="- Selecione uma Opção -" class="form-control user_id select2">
                                    @foreach ($afiliados as $afiliado)
                                        <option value="{{$afiliado->id}}">{{$afiliado->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarCupom" data-save_route="{{route('atualizarCupom')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirCupom">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postExcluirCupom">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Cupom <span class="_name"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Tem certeza que gostaria de apagar esse cupom?</h5>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-salvar" data-trash="S" data-save_target="#postExcluirCupom" data-save_route="{{route('excluirCupom')}}"><i class="fas fa-trash"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection