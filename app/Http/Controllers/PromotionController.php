<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function novaPromocao(Request $request)
    {
        $request->validate([
            'product_category'  => 'required',
            'value'             => 'required|string',
            'start_end_date'    => 'required|string',
            'products'          => 'required_without:categories',
            'categories'        => 'required_without:products',
            'main_category'     => 'required_without_all:sub_category,products',
            'sub_category'      => 'required_without_all:main_category,products',
        ]);

        $dates = explode('-', $request->start_end_date);
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[0]))));
        $final_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[1]))));

        $promotions['value']        = $request->value;
        $promotions['start_date']   = $start_date;
        $promotions['final_date']   = $final_date;

        if($request->product_category == 'product'){
            $promotions['category'] = 'N';

            foreach($request->products as $products){
                $promotions['identifier'] = $products;

                Promotion::create($promotions);
            }
        }

        if($request->product_category == 'category'){
            if($request->categories == 'main_category'){
                $promotions['category'] = 'S';

                foreach($request->main_category as $main_category){
                    $promotions['identifier'] = $main_category;

                    Promotion::create($promotions);
                }
            }

            if($request->categories == 'sub_category'){
                $promotions['category'] = 'S';

                foreach($request->sub_category as $sub_category){
                    $promotions['identifier'] = $sub_category;

                    Promotion::create($promotions);
                }
            }
        }

        return response()->json();
    }

    public function atualizarPromocao(Request $request)
    {
        $dates = explode('-', $request->start_end_date);
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[0]))));
        $final_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[1]))));

        $promotions = Promotion::where('id', $request->id)->update([
            'value'         => $request->value,
            'start_date'    => $start_date,
            'final_date'    => $final_date
        ]);

        return response()->json([
            'promotion_id'  => $request->id,
            'value'         => $request->value.'% OFF',
            'start_date'    => trim($dates[0]),
            'final_date'    => trim($dates[1]),
            'dados'         => Promotion::where('id', $request->id)->first()
        ]);
    }

    public function apagarPromocao(Request $request)
    {
        $promotion = Promotion::where('id', $request->promotion_id)->delete();

        return response()->json(['promotion_id' => $request->promotion_id]);
    }
}
