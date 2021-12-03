@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Produtos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Produtos</li>
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
                            <h3 class="card-title">Produtos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Corpo do Card --}}
                        <div class="card-body pad">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novoProduto"><i class="fas fa-plus"></i> Novo Produto</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Imagem</th>
                                            <th>Nome</th>
                                            <th>Unidade de Venda</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            @php
                                                $data_images = []; // Setamos um array para ser utilizados
                                            @endphp

                                            {{-- Leitura das imagens vinculadas ao produto --}}
                                            @foreach ($product->productImage as $image)
                                                @php
                                                    // Pegando a iamgem e tarnsformamndo em data
                                                    if(Storage::exists($image->image_name)){
                                                        $images         = Storage::get($image->image_name);
                                                        $mime_types     = Storage::mimeType($image->image_name);
                                                        $images         = 'data:'.$mime_types.';base64,'.base64_encode($images);
    
                                                        // Adiconando a um array para depois ser utilizados
                                                        $data_images[] = [
                                                            'sequence'  => $image->sequence,
                                                            'image'     => $images
                                                        ];
                                                    }
                                                @endphp

                                                @if ($image->sequence == 1)
                                                    @php
                                                        $image_name = $image->image_name;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @php
                                                if(Storage::exists($image_name)){
                                                    // Pegamos somente a primeira imagem a ser a principal
                                                    $image      = Storage::get($image_name);
                                                    $mime_type  = Storage::mimeType($image_name);
                                                    $image      = 'data:'.$mime_type.';base64,'.base64_encode($image);
                                                }
                                            @endphp
                                            <tr class="tr-id-{{$product->id}}">
                                                <td>{{$product->id}}</td>
                                                <td><img width="100px" src="{{$image}}"></td>
                                                <td>{{$product->name}}</td>
                                                <td>{{$sales_unit_array[$product->sales_unit]}}</td>
                                                <td>R$ {{number_format($product->value, 2, ',', '.')}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarProduto" data-images="{{json_encode($data_images)}}" data-dados="{{json_encode($product)}}"><i class="fas fa-edit"></i> Alterar</a>
                    
                                                        <a href="#" class="btn btn-danger btn-xs btn-excluir-produto" data-toggle="modal" data-target="#excluirProduto" data-dados="{{json_encode($product)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <th colspan="8">{{$products->count()}} Produtos</th>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="container mt-2">{{$products->links()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novoProduto">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" id="postNovoProduto">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Novo Produto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="newProduct-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="newProduct-dadosgerais-tab" data-toggle="pill" href="#newProduct-dadosgerais" role="tab" aria-controls="newProduct-dadosgerais" aria-selected="true">Dados Gerais</a>
                            </li>
                            <li class="nav-item atributo-tab d-none" role="presentation">
                                <a class="nav-link" id="newProduct-atributo-tab" data-toggle="pill" href="#newProduct-atributo" role="tab" aria-controls="newProduct-atributo" aria-selected="false">Atributos/Variações</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="newProduct-imagens-tab" data-toggle="pill" href="#newProduct-imagens" role="tab" aria-controls="newProduct-imagens" aria-selected="false">Imagens</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="newProduct-informacao-tab" data-toggle="pill" href="#newProduct-informacao" role="tab" aria-controls="newProduct-informacao" aria-selected="false">Informações</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="newProduct-tabContent">
                            <div class="tab-pane fade show active" id="newProduct-dadosgerais" role="tabpanel" aria-labelledby="newProduct-dadosgerais-tab">
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-2">
                                        <label for="code">Codigo</label>
                                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Codigo do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label for="name">Nome</label>
                                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Nome do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label for="brief_description">Breve Descrição</label>
                                        <input type="text" name="brief_description" class="form-control form-control-sm" placeholder="Descrição Breve do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="value">Valor R$</label>
                                        <input type="text" name="value" class="form-control form-control-sm" placeholder="Valor Real do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="brand">Marca</label>
                                        <input type="text" name="brand" class="form-control form-control-sm" placeholder="Marca do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label for="sales_unit">Unidade de Venda</label>
                                        <select name="sales_unit" class="form-control form-control-sm select2">
                                            <option value=""> - Selecione um Opção - </option>
                                            <option value="P">Peça</option>
                                            <option value="M">Metro</option>
                                            <option value="MQ">Metro Quadrado</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label for="product_type">Tipo de Produto</label>
                                        <select name="product_type" class="form-control form-control-sm select2">
                                            <option value=""> - Selecione um Opção - </option>
                                            <option value="simples">Simples</option>
                                            <option value="variacoes">Variações</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-2">
                                        <label for="weight">Peso (kg)</label>
                                        <input type="text" name="weight" class="form-control form-control-sm" placeholder="Peso do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="height">Altura (cm)</label>
                                        <input type="text" name="height" class="form-control form-control-sm" placeholder="Altura do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="width">Largura (cm)</label>
                                        <input type="text" name="width" class="form-control form-control-sm" placeholder="Largura do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="length">Comprimento (cm)</label>
                                        <input type="text" name="length" class="form-control form-control-sm" placeholder="Comprimento do Produto">
                                    </div>
                                </div>
        
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-4">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="has_preparation_check" class="has_preparation">
                                            <input type="hidden" name="has_preparation">
                                            <label for="has_preparation_check">Produto Possui Tempo de Preparo?</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-2 d-none preparation_time">
                                        <label for="preparation_time">Dias</label>
                                        <input type="text" class="form-control form-control-sm" name="preparation_time">
                                    </div>
                                </div>
        
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-3">
                                        <label for="main_category">Categoria Principal</label>
                                        <select name="main_category" class="form-control form-control-sm select2 main_category">
                                            <option value="" data-new_category="category_id"> - Nova Categoria - </option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
        
                                        <div class="mt-1">
                                            <button type="button" class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#novaCategoria"><i class="fas fa-plus"></i> Nova Categoria</button>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label for="sub_category">Sub Categoria</label>
                                        <select name="sub_category[]" class="form-control form-control-sm select2 sub_category" multiple></select>
        
                                        <div class="mt-1 d-none">
                                            <button type="button" class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#novaCategoria"><i class="fas fa-plus"></i> Nova Sub Categoria</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="newProduct-atributo" role="tabpanel" aria-labelledby="newProduct-atributo-tab">
                                <div id="accordion">
                                    @foreach ($attributes as $attribute)
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h4 class="card-title w-100">
                                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapse-{{$attribute->id}}" aria-expanded="false">
                                                        {{$attribute->name}}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse-{{$attribute->id}}" class="collapse" data-parent="#accordion" style="">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Adicionar</th>
                                                                <th>Nª</th>
                                                                <th>Imagem/Cor</th>
                                                                <th>Nome</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="icheck-primary">
                                                                        <input type="checkbox" id="icheck_{{$attribute->id}}" class="icheck_attribute">
                                                                        <input type="hidden" class="icheck_attribute_input" name="attribute[{{$attribute->id}}][icheck_attribute]">
                                                                        <label for="icheck_{{$attribute->id}}"></label>
                                                                    </div>
                                                                </td>
                                                                <td colspan="4">
                                                                    <input type="hidden" name="attribute[{{$attribute->id}}][parent_id]" value="{{$attribute->id}}">
                                                                    Adicionar nenhuma opção?
                                                                </td>
                                                            </tr>
                                                            @foreach ($attribute->variations as $variation)
                                                                <tr>
                                                                    <td>
                                                                        <div class="icheck-primary">
                                                                            <input type="checkbox" id="icheck_{{$variation->id}}" class="icheck_attribute">
                                                                            <input type="hidden" class="icheck_attribute_input" name="attribute[{{$variation->id}}][icheck_attribute]">
                                                                            <label for="icheck_{{$variation->id}}"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{$variation->id}}</td>
                                                                    <td>
                                                                        @if ($variation->image)
                                                                            @php
                                                                                // Pegamos somente a primeira imagem a ser a principal
                                                                                $image      = Storage::get($variation->image);
                                                                                $mime_type  = Storage::mimeType($variation->image);
                                                                                $image      = 'data:'.$mime_type.';base64,'.base64_encode($image);
                                                                            @endphp
                                                                            <img width="45px" src="{{$image}}" alt="">
                                                                        @else
                                                                            <div style="width: 45px; height: 45px; background-color: {{$variation->hexadecimal}};"></div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{$variation->name}}
                                                                        <input type="hidden" name="attribute[{{$variation->id}}][parent_id]" value="{{$attribute->id}}">
                                                                        <input type="hidden" name="attribute[{{$variation->id}}][attribute_id]" value="{{$variation->id}}">
                                                                        <input type="hidden" name="attribute[{{$variation->id}}][attribute_name]" value="{{$variation->name}}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control form-control-sm" name="attribute[{{$variation->id}}][attribute_value]">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="newProduct-imagens" role="tabpanel" aria-labelledby="newProduct-imagens-tab">
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-6">
                                        <div class="custom-file">
                                            <input name="img_principal" type="file" class="custom-file-input img_principal">
                                            <label class="custom-file-label" for="img_principal">Foto Principal</label>
                                        </div>
        
                                        <div class="my-2 img-principal"></div>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <div class="custom-file">
                                            <input name="img_multipla[]" type="file" class="custom-file-input img_multipla" multiple>
                                            <label class="custom-file-label" for="img_multipla">Multiplas Fotos</label>
                                        </div>
        
                                        <div class="my-2 img-multipla"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="newProduct-informacao" role="tabpanel" aria-labelledby="newProduct-informacao-tab">
                                <div class="form-row my-2">
                                    <div class="form-group col-12">
                                        <textarea name="description" class="textarea"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovoProduto" data-save_route="{{route('novoProduto')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarProduto">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" id="postEditarProduto">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Produto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="editProduct-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="editProduct-dadosgerais-tab" data-toggle="pill" href="#editProduct-dadosgerais" role="tab" aria-controls="editProduct-dadosgerais" aria-selected="true">Dados Gerais</a>
                            </li>
                            <li class="nav-item atributo-tab d-none" role="presentation">
                                <a class="nav-link" id="editProduct-atributo-tab" data-toggle="pill" href="#editProduct-atributo" role="tab" aria-controls="editProduct-atributo" aria-selected="false">Atributos/Variações</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="editProduct-imagens-tab" data-toggle="pill" href="#editProduct-imagens" role="tab" aria-controls="editProduct-imagens" aria-selected="false">Imagens</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="editProduct-informacao-tab" data-toggle="pill" href="#editProduct-informacao" role="tab" aria-controls="editProduct-informacao" aria-selected="false">Informações</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="editProduct-tabContent">
                            <div class="tab-pane fade show active" id="editProduct-dadosgerais" role="tabpanel" aria-labelledby="editProduct-dadosgerais-tab">
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-2">
                                        <label for="code">Codigo</label>
                                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Codigo do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label for="name">Nome</label>
                                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Nome do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label for="brief_description">Breve Descrição</label>
                                        <input type="text" name="brief_description" class="form-control form-control-sm" placeholder="Descrição Breve do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label for="sales_unit">Unidade de Venda</label>
                                        <select name="sales_unit" class="form-control form-control-sm select2 sales_unit">
                                            <option value=""> - Selecione um Opção - </option>
                                            <option value="P">Peça</option>
                                            <option value="M">Metro</option>
                                            <option value="MQ">Metro Quadrado</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="value">Valor R$</label>
                                        <input type="text" name="value" class="form-control form-control-sm" placeholder="Valor Real do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="brand">Marca</label>
                                        <input type="text" name="brand" class="form-control form-control-sm" placeholder="Marca do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label for="product_type">Tipo de Produto</label>
                                        <select name="product_type" class="form-control form-control-sm select2">
                                            <option value=""> - Selecione um Opção - </option>
                                            <option value="simples">Simples</option>
                                            <option value="variacoes">Variações</option>
                                        </select>
                                    </div>
                                </div>
        
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-2">
                                        <label for="weight">Peso (kg)</label>
                                        <input type="text" name="weight" class="form-control form-control-sm" placeholder="Peso do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="height">Altura (cm)</label>
                                        <input type="text" name="height" class="form-control form-control-sm" placeholder="Altura do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="width">Largura (cm)</label>
                                        <input type="text" name="width" class="form-control form-control-sm" placeholder="Largura do Produto">
                                    </div>
                                    <div class="form-group col-12 col-md-2">
                                        <label for="length">Comprimento (cm)</label>
                                        <input type="text" name="length" class="form-control form-control-sm" placeholder="Comprimento do Produto">
                                    </div>
                                </div>
        
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-4">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="has_preparation_check2" class="has_preparation">
                                            <input type="hidden" name="has_preparation">
                                            <label for="has_preparation_check2">Produto Possui Tempo de Preparo?</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-2 d-none preparation_time">
                                        <label for="preparation_time">Dias</label>
                                        <input type="text" class="form-control form-control-sm" name="preparation_time">
                                    </div>
                                </div>
        
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-3">
                                        <label for="main_category">Categoria Principal</label>
                                        <select name="main_category" class="form-control form-control-sm select2 main_category">
                                            <option value="" data-new_category="category_id"> - Nova Categoria - </option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
        
                                        <div class="mt-1">
                                            <button type="button" class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#novaCategoria"><i class="fas fa-plus"></i> Nova Categoria</button>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label for="sub_category">Sub Categoria</label>
                                        <select name="sub_category[]" class="form-control form-control-sm select2 sub_category" multiple></select>
        
                                        <div class="mt-1 d-none">
                                            <button type="button" class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#novaCategoria"><i class="fas fa-plus"></i> Nova Sub Categoria</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="editProduct-atributo" role="tabpanel" aria-labelledby="editProduct-atributo-tab">
                                <div id="accordion">
                                    @foreach ($attributes as $attribute)
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h4 class="card-title w-100">
                                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapse-{{$attribute->id}}" aria-expanded="false">
                                                        {{$attribute->name}}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse-{{$attribute->id}}" class="collapse" data-parent="#accordion" style="">
                                                <div class="card-body table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Adicionar</th>
                                                                <th>Nª</th>
                                                                <th>Imagem/Cor</th>
                                                                <th>Nome</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="icheck-primary">
                                                                        <input type="checkbox" id="edit_icheck_{{$attribute->id}}" class="icheck_attribute">
                                                                        <input type="hidden" class="icheck_attribute_input" name="attribute[{{$attribute->id}}][icheck_attribute]">
                                                                        <label for="edit_icheck_{{$attribute->id}}"></label>
                                                                    </div>
                                                                </td>
                                                                <td colspan="4">
                                                                    <input type="hidden" name="attribute[{{$attribute->id}}][parent_id]" value="{{$attribute->id}}">
                                                                    Adicionar nenhuma opção?
                                                                </td>
                                                            </tr>
                                                            @foreach ($attribute->variations as $variation)
                                                                <tr>
                                                                    <td>
                                                                        <div class="icheck-primary">
                                                                            <input type="checkbox" id="edit_icheck_{{$variation->id}}" class="icheck_attribute">
                                                                            <input type="hidden" class="icheck_attribute_input" name="attribute[{{$variation->id}}][icheck_attribute]">
                                                                            <label for="edit_icheck_{{$variation->id}}"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{$variation->id}}</td>
                                                                    <td>
                                                                        @if ($variation->image)
                                                                            @php
                                                                                // Pegamos somente a primeira imagem a ser a principal
                                                                                $image      = Storage::get($variation->image);
                                                                                $mime_type  = Storage::mimeType($variation->image);
                                                                                $image      = 'data:'.$mime_type.';base64,'.base64_encode($image);
                                                                            @endphp
                                                                            <img width="45px" src="{{$image}}" alt="">
                                                                        @else
                                                                            <div style="width: 45px; height: 45px; background-color: {{$variation->hexadecimal}};"></div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{$variation->name}}
                                                                        <input type="hidden" name="attribute[{{$variation->id}}][parent_id]" value="{{$attribute->id}}">
                                                                        <input type="hidden" name="attribute[{{$variation->id}}][attribute_id]" value="{{$variation->id}}">
                                                                        <input type="hidden" name="attribute[{{$variation->id}}][attribute_name]" value="{{$variation->name}}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control form-control-sm" name="attribute[{{$variation->id}}][attribute_value]">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="editProduct-imagens" role="tabpanel" aria-labelledby="editProduct-imagens-tab">
                                <div class="form-row my-1">
                                    <div class="form-group col-12 col-md-6">
                                        <div class="custom-file">
                                            <input name="img_principal" type="file" class="custom-file-input img_principal">
                                            <label class="custom-file-label" for="img_principal">Foto Principal</label>
                                        </div>
        
                                        <div class="my-2 img-principal"></div>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <div class="custom-file">
                                            <input name="img_multipla[]" type="file" class="custom-file-input img_multipla" multiple>
                                            <label class="custom-file-label" for="img_multipla">Multiplas Fotos</label>
                                        </div>
        
                                        <div class="my-2 img-multipla"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="editProduct-informacao" role="tabpanel" aria-labelledby="editProduct-informacao-tab">
                                <div class="form-row my-2">
                                    <div class="form-group col-12">
                                        <textarea name="description" class="textarea"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-save_target="#postEditarProduto" data-save_route="{{route('atualizarProduto')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirProduto">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postProductExcluirProduto">
                    @csrf
                    <input type="hidden" name="product_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Excluir Produto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Tem Certeza que gostaria de inativar esse produto <span class="product-name"></span></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-confirma-exclusao-produto"><i class="fas fa-trash"></i> Inativar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Nova Categoria --}}
    <div class="modal fade" id="novaCategoria">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postProductNovaCategoria">
                    @csrf
                    <input type="hidden" name="parent_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Nova Categoria</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="category_name">Nome da Categoria</label>
                                <input type="text" name="category_name" class="form-control form-control-sm" placeholder="Nome da Categoria">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-save_target="#postProductNovaCategoria" data-save_route="{{route('novaCategoria')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection