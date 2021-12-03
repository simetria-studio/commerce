<?php

if(!function_exists('getCategories')){
    function getCategories(){
        $categories = App\Models\Category::whereNull('parent_id')->with(['subCategories'])->get();

        return $categories;
    }
}

if(!function_exists('attribute_select')){
    function attribute_select($attributes){
        $array_attribute = [];
        $parent_id = [];
        foreach($attributes as $attribute){
            $parent_id[] = $attribute->parent_id;
            $attribute_sub = App\Models\Attribute::where('id', $attribute->attribute_id)->first();
            $array_attribute[$attribute->parent_id][] = [
                'attribute_id' => $attribute->attribute_id,
                'attribute_value' => $attribute->attribute_value,
                'attribute_name' => $attribute->attribute_name,
                'hexadecimal' => $attribute_sub->hexadecimal ?? '',
                'image' => $attribute_sub->image ?? ''
            ];
        }

        $parent_id = array_unique($parent_id);
        $attribute_names = [];
        foreach($parent_id as $par_id){
            $attribute_sub = App\Models\Attribute::where('id', $par_id)->first();
            $attribute_names[] = [
                'parent_id' => $par_id,
                'name' => $attribute_sub->name
            ];
        }

        return json_decode(json_encode([
            'attribute_p' => $attribute_names,
            'attribute_s' => $array_attribute
        ]));

    }
}

if(!function_exists('cart_show')){
    function cart_show(){
        $cart_contents = Darryldecode\Cart\Facades\CartFacade::getContent();

        $carts = [];
        foreach($cart_contents as $contents){
            $cart['row_id']     = $contents->id;
            $cart['name']       = $contents->name;
            $cart['price']      = $contents->price;
            $cart['quantity']   = $contents->quantity;
            $cart['attributes'] = [
                'product_id'            => $contents->attributes->product_id,
                'has_preparation'       => $contents->attributes->has_preparation,
                'preparation_time'      => $contents->attributes->preparation_time,
                'product_value'         => $contents->attributes->product_value,
                'product_p_value'       => $contents->attributes->product_p_value,
                'product_p_porcent'     => $contents->attributes->product_p_porcent,
                'product_promotion'     => $contents->attributes->product_promotion,
                'product_image'         => $contents->attributes->product_image,
                'product_weight'        => $contents->attributes->product_weight,
                'product_height'        => $contents->attributes->product_height,
                'product_width'         => $contents->attributes->product_width,
                'product_length'        => $contents->attributes->product_length,
                'product_sales_unit'    => $contents->attributes->product_sales_unit,
                'project_value'         => $contents->attributes->project_value,
                'project_width'         => $contents->attributes->project_width,
                'project_height'        => $contents->attributes->project_height,
                'project_meters'        => $contents->attributes->project_meters,
                'attributes_aux'        => $contents->attributes->attributes_aux,
                'project'               => $contents->attributes->project,
                'note'                  => $contents->attributes->note,
            ];
    
            if(auth()->check()){
                $cart['user_id']    = auth()->user()->id;
                $cart['active']     = 'S';
                Darryldecode\Cart\Facades\CartFacade::remove($contents->id);
    
                $cart_row_id = App\Models\Cart::where('user_id', auth()->user()->id)->first([DB::raw('MAX(row_id) as row_id')]);
                $cart['row_id'] = $cart_row_id->row_id+1;
                App\Models\Cart::create($cart);
            }

            $carts[] = $cart;
        }

        $carts = json_decode(json_encode($carts));

        if(auth()->check()){
            $carts = App\Models\Cart::where('user_id', auth()->user()->id)->get();
        }
        
        $carts = json_decode(json_encode($carts));

        $total_cart = 0;
        $quantity_cart = 0;
        $originalValue = 0;
        foreach($carts as $total){
            $total_cart += ($total->price * $total->quantity);
            $quantity_cart += $total->quantity;

            if($total->attributes->product_promotion == 'S'){
                $product_value = (float)$total->attributes->product_value;
                $project_meters = (float)$total->attributes->project_meters;
                $project_value = (float)$total->attributes->project_value;
                $product_p_value = (float)$total->attributes->product_p_value;
                $product_p_porcent = (float)$total->attributes->product_p_porcent;
    
                $originalValue += ($project_meters !== 0 ? (($product_value * $project_meters)+($total->price - $project_value)) : ($product_value+($total->price - $product_p_value)) * $total->quantity);
            }else{
                $originalValue += ($total->price * $total->quantity);
            }
        }

        return json_decode(json_encode(['content' => $carts, 'total' => $total_cart, 'quantidade' => $quantity_cart, 'original_value' => $originalValue]));
    }
}

if(!function_exists('getPricePromotion')){
    function getPricePromotion($product_id, $product_value, $categories){
        ##############################
        /////////////REGRA////////////
        ##O valor da promoção do produto prevale como superior##
        ##O valor da categoria fica como secundario caso o do produto não tenha promoção##
        ##Quando não tiver promoção na catgoria pai ou produto as subcategoria toma o lugar, quando o produto esta em mais d duas categorias o produto pega o o desconto maior##
        ////////////FIM DA REGRA//////
        ##############################
        $promotions = App\Models\Promotion::where('start_date', '<=', date('Y-m-d'))->where('final_date', '>=', date('Y-m-d'))->where('active', 'S')->get();

        $subPromotions = [];
        foreach($promotions as $promotion){
            if($promotion->category == 'N'){ // quando não tem catgoria ou mesmo se tiver categoria o do rpoduto prevalece
                if($promotion->identifier == $product_id){ // Identificando os ids
                    return ['value' => ($product_value - (($product_value * $promotion->value) / 100)), 'porcent' => $promotion->value];
                }
            }else if($promotion->category == 'S'){
                foreach($categories as $category){
                    if($category->category_pai == 'S'){
                        if($promotion->identifier == $category->category_id){
                            return ['value' => ($product_value - (($product_value * $promotion->value) / 100)), 'porcent' => $promotion->value];
                        }
                    }else if($category->category_pai == 'N'){
                        if($promotion->identifier == $category->category_id){
                            $subPromotions[] = $promotion->value;
                        }
                    }
                }
            }
        }

        if(count($subPromotions) > 0){
            $value = max($subPromotions);
            return ['value' => ($product_value - (($product_value * $value) / 100)), 'porcent' => $value];
        }

        return false;
    }
}