<?php

namespace App\Http\Controllers;

use App\Models\ShippingCompany;
use App\Models\TransportValue;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function novaTransportadora(Request $request)
    {
        $request->validate([
            'carrier_name' => 'required|string',
        ]);

        $shipping_company = ShippingCompany::create([
            'carrier_name' => mb_convert_case($request->carrier_name, MB_CASE_UPPER),
            'corporate_name' => $request->corporate_name,
            'fantasy_name' => $request->fantasy_name,
            'cnpj_cpf' => $request->cnpj_cpf,
        ]);

        return response()->json([
            'table' => '<tr class="tr-id-'.$shipping_company->id.'">
                <td>'.$shipping_company->id.'</td>
                <td>'.$shipping_company->carrier_name.'</td>
                <td>'.$shipping_company->corporate_name.'</td>
                <td>'.$shipping_company->fantasy_name.'</td>
                <td>'.$shipping_company->cnpj_cpf.'</td>
                <td><a href="'.url('admin/transportes', $shipping_company->id).'" class="btn btn-info btn-sm">(0) <i class="fas fa-eye"></i> Visualizar</a></td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#editarTrasnportadora" data-dados=\''.json_encode($shipping_company).'\'><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-sm btn-editar" data-toggle="modal" data-target="#excluirTrasnportadora" data-dados=\''.json_encode($shipping_company).'\'><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>
            </tr>'
        ]);
    }

    public function atualizarTransportadora(Request $request)
    {
        $request->validate([
            'carrier_name' => 'required',
        ]);

        $shipping_companies['carrier_name']   = mb_convert_case($request->carrier_name, MB_CASE_UPPER);
        $shipping_companies['corporate_name'] = $request->corporate_name;
        $shipping_companies['fantasy_name']   = $request->fantasy_name;
        $shipping_companies['cnpj_cpf']       = $request->cnpj_cpf;

        ShippingCompany::where('id', $request->id)->update($shipping_companies);
        $shipping_company = ShippingCompany::with(['transportValues'])->where('id', $request->id)->first();

        return response()->json([
            'tb_id' => $shipping_company->id,
            'tb_up' => '
                <td>'.$shipping_company->id.'</td>
                <td>'.$shipping_company->carrier_name.'</td>
                <td>'.$shipping_company->corporate_name.'</td>
                <td>'.$shipping_company->fantasy_name.'</td>
                <td>'.$shipping_company->cnpj_cpf.'</td>
                <td><a href="'.url('admin/transportes', $shipping_company->id).'" class="btn btn-info btn-sm">('.$shipping_company->transportValues->count().') <i class="fas fa-eye"></i> Visualizar</a></td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#editarTrasnportadora" data-dados=\''.json_encode($shipping_company).'\'><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-sm btn-editar" data-toggle="modal" data-target="#excluirTrasnportadora" data-dados=\''.json_encode($shipping_company).'\'><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>'
        ]);
    }

    public function excluirTransportadora(Request $request)
    {
        ShippingCompany::where('id', $request->id)->delete();
        TransportValue::where('shipping_company_id', $request->id)->delete();

        return response()->json([
            'tb_trash' => $request->id
        ]);
    }

    ###################################################################

    public function novoServico(Request $request)
    {
        $request->validate([
            'price' => 'required|string',
            'time' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'weight' => 'required|string',
            'height' => 'required|string',
            'width' => 'required|string',
            'length' => 'required|string',
        ]);

        $transport_value = TransportValue::create([
            'shipping_company_id' => $request->shipping_company_id,
            'weight' => str_replace(['.', ','], ['', '.'], $request->weight),
            'height' => $request->height,
            'width' => $request->width,
            'length' => $request->length,
            'price' => str_replace(['.', ','], ['', '.'], $request->price),
            'state' => $request->state,
            'city' => $request->city,
            'time' => $request->time,
        ]);

        return response()->json([
            'table' => '<tr class="tr-id-'.$transport_value->id.'">
                <td>'.$transport_value->id.'</td>
                <td>'.number_format($transport_value->price,2,',','.').'</td>
                <td>'.$transport_value->state.'</td>
                <td>'.$transport_value->city.'</td>
                <td>'.$transport_value->time.' Dias</td>
                <td>'.$transport_value->weight.'</td>
                <td>'.$transport_value->height.'</td>
                <td>'.$transport_value->width.'</td>
                <td>'.$transport_value->length.'</td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#editarServico" data-dados=\''.json_encode($transport_value).'\'><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-sm btn-editar" data-toggle="modal" data-target="#excluirServico" data-dados=\''.json_encode($transport_value).'\'><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>
            </tr>'
        ]);
    }

    public function atualizarServico(Request $request)
    {
        $request->validate([
            'price' => 'required|string',
            'time' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'weight' => 'required|string',
            'height' => 'required|string',
            'width' => 'required|string',
            'length' => 'required|string',
        ]);

        $transport_values['weight'] = str_replace(['.', ','], ['', '.'], $request->weight);
        $transport_values['height'] = $request->height;
        $transport_values['width']  = $request->width;
        $transport_values['length'] = $request->length;
        $transport_values['price']  = str_replace(['.', ','], ['', '.'], $request->price);
        $transport_values['state']  = $request->state;
        $transport_values['city']   = $request->city;
        $transport_values['time']   = $request->time;

        TransportValue::where('id', $request->id)->update($transport_values);
        $transport_value = TransportValue::where('id', $request->id)->first();

        return response()->json([
            'tb_id' => $transport_value->id,
            'tb_up' => '
                <td>'.$transport_value->id.'</td>
                <td>R$ '.number_format($transport_value->price,2,',','.').'</td>
                <td>'.$transport_value->state.'</td>
                <td>'.$transport_value->city.'</td>
                <td>'.$transport_value->time.' Dias</td>
                <td>'.$transport_value->weight.'</td>
                <td>'.$transport_value->height.'</td>
                <td>'.$transport_value->width.'</td>
                <td>'.$transport_value->length.'</td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-sm btn-editar" data-toggle="modal" data-target="#editarServico" data-dados=\''.json_encode($transport_value).'\'><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-sm btn-editar" data-toggle="modal" data-target="#excluirServico" data-dados=\''.json_encode($transport_value).'\'><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>'
        ]);
    }

    public function excluirServico(Request $request)
    {
        TransportValue::where('id', $request->id)->delete();

        return response()->json([
            'tb_trash' => $request->id
        ]);
    }
}
