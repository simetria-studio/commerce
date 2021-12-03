<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Intervention\Image\ImageManagerStatic as Image;

class AttributeController extends Controller
{
    public function novoAtributo(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        if($request->parent_id){
            $attribute['name'] = mb_convert_case($request->name, MB_CASE_TITLE);
            $attribute['parent_id'] = $request->parent_id;

            $originalPath = storage_path('/app/attribute_image/');

            if($request->attribute_check == 'image'){
                $request->color = '';
                if($request->img_icon){
                    $width_max = 120;
                    $height_max = 120;

                    list($width_orig, $height_orig) = getimagesize($request->img_icon);
                    $ratio_orig = $width_orig/$height_orig;
                    if ($width_max/$height_max > $ratio_orig) {
                        $width_max = $height_max*$ratio_orig; //----
                    } else {
                        $height_max = $width_max/$ratio_orig; //----
                    }
                    $img_icon = Image::make($request->img_icon)->resize($width_max, $height_max);
                    $img_name = Str::random().'.'.$request->img_icon->extension();
                    $img_icon->save($originalPath.$img_name);

                    $attribute['image'] = 'attribute_image/'.$img_name;
                }
            }elseif($request->attribute_check == 'color'){
                $request->img_icon = '';
                $attribute['hexadecimal'] = $request->color;
            }
    
            $attribute_id = Attribute::create($attribute);

            if($request->img_icon){
                $image          = Storage::get('attribute_image/'.$img_name);
                $mime_type      = Storage::mimeType('attribute_image/'.$img_name);
                $image          = 'data:'.$mime_type.';base64,'.base64_encode($image);
                $item           = '<img width="45px" src="'.$image.'">';
            }elseif($request->color){
                $item = '<div style="width: 45px; height: 45px; background-color: '.$request->color.';"></div>';
            }
    
            return response()->json([
                'table' => '<tr class="tr-id-'.$attribute_id->id.'">
                    <td>'.$attribute_id->id.'</td>
                    <td>'.$item.'</td>
                    <td>'.$attribute_id->name.'</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                            <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-edit"></i> Alterar</a>
                            <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-trash"></i> Apagar</a>
                        </div>
                    </td>
                </tr>'
            ]);
        }else{
            $attribute['name'] = mb_convert_case($request->name, MB_CASE_UPPER);
    
            $attribute_id = Attribute::create($attribute);
    
            return response()->json([
                'table' => '<tr>
                    <td>'.$attribute_id->id.'</td>
                    <td>'.$attribute_id->name.'</td>
                    <td>0</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                            <a href="'.url('admin/cadastro/atributos', $attribute_id->id).'" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Vizualizar</a>
                            <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-edit"></i> Alterar</a>
                            <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-trash"></i> Apagar</a>
                        </div>
                    </td>
                </tr>'
            ]);
        }
    }

    public function atualizarAtributo(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        if($request->parent_id){
            $attribute['name'] = mb_convert_case($request->name, MB_CASE_TITLE);
            $attribute['parent_id'] = $request->parent_id;

            $originalPath = storage_path('/app/attribute_image/');

            if($request->attribute_check == 'image'){
                $request->color = '';
                if($request->img_icon){
                    $width_max = 120;
                    $height_max = 120;

                    list($width_orig, $height_orig) = getimagesize($request->img_icon);
                    $ratio_orig = $width_orig/$height_orig;
                    if ($width_max/$height_max > $ratio_orig) {
                        $width_max = $height_max*$ratio_orig; //----
                    } else {
                        $height_max = $width_max/$ratio_orig; //----
                    }
                    $img_icon = Image::make($request->img_icon)->resize($width_max, $height_max);
                    $img_name = Str::random().'.'.$request->img_icon->extension();
                    $img_icon->save($originalPath.$img_name);
                    Storage::delete(Attribute::where('id', $request->id)->first()->image);

                    $attribute['image'] = 'attribute_image/'.$img_name;
                }
            }elseif($request->attribute_check == 'color'){
                $request->img_icon = '';
                $attribute['hexadecimal'] = $request->color;
            }
    
            Attribute::where('id', $request->id)->update($attribute);
            $attribute_id = Attribute::where('id', $request->id)->first();

            if($request->img_icon){
                $image          = Storage::get('attribute_image/'.$img_name);
                $mime_type      = Storage::mimeType('attribute_image/'.$img_name);
                $image          = 'data:'.$mime_type.';base64,'.base64_encode($image);
                $item           = '<img width="45px" src="'.$image.'">';
            }elseif($request->color){
                $item = '<div style="width: 45px; height: 45px; background-color: '.$request->color.';"></div>';
            }
    
            return response()->json([
                'tb_id' => $attribute_id->id,
                'tb_up' => '
                    <td>'.$attribute_id->id.'</td>
                    <td>'.$item.'</td>
                    <td>'.$attribute_id->name.'</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                            <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-edit"></i> Alterar</a>
                            <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-trash"></i> Apagar</a>
                        </div>
                    </td>'
            ]);
        }else{
            $attribute['name'] = mb_convert_case($request->name, MB_CASE_UPPER);
    
            Attribute::where('id', $request->id)->update($attribute);
            $attribute_id = Attribute::where('id', $request->id)->first();
    
            return response()->json([
                'tb_id' => $attribute_id->id,
                'tb_up' => '
                    <td>'.$attribute_id->id.'</td>
                    <td>'.$attribute_id->name.'</td>
                    <td>0</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                            <a href="'.url('admin/cadastro/atributos', $attribute_id->id).'" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Vizualizar</a>
                            <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-edit"></i> Alterar</a>
                            <a href="#" class="btn btn-danger btn-xs btn-editar" data-toggle="modal" data-target="#excluirAtributo" data-dados="\''.json_encode($attribute_id).'\'"><i class="fas fa-trash"></i> Apagar</a>
                        </div>
                    </td>'
            ]);
        }
    }

    public function apagarAtributo(Request $request)
    {
        $attribute_parent = Attribute::where('parent_id', $request->id);
        foreach($attribute_parent->get() as $attr_images){
            if($attr_images->image) Storage::delete($attr_images->image);
        }
        $attribute_parent->delete();

        $attribute = Attribute::where('id', $request->id);
        if($attribute->first()->image) Storage::delete($attribute->first()->image);
        $attribute->delete();

        return response()->json([
            'tb_trash' => $request->id
        ]);
    }
}
