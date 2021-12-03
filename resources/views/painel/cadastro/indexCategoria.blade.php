@extends('layouts.painel')

@section('container')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Categorias / Menus</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">@if ($id) <a href="{{asset('/admin/cadastro/categoria_menu')}}">Categorias</a> @else Categorias @endif</li>
                        @if ($id) <li class="breadcrumb-item active">{{$category_name}}</li> @endif
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
                            <h3 class="card-title">Categoria @if ($id) <strong>{{$category_name}}</strong> @else Principal @endif</h3>
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
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#novaCategoria"><i class="fas fa-plus"></i> Nova Categoria</button>
                                    </div>
                                    <div class="col-6 text-right">
                                        @if ($id)
                                            <a class="btn btn-default btn-sm" href="{{asset('admin/cadastro/categoria_menu')}}"><i class="fas fa-arrow-left"></i> Voltar</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="container mt-2 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nome</th>
                                            <th>Slug</th>
                                            @if ($id == null) <th>Sub Categorias</th> @endif
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category)
                                            <tr class="tr-id-{{$category->id}}">
                                                <td>{{$category->id}}</td>
                                                <td>{{$category->name}}</td>
                                                <td>{{$category->slug}}</td>
                                                @if ($category->parent_id == null) <td>{{$category->subCategories->count()}}</td> @endif
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="">
                                                        @if ($category->parent_id == null)
                                                            <a href="{{url('admin/cadastro/categoria_menu', $category->id)}}" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Vizualizar</a>
                                                        @endif
    
                                                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCategoria" data-dados="{{json_encode($category)}}"><i class="fas fa-edit"></i> Alterar</a>
                    
                                                        <a href="#" class="btn btn-danger btn-xs btn-excluir-categoria" data-toggle="modal" data-target="#excluirCategoria" data-dados="{{json_encode($category)}}"><i class="fas fa-trash"></i> Apagar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <th colspan="5">{{$categories->count()}} Categorias</th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="novaCategoria">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postNovaCategoria">
                    @csrf
                    <input type="hidden" name="parent_id" value="@if($id){{$id}}@endif">
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
                                <input type="text" name="category_name" class="form-control" placeholder="Nome da Categoria">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postNovaCategoria" data-save_route="{{route('novaCategoria')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarCategoria">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postEditarCategoria">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Atualizar Categoria</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="category_name">Nome da Categoria</label>
                                <input type="text" name="category_name" class="form-control name" placeholder="Nome da Categoria">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-salvar" data-update_table="S" data-save_target="#postEditarCategoria" data-save_route="{{route('atualizarCategoria')}}"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excluirCategoria">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="postExcluirCategoria">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-danger btn-confirma-exclusao-categoria d-none"><i class="fas fa-save"></i> Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection