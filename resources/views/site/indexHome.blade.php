@extends('layouts.site')

@section('container')
    {{-- Banner --}}
    <div class="banner">
        <div class="banner-principal">
            <img class="img-fluid" src="{{asset('imgs/BANNER.jpg')}}" alt="Banner">
        </div>
    </div>

    {{-- Sub Banner --}}
    {{-- <div class="container sub-banner">
        <div class="row">
            <div class="col-4 text-center banners" style="background-color: #67233a;">
                <div>
                    <i class="fas fa-truck"></i>
                    <span>DESPACHAMOS PARA TODO BRASIL</span>
                    <span>CONSULTE OS VALORES</span>
                </div>
            </div>
            <div class="col-4 text-center banners" style="background-color: #d38e53;">
                <div>
                    <i class="fas fa-leaf"></i>
                    <span>PRODUTOS PRESERVADOS</span>
                </div>
            </div>
            <div class="col-4 text-center banners" style="background-color: #406261;">
                <div>
                    <i class="fas fa-seedling"></i>
                    <span>CONTROLE NA EMISSÂO DE POLUENTES</span>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Produtos --}}
    <div class="container produto">
        <div class="text-center">
            <h1 class="titulo-principal">CONFIRA NOSSAS NOVIDADES</h1>
        </div>

        {{-- Listas de Produtos --}}
        <div class="row py-2">
            @forelse ($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 text-center mb-5 d-flex flex-column">
                    <div class="img-produto text-center py-1">
                        @php
                            // Pegamos somente a primeira imagem a ser a principal
                            $image      = Storage::get($product->productImage[0]->image_name);
                            $mime_type  = Storage::mimeType($product->productImage[0]->image_name);
                            $image      = 'data:'.$mime_type.';base64,'.base64_encode($image);
                        @endphp
                        <img src="{{$image}}" alt="Imagem do Produto">
                    </div>
                    <div class="mt-auto">
                        <div class="titulo-produto">
                            <h5>{{mb_convert_case($product->name, MB_CASE_UPPER)}}</h5>
                        </div>
                        <div class="star-produto">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        @php
                            $getPricePromotion = getPricePromotion($product->id, $product->value, $product->productCategory);
                        @endphp
                        @if ($getPricePromotion)
                            <div class="preco-promocao">
                                <div class="porcent"><span>{{$getPricePromotion['porcent']}}% OFF</span></div>
                                <div class="values"><span class="value-1">R$ {{number_format($product->value, 2, ',', '.')}}</span> <span class="value-2">R$ {{number_format($getPricePromotion['value'], 2, ',', '.')}} / {{mb_convert_case($sales_unit_array[$product->sales_unit], MB_CASE_LOWER)}}</span></div>
                            </div>
                        @else
                            <div class="preco-produto">
                                R$ {{number_format($product->value, 2, ',', '.')}} / {{mb_convert_case($sales_unit_array[$product->sales_unit], MB_CASE_LOWER)}}
                            </div>
                        @endif
                        <div class="botao-produto pt-3">
                            <a href="{{asset('produto/'.$product->slug)}}"><i class="fas fa-shopping-cart"></i> COMPRAR</a>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
@endsection
