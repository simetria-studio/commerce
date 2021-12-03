@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Atributos e suas Variações</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">@if ($id) <a href="{{asset('/admin/cadastro/categoria_menu')}}">Atributos</a> @else Atributos @endif</li>
                        @if ($id) <li class="breadcrumb-item active">{{$attribute_name}}</li> @endif
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
                            <h3 class="card-title">@if ($id) <strong>Variação de {{$attribute_name}}</strong> @else Atributos @endif</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novoAtributo"><i class="fas fa-plus"></i> Novo Atributo</button>
                                    </div>
                                    <div class="col-6 text-right">
                                        @if ($id)
                                            <a class="btn btn-default btn-sm" href="{{asset('admin/cadastro/atributos')}}"><i class="fas fa-arrow-left"></i> Voltar</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            @if ($id !== null) <th>Imagem/Cor</th> @endif
                                            <th>Nome</th>
                                            @if ($id == null) <th>Variações</th> @endif
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($attributes as $attribute)
                                            <tr class="tr-id-{{$attribute->id}}">
                                                <td>{{$attribute->id}}</td>
                                                @if ($attribute->parent_id !== null)
                                                    <td>
                                                        @if ($attribute->image)
                                                            @php
                                                                // Pegamos somente a primeira imagem a ser a principal
                                                                $image      = Storage::get($attribute->image);
                                                                $mime_type  = Storage::mimeType($attribute->image);
                                                                $image      = 'data:'.$mime_type.';base64,'.base64_encode($image);
                                                            @endphp
                                                            <img width="45px" src="{{$image}}" alt="">
                                                        @else
                                                            <div style="width: 45px; height: 45px; background-color: {{$attribute->hexadecimal}};"></div>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td>{{$attribute->name}}</td>
                                                @if ($attribute->parent_id == null) <td>{{$attribute->variations->count()}}</td> @endif
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        @if ($attribute->parent_id == null)
                                                            <a href="{{url('admin/cadastro/atributos', $attribute->id)}}" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Vizualizar</a>
                                                        @endif
    
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarAtributo" data-dados="{{json_encode($attribute)}}"><i class="fas fa-edit"></i> Alterar</a>
                    
                                                        <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirAtributo" data-dados="{{json_encode($attribute)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <th colspan="5">{{$attributes->count()}} Atributos</th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novoAtributo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovoAtributo">
                    @csrf
                    <input type="hidden" name="parent_id" value="@if($id){{$id}}@endif">
                    <div class="modal-header">
                        <h4 class="modal-title">Novo Atributo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome do Atributo</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome do Atributo">
                            </div>
                        </div>
                        @if ($id !== null)
                            <div class="form-row my-1">
                                <div class="form-group col-12">
                                    <h5>Imagem ou Cor?</h5>
                                </div>
                                <div class="form-group col-6">
                                    <div class="icheck-primary">
                                        <input type="radio" id="imagem_check" name="attribute_check" value="image">
                                        <label for="imagem_check">Imagem</label>
                                    </div>
                                    
                                </div>
                                <div class="form-group col-6">
                                    <div class="icheck-primary">
                                        <input type="radio" id="cor_check" name="attribute_check" value="color">
                                        <label for="cor_check">Cor</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Imagem --}}
                            <div class="form-row my-1 imagem_check_form d-none">
                                <div class="form-group col-12">
                                    <div class="custom-file">
                                        <input name="img_icon" type="file" class="custom-file-input img_icon">
                                        <label class="custom-file-label" for="img_icon">Imagem Pequena (45x45)</label>
                                    </div>
    
                                    <div class="my-2 img-icon"></div>
                                </div>
                            </div>

                            {{-- Cor --}}
                            <div class="form-row my-1 cor_check_form d-none">
                                <div class="form-group col-12">
                                    <label>Cor da variação</label>

                                    <div class="input-group my-colorpicker2">
                                        <input type="text" name="color" class="form-control">

                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-square"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovoAtributo" data-save_route="{{route('novoAtributo')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarAtributo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarAtributo">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="parent_id" value="@if($id){{$id}}@endif">
                    <div class="modal-header">
                        <h4 class="modal-title">Atualizar Atributo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">Nome do Atributo</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome do Atributo">
                            </div>
                        </div>
                        @if ($id !== null)
                            <div class="form-row my-1">
                                <div class="form-group col-12">
                                    <h5>Imagem ou Cor?</h5>
                                </div>
                                <div class="form-group col-6">
                                    <div class="icheck-primary">
                                        <input type="radio" id="imagem_check_edit" name="attribute_check" value="image">
                                        <label for="imagem_check_edit">Imagem</label>
                                    </div>
                                    
                                </div>
                                <div class="form-group col-6">
                                    <div class="icheck-primary">
                                        <input type="radio" id="cor_check_edit" name="attribute_check" value="color">
                                        <label for="cor_check_edit">Cor</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Imagem --}}
                            <div class="form-row my-1 imagem_check_form d-none">
                                <div class="form-group col-12">
                                    <div class="custom-file">
                                        <input name="img_icon" type="file" class="custom-file-input img_icon">
                                        <label class="custom-file-label" for="img_icon">Imagem Pequena (45x45)</label>
                                    </div>
    
                                    <div class="my-2 img-icon"></div>
                                </div>
                            </div>

                            {{-- Cor --}}
                            <div class="form-row my-1 cor_check_form d-none">
                                <div class="form-group col-12">
                                    <label>Cor da variação</label>

                                    <div class="input-group my-colorpicker2">
                                        <input type="text" name="color" class="form-control hexadecimal">

                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-square"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarAtributo" data-save_route="{{route('atualizarAtributo')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirAtributo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postApagarAtributo">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="_name"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Tem certeza que gostaria de apagar essa variação do atributo?</h5>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-salvar" data-trash="S" data-save_target="#postApagarAtributo" data-save_route="{{route('apagarAtributo')}}"><i class="fas fa-trash"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection