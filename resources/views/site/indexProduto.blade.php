@extends('layouts.site')

@php
    $images = []; // Criamos o 
    $getPricePromotion = getPricePromotion($product->id, $product->value, $product->productCategory);
@endphp
{{-- Lemos as imagems do produto para converter em base64 --}}
@forelse ($product->productImage as $image)
    @php
        // Pegamos somente a primeira imagem a ser a principal
        $img        = Storage::get($image->image_name);
        $mime_type  = Storage::mimeType($image->image_name);
        $images[]   = 'data:'.$mime_type.';base64,'.base64_encode($img);
    @endphp
@empty
    @php
        $img        = Storage::get(asset('imgs/PRODUTO-01.jpg'));
        $mime_type  = Storage::mimeType(asset('imgs/PRODUTO-01.jpg'));
        $images[]   = 'data:'.$mime_type.';base64,'.base64_encode($img);
    @endphp
@endforelse

@section('container')
    <input type="hidden" id="originalId" value="{{$product->id}}">
    <input type="hidden" id="originalValue" value="{{$product->value}}">
    <input type="hidden" id="promotion" value="{{$getPricePromotion ? 'S' : 'N'}}">
    <input type="hidden" id="promotionValue" value="{{$getPricePromotion['value'] ?? 0}}">
    <input type="hidden" id="promotionPorcent" value="{{$getPricePromotion['porcent'] ?? 0}}">
    <input type="hidden" id="originalName" value="{{$product->name}}">
    <input type="hidden" id="hasPreparation" value="{{$product->has_preparation}}">
    <input type="hidden" id="preparationTime" value="{{$product->preparation_time}}">
    <input type="hidden" id="productImage" value="{{$product->productImage[0]->image_name}}">
    <input type="hidden" id="productWeight" value="{{$product->weight}}">
    <input type="hidden" id="productHeight" value="{{$product->height}}">
    <input type="hidden" id="productWidth" value="{{$product->width}}">
    <input type="hidden" id="ProductLength" value="{{$product->length}}">
    <input type="hidden" id="originalSalesUnit" value="{{$sales_unit_array[$product->sales_unit]}}">
    <input type="hidden" id="customProjectValue" value="{{$product->sales_unit == 'P' ? $product->value : ''}}">
    <input type="hidden" id="customProjectWidth">
    <input type="hidden" id="customProjectHeight">
    <input type="hidden" id="customProjectMeters">
    <input type="hidden" id="customValue" value="{{$product->sales_unit == 'P' ? ($getPricePromotion['value'] ?? $product->value) : ''}}">

    <div class="container my-5">
        <div class="row product-detail">
            <div class="col-12 col-lg-5">
                <div class="imagem-produto-1 mb-2">
                    @foreach ($images as $image)
                        <div><img class="img-fluid mx-auto" src="{{$image}}" alt="Imagen do produto"></div>
                    @endforeach
                </div>

                <div class="imagem-produto-2">
                    @foreach ($images as $image)
                        <div class="px-2"><img class="img-fluid" src="{{$image}}" alt="Imagen do produto"></div>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <div class="row">
                    <div class="col-12">
                        <h1>{{mb_convert_case($product->name, MB_CASE_UPPER)}}</h1>
                    </div>
                    <div class="col-12">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>

                    <div class="line"></div>

                    <div class="col-12">
                        <div class="value-title"><span>Valor em {{mb_convert_case($sales_unit_array[$product->sales_unit], MB_CASE_LOWER)}}</span></div>
                        @php
                            
                        @endphp
                        @if ($getPricePromotion)
                            <div class="preco-promocao">
                                <div class="porcent"><span>{{$getPricePromotion['porcent']}}% OFF</span></div>
                                <div class="values"><span class="value-1">R$ {{number_format($product->value, 2, ',', '.')}}</span> <span class="value-2">R$ {{number_format($getPricePromotion['value'], 2, ',', '.')}} / {{mb_convert_case($sales_unit_array[$product->sales_unit], MB_CASE_LOWER)}}</span></div>
                            </div>
                        @else
                            <div class="value"><span>R$ {{number_format($product->value, 2, ',', '.')}} / {{mb_convert_case($sales_unit_array[$product->sales_unit], MB_CASE_LOWER)}}</span></div>
                        @endif
                    </div>

                    {{-- Atributos --}}
                    @php
                        $productAttributes = attribute_select($product->productAttribute);
                    @endphp
                    <div class="col-12 my-2">
                        @foreach ($productAttributes->attribute_p as $productAttribute)
                            <div class="d-flex calc-title"><h5 class="h-title">{{$productAttribute->name}}</h5></div>
                            <div class="row attributes">
                                @foreach ($productAttributes->attribute_s->{$productAttribute->parent_id} as $attribute)
                                    <div class="col-2">

                                        <input type="hidden" class="select-data-attribute" value="{{$attribute->attribute_id ?? ''}}|{{$attribute->attribute_name ?? ''}}|{{$attribute->attribute_value ?? ''}}">
                                        
                                        @if (!$attribute->attribute_id)
                                            <div class="select-attribute" title="Sem Opção">
                                                <div class="select-attribute-bg"></div>
                                            </div>
                                            <div class="select-opcao">Sem Opção</div>
                                        @endif

                                        @if ($attribute->image)
                                            @php
                                                // Pegamos somente a primeira imagem a ser a principal
                                                $image      = Storage::get($attribute->image);
                                                $mime_type  = Storage::mimeType($attribute->image);
                                                $image      = 'data:'.$mime_type.';base64,'.base64_encode($image);
                                            @endphp
                                            <div class="select-attribute" title="{{$attribute->attribute_name}}">
                                                <div class="select-attribute-bg"><img width="45px" src="{{$image}}" alt=""></div>
                                            </div>
                                            <div class="select-opcao">{{$attribute->attribute_name}}</div>
                                            @if ($attribute->attribute_value)
                                                <div class="select-opcao">R$ {{$attribute->attribute_value}}</div>
                                            @endif
                                        @endif
                                        
                                        @if ($attribute->hexadecimal)
                                            <div class="select-attribute" title="{{$attribute->attribute_name}}">
                                                <div class="select-attribute-bg" style="background-color: {{$attribute->hexadecimal}};"></div>
                                            </div>
                                            <div class="select-opcao">{{$attribute->attribute_name}}</div>
                                            @if ($attribute->attribute_value)
                                                <div class="select-opcao">R$ {{number_format($attribute->attribute_value, 2, ',','')}}</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    {{-- Calculo para metros lineares --}}
                    @if ($product->sales_unit == 'M')
                        <div class="col-12"><hr /></div>
                        <div class="col-12 my-2">
                            <div class="row">
                                <div class="form-group col-12">
                                    <h5>Calcular em Metros ou Centímetros</h5>
                                </div>
                                <div class="form-group col-6">
                                    <div class="icheck-primary">
                                        <input type="radio" id="meters_check" name="prodcuct_project_linear" value="meters">
                                        <label for="meters_check">Metro</label>
                                    </div>
                                    
                                </div>
                                <div class="form-group col-6">
                                    <div class="icheck-primary">
                                        <input type="radio" id="centimetro_check" name="prodcuct_project_linear" value="centimeters">
                                        <label for="centimetro_check">Centímetro</label>
                                    </div>
                                </div>
                            </div>

                            <div class="project-campo d-none">
                                <div class="row py-1">
                                    <div class="col-2"><label for="">Tamanho*</label></div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control linear-meters">
                
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-default-qty btn-calc-meters">Calcular</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 tela-projeto"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12"><hr /></div>
                    @endif

                    {{-- Para calculo em metros qudrados --}}
                    @if ($product->sales_unit == 'MQ')
                        <div class="col-12 my-2">
                            <div class="d-flex calc-title"><h5 class="h-title">Calcule seu projeto: <span class="ml-2"><i class="fas fa-question-circle"></i></span></h5></div>

                            <div class="row mt-2">
                                <div class="col-4"><button type="button" class="btn btn-default-product" data-toggle="modal" data-target="#modalProject">CALCULAR</button></div>
                                <div class="col-8 tela-projeto"></div>
                            </div>
                        </div>
                    @endif

                    <div class="col-12 my-2">
                        <div class="row">
                            <div class="col-12"><h5>{{$product->sales_unit == 'P' ? 'VALOR FINAL:' : 'VALOR FINAL DO SEU PROJETO SOB MEDIDA:'}} <span class="valor-final"></span></h5></div>
                        </div>
                    </div>

                    {{-- Qunatidade do produto --}}
                    <div class="col-12 my-2">
                        <label for="">Quantidade:</label>
                        <div class="input-group" style="width: 20%;">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-default-qty qty_plus"><i class="fa fa-plus"></i></button>
                            </div>

                            <input type="number" min="1" class="form-control text-center qty_total" readonly value="1">

                            <div class="input-group-append">
                                <button type="button" class="btn btn-default-qty qty_minus"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="form-grup col-12">
                        <label for="product_ob">Observação:</label>
                        <textarea id="product_ob" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-12 my-2 text-center">
                        <button type="button" class="btn btn-default-product" id="comprar_produto" disabled><i class="fas fa-shopping-cart"></i> COMPRAR</button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                {!! $product->description !!}
            </div>
        </div>
    </div>

    @if ($product->sales_unit == 'MQ')
        <div class="modal fade" id="modalProject">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Calcule seu Projeto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <h5>Calcular em Metros ou Centímetros</h5>
                            </div>
                            <div class="form-group col-6">
                                <div class="icheck-primary">
                                    <input type="radio" id="meters_check" name="prodcuct_project" value="meters">
                                    <label for="meters_check">Metro</label>
                                </div>
                                
                            </div>
                            <div class="form-group col-6">
                                <div class="icheck-primary">
                                    <input type="radio" id="centimetro_check" name="prodcuct_project" value="centimeters">
                                    <label for="centimetro_check">Centímetro</label>
                                </div>
                            </div>
                        </div>

                        <div class="row project-campo-titulo d-none">
                            <div class="col-3">Remover</div>
                            <div class="col-3">Largura*</div>
                            <div class="col-3">Altura*</div>
                            <div class="col-3">M²</div>
                        </div>

                        <div class="project-campo d-none">
                            <div class="row py-1">
                                <input type="hidden" class="customModuloProject">
                                <div class="col-3">
                                    <button type="button" class="btn btn-danger btn-sm remove-project-calc"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-sm project-width">
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-sm project-height">
                                </div>
                                <div class="col-3 text-square"></div>
                            </div>
                        </div>

                        <div class="row mt-3 btn-add-project-campo d-none">
                            <div class="col-12"><button type="button" class="btn btn-primary btn-sm btn-add-project-calc">Adicionar Modulo</button></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="button" class="btn btn-success btn-calc-project"><i class="fas fa-calculator"></i> Calcular</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection