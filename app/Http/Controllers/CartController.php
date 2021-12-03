<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart as CartModel;
use Cart;

class CartController extends Controller
{
    public function cartAdd(Request $request)
    {
        $row_id = 0;
        foreach(Cart::getContent() as $cart_content){
            $row_id = $cart_content->id;
        }

        $cart['id']         = ($row_id+1);
        $cart['name']       = $request->originalName;
        $cart['price']      = $request->customValue;
        $cart['quantity']   = $request->qty_total;
        $cart['attributes'] = [
            'product_id'            => $request->originalId,
            'has_preparation'       => $request->hasPreparation,
            'preparation_time'      => $request->preparationTime,
            'product_value'         => $request->originalValue,
            'product_p_value'       => $request->promotionValue,
            'product_p_porcent'     => $request->promotionPorcent,
            'product_promotion'     => $request->promotion,
            'product_image'         => $request->productImage,
            'product_weight'        => $request->productWeight,
            'product_height'        => $request->productHeight,
            'product_width'         => $request->productWidth,
            'product_length'        => $request->ProductLength,
            'product_sales_unit'    => $request->originalSalesUnit,
            'project_value'         => $request->customProjectValue,
            'project_width'         => $request->customProjectWidth,
            'project_height'        => $request->customProjectHeight,
            'project_meters'        => $request->customProjectMeters,
            'attributes_aux'        => $request->attributes_aux,
            'project'               => $request->project,
            'note'                  => $request->note,
        ];

        if(auth()->check()){
            unset($cart['id']);
            $cart['row_id'] = ($row_id+1);
            $cart['user_id'] = auth()->user()->id;
            $cart['active'] = 'S';

            CartModel::create($cart);
        }else{
            Cart::add($cart);
        }

        return response()->json([$cart], 200);
    }

    public function cartRemove(Request $request)
    {
        if(auth()->check()){
            CartModel::where('user_id', auth()->user()->id)->where('row_id', $request->row_id)->delete();
        }else{
            Cart::remove($request->row_id);
        }
    }
}