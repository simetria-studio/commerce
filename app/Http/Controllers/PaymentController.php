<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingCustomer;
use App\Models\PaymentOrder;
use App\Models\Coupon;
use App\Models\UsedCoupon;
use App\Models\CouponHistory;
use App\Models\AffiliateBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use App\Mail\Orders;

class PaymentController extends Controller
{
    public function finalizarPagamento(Request $request)
    {
        // dd($request->all());
        $address_id = session('address_id');
        $address = Address::where('id', $address_id)->first();
        $transport = session('transport');

        if(empty(session()->get('order_number'))){
            $order_number = Order::max('order_number');
            $order_number = str_pad(($order_number+1), 8, "0", STR_PAD_LEFT);
            session(['order_number' => $order_number]);

            // Criando o pedido
            $order = Order::create([
                'order_number' => $order_number,
                'user_id' => auth()->user()->id,
                'user_name' => auth()->user()->name,
                'user_email' => auth()->user()->email,
                'user_cnpj_cpf'=> auth()->user()->cnpj_cpf,
                'birth_date' => auth()->user()->birth_date,
                'total_value' => ((session()->get('coupon') ? session()->get('coupon')['value'] : cart_show()->total)+$transport['price']),
                'cost_freight' => $transport['price'],
                'product_value' => cart_show()->total,
                'discount' => (cart_show()->original_value - cart_show()->total),
                'coupon_value' => session()->get('coupon')['desconto'],
                'coupon' => session()->get('coupon')['coupon'],
                'pay' => 'Não'
            ]);

            if(session()->get('coupon')){
                $coupon = Coupon::where('code', session()->get('coupon')['coupon'])->first();
                UsedCoupon::create([
                    'order_id' => $order_number,
                    'name' => $coupon->name,
                    'coupon' => $coupon->code,
                    'discount' => ($coupon->discount_type == 'P' ? 'R$ ' : '% ').$coupon->value,
                    'start_date' => $coupon->start_date,
                    'final_date' => $coupon->final_date
                ]);
            }

            // Criando os produtos do pedido
            foreach(cart_show()->content as $content){
                $product = Product::where('id', $content->attributes->product_id)->first();
                $sequence_order = OrderProduct::where('order_number', $order_number)->max('sequence');
                $sequence_order = ($sequence_order+1);

                $discount = 0;
                if($content->attributes->product_promotion == 'S'){
                    $product_value = (float)$content->attributes->product_value;
                    $project_meters = (float)$content->attributes->project_meters;
                    $project_value = (float)$content->attributes->project_value;
                    $product_p_value = (float)$content->attributes->product_p_value;
                    $product_p_porcent = (float)$content->attributes->product_p_porcent;
        
                    $originalValue = $project_meters !== 0 ? (($product_value * $project_meters)+($content->price - $project_value)) : ($product_value+($content->price - $product_p_value));
                    $discount = $originalValue - $content->price;
                }
    
                $order_product = OrderProduct::create([
                    'order_number' => $order_number,
                    'sequence' => $sequence_order,
                    'product_id' => $content->attributes->product_id,
                    'product_code' => $product->code,
                    'product_name' => $content->name,
                    'product_price' => $content->price,
                    'quantity' => $content->quantity,
                    'has_preparation' => $content->attributes->has_preparation,
                    'preparation_time' => $content->attributes->preparation_time,
                    'product_weight' => $content->attributes->product_weight,
                    'product_height' => $content->attributes->product_height,
                    'product_width' => $content->attributes->product_width,
                    'product_length' => $content->attributes->product_length,
                    'product_sales_unit' => $content->attributes->product_sales_unit,
                    'project_value' => $content->attributes->project_value,
                    'project_width' => $content->attributes->project_width,
                    'project_height' => $content->attributes->project_height,
                    'project_meters' => $content->attributes->project_meters,
                    'attributes' => $content->attributes->attributes_aux,
                    'project' => $content->attributes->project,
                    'discount' => $discount,
                    'note' => $content->attributes->note,
                ]);
            }

            // Criando os dados da entrega
            $shipping_customer = ShippingCustomer::create([
                'order_number' => $order_number,
                'post_code' => $address->post_code,
                'state' => $address->state,
                'city' => $address->city,
                'address2' => $address->address2,
                'address' => $address->address,
                'number' => $address->number,
                'complement' => $address->complement,
                'phone1' => $address->phone1,
                'phone2' => $address->phone2,
                'transport' => $transport['carrier_name'],
                'price' => $transport['price'],
                'time' => $transport['time'],
            ]);
        }else{
            $order_number = session()->get('order_number');
        }

        $dados = [
            'token'                 => $request->token,
            'transaction_amount'    => (float)$request->transaction_amount,
            'issuer_id'             => (int)$request->issuer_id,
            'payment_method_id'     => $request->payment_method_id,
            'installments'          => (int)$request->installments,
            'description'           => $request->description,
            'payer'                 => $request->payer,
            'additional_info'       => [
                'payer'     => [
                    'first_name'    => auth()->user()->name,
                    'phone'         => [
                        'area_code' => (int)str_replace(['(',')'], '', explode(' ', $address->phone2)[0]),
                        'number'    => str_replace('-', '', explode(' ', $address->phone2)[1])
                    ]
                ],
                'shipments' => [
                    'receiver_address' => [
                        'zip_code' => $address->post_code,
                        'state_name' => $address->state,
                        'city_name' => $address->city,
                        'street_name' => $address->adrress,
                        'street_number' => (int)$address->number
                    ]
                ]
            ],
            'external_reference' => $order_number
        ];

        $payment = $this->curl_mp($dados);
        $payment_decode = json_decode($payment);
        $quebra = chr(13).chr(10);

        $fp = fopen("./logs/payment_return.log", "a");
        $escreve = fwrite($fp, '['.date('Y-m-d H:i:s').']-------->>>>>>');
        $escreve = fwrite($fp, $payment.$quebra);
        fclose($fp);

        // Gravando os pagamento caso esteje aceito
        $payemnt_order = PaymentOrder::create([
            'order_number' => $order_number,
            'payment_id' => $payment_decode->id,
            'issuer_id' => $payment_decode->issuer_id,
            'payment_method_id' => $payment_decode->payment_method_id,
            'payment_type_id' => $payment_decode->payment_type_id,
            'status' => $payment_decode->status,
            'status_detail' => $payment_decode->status_detail,
            'currency_id' => $payment_decode->currency_id,
            'collector_id' => $payment_decode->collector_id,
            'net_received_amount' => $payment_decode->transaction_details->net_received_amount,
            'total_paid_amount' => $payment_decode->transaction_details->total_paid_amount,
            'installments' => $payment_decode->installments,
            'installment_amount' => $payment_decode->transaction_details->installment_amount,
            'rate_mp' => $payment_decode->fee_details[0]->amount ?? 0,
            'payer_name' => $payment_decode->card->cardholder->name,
            'payer_cnpj_cpf' => $payment_decode->card->cardholder->identification->number,
        ]);

        if($payment_decode->status == 'approved'){
            // session()->forget('order_number');
            // session()->forget('address_id');
            // session()->forget('transport');
            // session()->forget('coupon');

            Order::where('order_number', $order_number)->update(['pay'=>'Sim']);
            if(session()->get('coupon')){
                $coupon = Coupon::where('code', session()->get('coupon')['coupon'])->first();
                foreach(json_decode($coupon->user_id) as $user_id){
                    $bank = AffiliateBank::where('user_id', $user_id)->first();
                    AffiliateBank::where('user_id', $user_id)->update([
                        'balance_available' => $bank->balance_available+(cart_show()->total-session()->get('coupon')['value']),
                        'accumulated_total' => $bank->accumulated_total+(cart_show()->total-session()->get('coupon')['value'])
                    ]);
    
                    CouponHistory::create([
                        'user_id' => $user_id,
                        'type' => 'Recebimento de Venda',
                        'history' => 'Recebimento de venda feito site',
                        'coupon' => $coupon->code
                    ]);
                }
            }

            // Envio de emails
            Mail::to(auth()->user()->email)->send(new Orders($order_number, 'comprador'));
            Mail::to('ellernetpar@gmail.com')->send(new Orders($order_number, 'vendedor'));

            return response()->json(['approved'], 200);
        }

        return response()->json(['rejected'], 401);
    }

    public function notificaPagamento(Request $request)
    {
        $quebra = chr(13).chr(10);

        $fp = fopen("./logs/receiver_payment_input.log", "a");
        $escreve = fwrite($fp, '['.$data_hora.']-------->>>>>>');
        $escreve = fwrite($fp, file_get_contents('php://input').$quebra);
        fclose($fp);

        $fp = fopen("./logs/receiver_payment_post.log", "a");
        $escreve = fwrite($fp, '['.$data_hora.']-------->>>>>>');
        $escreve = fwrite($fp, $request->all().$quebra);
        fclose($fp);
    }

    public function curl_mp($request)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

        $headers = array();
        $headers[] = 'Authorization: Bearer TEST-1076653505770977-070300-1ddab2fd2c66e94b4eae4a4e1f36103c-129541032';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }
}
