<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\Product;
use App\Models\Category;
use App\Models\Address;
use App\Models\ShippingCompany;
use App\Models\TransportValue;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingCustomer;
use App\Models\PaymentOrder;
use App\Models\CouponHistory;
use App\Models\AffiliateBank;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    public $sales_unit_array = [
        'P' => 'UN',
        'M' => 'M',
        'MQ' => 'M²'
    ];

    public function cepConsulta($cep)
    {
        function consultaCep($cep){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$cep/json/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, FALSE);

            $response = json_decode(curl_exec($ch), true);
            curl_close($ch);

            return $response;
        }

        return response()->json(consultaCep($cep));
    }

    public function indexFaleConosco()
    {
        return view('site.indexFaleConosco');
    }

    public function indexCarrinho()
    {
        return view('site.indexCarrinho');
    }

    public function indexPesquisa()
    {
        $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
        // $products = Product::where('slug', $slug)->where('status', '1')->with(['productImage', 'productCategory', 'promotionP' => function($query){
        //     $query->where('category', 'N')->where('final_date', '>=', date('Y-m-d'))->where('active', 'S');
        // }])->get();
        $products = Product::where('name', 'like', "%$q%")->where('status', '1')->with(['productImage', 'productCategory'])->get();
        if(empty($q)){
            $products = [];
        }
        $sales_unit_array = $this->sales_unit_array;

        return view('site.indexPesquisa', compact('products', 'sales_unit_array', 'q'));
    }

    public function indexHome()
    {
        // $products = Product::where('slug', $slug)->where('status', '1')->with(['productImage', 'productCategory', 'promotionP' => function($query){
        //     $query->where('category', 'N')->where('final_date', '>=', date('Y-m-d'))->where('active', 'S');
        // }])->get();
        $products = Product::where('status', '1')->with(['productImage', 'productCategory'])->get();
        $sales_unit_array = $this->sales_unit_array;

        return view('site.indexHome', compact('products', 'sales_unit_array'));
    }

    public function indexProduto($slug)
    {
        // $products = Product::where('slug', $slug)->where('status', '1')->with(['productImage', 'productCategory', 'promotionP' => function($query){
        //     $query->where('category', 'N')->where('final_date', '>=', date('Y-m-d'))->where('active', 'S');
        // }])->get();
        $product = Product::where('slug', $slug)->where('status', '1')->with(['productImage', 'productCategory', 'productAttribute'])->first();
        $sales_unit_array = $this->sales_unit_array;

        return view('site.indexProduto', compact('product', 'sales_unit_array'));
    }

    public function indexCategoria($slug)
    {
        $category = Category::where('slug', $slug)->with(['subCategories', 'products'])->first();
        if($category->parent_id){
            $subCategory_parent = Category::where('id', $category->parent_id)->with(['subCategories'])->first();
        }

        $subCategory_parent = $subCategory_parent->subCategories ?? $category->subCategories;
        $sales_unit_array = $this->sales_unit_array;

        return view('site.indexCategoria', compact('category', 'subCategory_parent', 'sales_unit_array'));
    }


    public function indexPedidos()
    {
        $orders = Order::where('user_id', auth()->user()->id)->where('active', 'S')->where('pay', 'Sim')->orderBy('order_number', 'DESC')->with(['orderProducts', 'shippingCustomer', 'paymentOrder'])->paginate(10);

        return view('site.indexPedidos', compact('orders'));
    }

    public function conta()
    {
        $user = User::with(['bank'])->find(auth()->user()->id);
        $histories = CouponHistory::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        return view('site.conta', compact('user', 'histories'));
    }

    // ######################### //
    public function indexPagamento($url = null)
    {
        $addresses = [];
        $address = [];
        $transportadoras = [];
        $preparation_time_final = 0;
        switch($url){
            case 'dados':
            break;
            case 'enderecos':
                $addresses = Address::where('user_id', auth()->user()->id)->get();
            break;
            case 'transportes':
                session()->forget('coupon');

                if(empty(session('address_id'))) return redirect('/pagamento/enderecos');
                $address_id = session('address_id');
                $address = Address::where('id', $address_id)->first();

                // pegando os dados do shipping
                $shippings = ShippingCompany::with(['transportValues' => function($query){
                    $address_id = session('address_id');
                    $address = Address::where('id', $address_id)->first();
                    $query->where('state', $address->state)->where('city', $address->city)->orWhere('city', 'Toda Região');
                }])->get();

                // Fazendo uma leitura no cart
                $dados = [];
                foreach(cart_show()->content as $content){
                    if($content->attributes->product_sales_unit == 'M²'){
                        $project_width = 0;
                        $project_height = 0;
                        $project_meters = 0;
                        $project_length = (count($content->attributes->project) * $content->attributes->product_length);
                        $project_weight = ($content->attributes->product_weight * $content->attributes->project_meters);

                        // Para contagem, pegamos o metro quadrado para verificar o tamanho da caixa
                        foreach($content->attributes->project as $project){
                            $project_value = explode('|', $project);

                            if($project_value[0] >= $project_width) $project_width = str_replace('.','', $project_value[0]);
                            if($project_value[1] >= $project_height) $project_height = str_replace('.','', $project_value[1]);
                            if($project_value[2] >= $project_meters) $project_meters = str_replace('.','', $project_value[2]);
                        }

                        $dados[] = [
                            'width'     => $project_width,
                            'height'    => $project_height,
                            'length'    => ($project_length*$content->quantity),
                            'weight'    => ($project_weight*$content->quantity),
                            'has_preparation'    => $content->attributes->has_preparation,
                            'preparation_time'    => $content->attributes->preparation_time,
                        ];
                    }

                    if($content->attributes->product_sales_unit == 'M'){
                        $project_width = $content->attributes->project_meters;
                        $project_height = $content->attributes->product_height;
                        $project_meters = $content->attributes->project_meters;
                        $project_length = $content->attributes->product_length;
                        $project_weight = ($content->attributes->product_weight * ($project_width * $project_height));

                        $dados[] = [
                            'width'             => str_replace('.','',$project_width),
                            'height'            => $project_height,
                            'length'            => ($project_length*$content->quantity),
                            'weight'            => ($project_weight*$content->quantity),
                            'has_preparation'   => $content->attributes->has_preparation,
                            'preparation_time'  => $content->attributes->preparation_time,
                        ];
                    }

                    if($content->attributes->product_sales_unit == 'UN'){
                        $project_width = $content->attributes->product_width;
                        $project_height = $content->attributes->product_height;
                        $project_length = $content->attributes->product_length;
                        $project_weight = $content->attributes->product_weight;

                        $dados[] = [
                            'width'             => $project_width,
                            'height'            => $project_height,
                            'length'            => ($project_length*$content->quantity),
                            'weight'            => ($project_weight*$content->quantity),
                            'has_preparation'   => $content->attributes->has_preparation,
                            'preparation_time'  => $content->attributes->preparation_time,
                        ];
                    }
                }

                $project_length_final = 0;
                $project_weight_final = 0;
                $preparation_time_final = 0;
                foreach($dados as $dados_product){
                    $project_width_final = 0;
                    $project_height_final = 0;
                    $project_length_final += $dados_product['length'];
                    $project_weight_final += $dados_product['weight'];
                    if($dados_product['width'] >= $project_width_final) $project_width_final = $dados_product['width'];
                    if($dados_product['height'] >= $project_height_final) $project_height_final = $dados_product['height'];
                    if($dados_product['preparation_time'] >= $preparation_time_final) $preparation_time_final = $dados_product['preparation_time'];
                }

                $transportadoras = [];
                foreach($shippings as $shipping){
                    foreach($shipping->transportValues as $values){
                        if($project_width_final <= $values->width && $project_height_final <= $values->height && $project_length_final <= $values->length && $project_weight_final <= $values->weight){
                            $transportadoras[] = [
                                'transport'         => $shipping->carrier_name,
                                'price'             => $values->price,
                                'time'              => $values->time
                            ];
                            break;
                        }
                    }
                }
            break;
            case 'finalizar':
                if(empty(session('address_id'))) return redirect('/pagamento/enderecos');
                $address_id = session('address_id');
                $address = Address::where('id', $address_id)->first();
            break;
        }
        return view('site.indexPagamento', compact('url' ,'addresses', 'address', 'transportadoras', 'preparation_time_final'));
    }

    public function atualizarPagamento(Request $request)
    {
        switch($request->type){
            case 'dados':
                User::find(auth()->user()->id)->update([
                    'name'  => $request->name,
                    'cpf'   => $request->cpf,
                    'birth_date' => $request->birth_date ? date('Y-m-d', strtotime(str_replace('/','-', $request->birth_date))) : null,
                ]);

                return redirect('/pagamento/enderecos');
            break;
            case 'enderecos':
                session(['address_id' => $request->address_id]);

                return redirect('/pagamento/transportes');
            break;
            case 'transportes':
                $transport = explode('|', $request->transport);
                $dados = [
                    'carrier_name' => $transport[0],
                    'price' => $transport[1],
                    'time' => $transport[2],
                    'preparation_time' => $request->preparation_time_final,
                ];
                session(['transport' => $dados]);

                return redirect('/pagamento/finalizar');
            break;
        }
    }
    // ######################### //

    ################### Dados da conta #####################
    public function perfil()
    {
        $addresses = Address::where('user_id', auth()->user()->id)->get();

        return view('profile.perfil', compact('addresses'));
    }
    #########################################################

    public function terms_of_service()
    {
        return view('terms');
    }

    public function privacy_policy()
    {
        return view('policy');
    }
}
