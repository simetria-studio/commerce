<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponHistory;
use App\Models\AffiliateBank;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function novoCupom(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string',
            'code'                  => 'required|string|unique:coupons',
            'start_end_date'        => 'required|string',
            'discount_type'         => 'required|string',
            'value'                 => 'required|string',
            'discount_accepted'     => 'required',
            'installemnts'          => 'required|string',
        ]);

        $dates = explode('-', $request->start_end_date);
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[0]))));
        $final_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[1]))));

        $coupons['name']                = $request->name;
        $coupons['code']                = mb_convert_case($request->code, MB_CASE_UPPER);
        $coupons['discount_type']       = $request->discount_type;
        $coupons['start_date']          = $start_date;
        $coupons['final_date']          = $final_date;
        $coupons['value']               = $request->value;
        $coupons['min_value']           = $request->min_value ? $request->min_value : null;
        $coupons['max_value']           = $request->max_value ? $request->max_value : null;
        $coupons['discount_accepted']   = json_encode($request->discount_accepted);
        $coupons['installemnts']        = $request->installemnts;
        $coupons['user_id']             = $request->user_id ? json_encode($request->user_id) : null;

        $coupon = Coupon::create($coupons);

        return response()->json([
            'table' => '<tr class="tr-id-'.$coupon->id.'">
                <td>'.$coupon->id.'</td>
                <td>'.$coupon->name.'</td>
                <td>'.$coupon->code.'</td>
                <td>'.($coupon->value.($coupon->discount_type == 'P' ? '%' : 'R$')).'</td>
                <td>'.date('d/m/Y', strtotime(str_replace('-','/',  $coupon->start_date))).'</td>
                <td>'.date('d/m/Y', strtotime(str_replace('-','/',  $coupon->final_date))).'</td>
                <td>'.$coupon->min_value.' R$ | '.$coupon->max_value.' R$</td>
                <td>'.implode(', ', json_decode($coupon->discount_accepted)).'</td>
                <td>'.$coupon->installemnts.'</td>
                <td>'.count(json_decode($coupon->user_id)).'</td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCupom" data-dados="\''.json_encode($coupon).'\'"><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirCupom" data-dados="\''.json_encode($coupon).'\'"><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>
            </tr>'
        ]);
    }

    public function atualizarCupom(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string',
            'code'                  => 'required|string|unique:coupons,code,'.$request->id,
            'start_end_date'        => 'required|string',
            'discount_type'         => 'required|string',
            'value'                 => 'required|string',
            'discount_accepted'     => 'required',
            'installemnts'          => 'required|string',
        ]);

        $dates = explode('-', $request->start_end_date);
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[0]))));
        $final_date = date('Y-m-d', strtotime(str_replace('/','-',trim($dates[1]))));

        $coupons['name']                = $request->name;
        $coupons['code']                = mb_convert_case($request->code, MB_CASE_UPPER);
        $coupons['discount_type']       = $request->discount_type;
        $coupons['start_date']          = $start_date;
        $coupons['final_date']          = $final_date;
        $coupons['value']               = $request->value;
        $coupons['min_value']           = $request->min_value ? $request->min_value : null;
        $coupons['max_value']           = $request->max_value ? $request->max_value : null;
        $coupons['discount_accepted']   = json_encode($request->discount_accepted);
        $coupons['installemnts']        = $request->installemnts;
        $coupons['user_id']             = $request->user_id ? json_encode($request->user_id) : null;

        Coupon::where('id', $request->id)->update($coupons);
        $coupon = Coupon::where('id', $request->id)->first();

        return response()->json([
            'tb_id' => $coupon->id,
            'tb_up' => '
                <td>'.$coupon->id.'</td>
                <td>'.$coupon->name.'</td>
                <td>'.$coupon->code.'</td>
                <td>'.($coupon->value.($coupon->discount_type == 'P' ? '%' : 'R$')).'</td>
                <td>'.date('d/m/Y', strtotime(str_replace('-','/',  $coupon->start_date))).'</td>
                <td>'.date('d/m/Y', strtotime(str_replace('-','/',  $coupon->final_date))).'</td>
                <td>'.$coupon->min_value.' R$ | '.$coupon->max_value.' R$</td>
                <td>'.implode(',', json_decode($coupon->discount_accepted)).'</td>
                <td>'.$coupon->installemnts.'</td>
                <td>'.implode(',', json_decode($coupon->user_id)).'</td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCupom" data-dados="\''.json_encode($coupon).'\'"><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirCupom" data-dados="\''.json_encode($coupon).'\'"><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>'
        ]);
    }

    public function excluirCupom(Request $request)
    {
        Coupon::where('id', $request->id)->delete();

        return response()->json([
            'tb_trash' => $request->id
        ]);
    }

    public function appCoupon(Request $request)
    {
        session()->forget('coupon');

        $coupon = Coupon::where('code', $request->coupon)->first();
        if($coupon == null){
            return response()->json(['erro' => 'Cupom invalido!'], 412);
        }

        if(date('Y-m-d', strtotime($coupon->start_date)) <= date('Y-m-d') && date('Y-m-d', strtotime($coupon->final_date)) >= date('Y-m-d')){
            if($coupon->min_value && $coupon->max_value){
                if(cart_show()->total <= (float)$coupon->min_value && cart_show()->total >= (float)$coupon->max_value){
                    return response()->json(['erro' => 'Os valores devem estar entre '.$coupon->min_value.' á '.$coupon->max_value], 412);
                }
            }else if($coupon->min_value){
                if(cart_show()->total <= (float)$coupon->min_value){
                    return response()->json(['erro' => 'O valor deve estar acima de '.$coupon->min_value], 412);
                }
            }else if($coupon->max_value){
                if(cart_show()->total <= (float)$coupon->max_value){
                    return response()->json(['erro' => 'O valor deve estar abaixo de '.$coupon->max_value], 412);
                }
            }

            if($coupon->discount_type == 'P'){
                $desconto = ((cart_show()->total*$coupon->value)/100);
                $valor = (cart_show()->total-((cart_show()->total*$coupon->value)/100));
            }else{
                $desconto = $coupon->value;
                $valor = (cart_show()->total-$coupon->value);
            }

            $dados = [
                'coupon' => $coupon->code,
                'value' => $valor,
                'desconto' => $desconto
            ];

            session(['coupon'=>$dados]);

            return  response()->json($dados);
        }else{
            return response()->json(['erro' => 'Cupom vencido!'], 412);
        }
    }

    public function pagarAfiliado(Request $request)
    {
        $bank = AffiliateBank::where('user_id', $request->id)->first();
        AffiliateBank::where('user_id', $request->id)->update([
            'balance_available' => $bank->balance_available-(str_replace(['.',','], ['','.'], $request->value)),
            'balance_withdrawn' => $bank->balance_withdrawn+(str_replace(['.',','], ['','.'], $request->value))
        ]);

        CouponHistory::create([
            'user_id' => $request->id,
            'type' => 'Valor Transferido',
            'history' => 'Feito transferencia no valor de R$ '.$request->value,
        ]);

        return response()->json();
    }
}
