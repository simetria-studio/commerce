@extends('layouts.site')

@section('container')
    <div class="container my-5">
        <table id="cart" class="table table-hover table-condensed">
            <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto/Nome</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th> 
                        <th>Ações</th>
                    </tr>
            </thead>
            <tbody>
                    @foreach (cart_show()->content as $cart_content)
                        @php
                            $img        = Storage::get($cart_content->attributes->product_image);
                            $mime_type  = Storage::mimeType($cart_content->attributes->product_image);
                            $image      = 'data:'.$mime_type.';base64,'.base64_encode($img);
                        @endphp
                        <tr>
                            <td>
                                <img width="120px" src="{{$image}}" alt="">
                            </td>
                            <td>{{$cart_content->name}}</td>
                            @if ($cart_content->attributes->product_promotion == 'S')
                                <td>
                                    @php
                                        $product_value = (float)$cart_content->attributes->product_value;
                                        $project_meters = (float)$cart_content->attributes->project_meters;
                                        $project_value = (float)$cart_content->attributes->project_value;
                                        $product_p_value = (float)$cart_content->attributes->product_p_value;
                                        $product_p_porcent = (float)$cart_content->attributes->product_p_porcent;

                                        $originalValue = $project_meters !== 0 ? (($product_value * $project_meters)+($cart_content->price - $project_value)) : ($product_value+($cart_content->price-$product_p_value));
                                    @endphp
                                    <div class="porcent"><span>{{$product_p_porcent}}% OFF</span></div>
                                    <div class="values"><span class="value-1">R$ {{number_format($originalValue, 2, ',', '.')}}</span> <span class="value-2">R$ {{number_format($cart_content->price, 2, ',', '.')}}</span></div>
                                </td>
                            @else
                                <td>R$ {{number_format($cart_content->price, 2, ',', '')}}</td>
                            @endif
                            <td>{{$cart_content->quantity}}</td>
                            <td>R$ {{number_format(($cart_content->quantity * $cart_content->price) , 2, ',', '.')}}</td>
                            <td><button type="button" class="btn btn-danger btn-delete-product" data-row_id="{{$cart_content->row_id}}" data-repagina="sim"><i class="fas fa-trash"></i> Apagar</button></td>
                        </tr>
                    @endforeach
            </tbody>
            <tfoot>
                    <tr>
                        <td class="text-center" colspan="6"><strong>Total: R$ {{number_format(cart_show()->total, 2, ',', '')}}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"><a href="{{asset('/')}}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Comprando</a></td>
                        <td colspan="3"><a href="{{asset('pagamento/dados')}}" class="btn btn-success btn-block">Finalizar Compra <i class="fa fa-angle-right"></i></a></td>
                    </tr>
            </tfoot>
        </table>
    </div>
@endsection