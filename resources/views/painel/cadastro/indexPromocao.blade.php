@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Promoções</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Promoções</li>
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
                            <h3 class="card-title">Promoções</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novaPromocao"><i class="fas fa-plus"></i> Nova Promoção</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nome da Seção/Produto</th>
                                            <th>Valor</th>
                                            <th>Data de Inicio</th>
                                            <th>Data Final</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($promotions as $promotion)
                                            <tr class="tr-id-{{$promotion->id}}">
                                                <td>
                                                    {{$promotion->id}} 
                                                    @if (date('Y-m-d', strtotime($promotion->final_date)) <= date('Y-m-d'))
                                                        <span class="text-danger">INATIVO</span>
                                                    @endif
                                                    @if (date('Y-m-d', strtotime($promotion->final_date)) >= date('Y-m-d'))
                                                        <span class="text-success">ATIVO</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($promotion->category == 'S')
                                                        @php
                                                            $secao = 'Categoria';
                                                            $name = App\Models\Category::where('id', $promotion->identifier)->first()->name;
                                                        @endphp
                                                    @endif

                                                    @if ($promotion->category == 'N')
                                                        @php
                                                            $secao = 'Produto';
                                                            $name = App\Models\Product::where('id', $promotion->identifier)->first()->name;
                                                        @endphp
                                                    @endif
                                                    {{$secao}} / 
                                                    {{$name}}
                                                </td>
                                                <td>{{$promotion->value}}% OFF </td>
                                                <td>{{date('d/m/Y', strtotime($promotion->start_date))}}</td>
                                                <td>{{date('d/m/Y', strtotime($promotion->final_date))}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarPromocao" data-dados="{{json_encode($promotion)}}"><i class="fas fa-edit"></i> Alterar</a>
                    
                                                        <a href="#" class="btn btn-danger btn-xs btn-excluir-promocao" data-toggle="modal" data-target="#excluirPromocao" data-dados="{{json_encode($promotion)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        {{-- <th colspan="8">{{$products->count()}} Produtos</th> --}}
                                    </tfoot>
                                </table>
                            </div>

                            <div class="container mt-2">{{$promotions->links()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novaPromocao">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" id="postNovaPromocao">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Nova Promoção</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row my-1">
                            <div class="form-group col-12">
                                <h5>Adcionar Promoção na Categoria/Produto?</h5>
                            </div>
                            <div class="form-group col-6">
                                <div class="icheck-primary">
                                    <input type="radio" id="category_check" name="product_category" value="category">
                                    <label for="category_check">Categorias</label>
                                </div>
                                
                            </div>
                            <div class="form-group col-6">
                                <div class="icheck-primary">
                                    <input type="radio" id="product_check" name="product_category" value="product">
                                    <label for="product_check">Produtos</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row my-1">
                            <div class="form-group col-12 col-md-4">
                                <label for="value">Desconto</label>
                                <div class="input-group">
                                    <input type="text" name="value" class="form-control" maxlength="3">
                                    <div class="input-group-append">
                                        <span class="input-group-text">% OFF</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-8">
                                <label for="start_end_date">Data Inicial e Final</label>
                                <input type="text" name="start_end_date" class="form-control date-mask">
                            </div>
                        </div>

                        {{-- Produtos --}}
                        <div id="product_check_form" class="form-row my-1 d-none">
                            <div class="form-group col-12">
                                <label for="products">Produtos</label>
                                <select name="products[]" multiple="multiple" data-placeholder="- Selecione um Produto -" class="form-control select2">
                                    @forelse ($products as $product)
                                        @if ($product->promotionP->count() == 0)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endif
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        {{-- Categorias --}}
                        <div id="category_check_form" class="form-row my-1 d-none">
                            <div class="form-group">
                                <h5>Adicionar na Categoria Principal ou Sub Categoria?</h5>
                            </div>

                            <div class="form-group col-6">
                                <div class="icheck-primary">
                                    <input type="radio" id="main_category_check" name="categories" value="main_category">
                                    <label for="main_category_check">Categoria Principal</label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <div class="icheck-primary">
                                    <input type="radio" id="sub_category_check" name="categories" value="sub_category">
                                    <label for="sub_category_check">Sub Categorias</label>
                                </div>
                            </div>
                        </div>

                        <div id="main_category_check_form" class="form-roe my-1 d-none">
                            <div class="form-group col-12">
                                <label for="main_category">Categoria Principal</label>
                                <select name="main_category[]" multiple="multiple" data-placeholder="- Selecione uma Categoria -" class="form-control select2">
                                    @forelse ($main_categories as $main_category)
                                        @if ($main_category->promotionC->count() == 0)
                                            <option value="{{$main_category->id}}">{{$main_category->name}}</option>
                                        @endif
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div id="sub_category_check_form" class="form-roe my-1 d-none">
                            <div class="form-group col-12">
                                <label for="sub_category">Sub Categoria</label>
                                <select name="sub_category[]" multiple="multiple" data-placeholder="- Selecione uma Sub Categoria -" class="form-control select2">
                                    @forelse ($sub_categories as $sub_category)
                                        @if ($sub_category->promotionC->count() == 0)
                                            <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                        @endif
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovaPromocao" data-save_route="{{route('novaPromocao')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarPromocao">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" id="postEditarPromocao">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Promoção</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row my-1">
                            <div class="form-group col-12 col-md-4">
                                <label for="value">Desconto</label>
                                <div class="input-group">
                                    <input type="text" name="value" class="form-control" maxlength="3">
                                    <div class="input-group-append">
                                        <span class="input-group-text">% OFF</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-8">
                                <label for="start_end_date">Data Inicial e Final</label>
                                <input type="text" name="start_end_date" class="form-control date-mask">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarPromocao" data-save_route="{{route('atualizarPromocao')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirPromocao">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postPromotionExcluirPromocao">
                    @csrf
                    <input type="hidden" name="promotion_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Excluir Promoção</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Tem Certeza que gostaria de excluir a Promoção selecionada?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-confirma-exclusao-promocao"><i class="fas fa-trash"></i> Apagar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection