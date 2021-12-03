<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    public $sales_unit_array = [
        'P' => 'Peça',
        'M' => 'Metro',
        'MQ' => 'Metro Quadrado'
    ];

    public function novoProduto(Request $request)
    {
        $request->validate([
            'code'              => 'unique:products',
            'name'              => 'required|string',
            'brief_description' => 'required|string',
            'sales_unit'        => 'required|string',
            'value'             => 'required|string',
            'main_category'     => 'required|string',
        ]);

        $originalPath = storage_path('/app/product_image/');

        $products['code']               = $request->code;
        $products['name']               = mb_convert_case($request->name, MB_CASE_TITLE);
        $products['brief_description']  = $request->brief_description;
        $products['sales_unit']         = $request->sales_unit;
        $products['value']              = str_replace(['.', ','], ['', '.'], $request->value);
        $products['brand']              = $request->brand;
        $products['product_type']       = $request->product_type ?? null;
        $products['weight']             = str_replace(['.', ','], ['', '.'], $request->weight);
        $products['height']             = $request->height;
        $products['width']              = $request->width;
        $products['length']             = $request->length;
        $products['has_preparation']    = $request->has_preparation;
        $products['preparation_time']   = $request->has_preparation == 'S' ? $request->preparation_time : null;
        $products['main_category']      = $request->main_category;
        $products['sub_category']       = $request->sub_category;
        $products['description']        = $request->description;
        $products['status']             = 1;

        $products = Product::create($products);

        if ($products->id) {
            if ($request->attribute) {
                foreach ($request->attribute as $attr) {
                    $attr = json_decode(json_encode($attr));
                    if ($attr->icheck_attribute == 'S') {
                        $attributes['product_id']       = $products->id;
                        $attributes['parent_id']        = $attr->parent_id;
                        $attributes['attribute_id']     = $attr->attribute_id ?? null;
                        $attributes['attribute_name']   = $attr->attribute_name ?? null;
                        $attributes['attribute_value']  = $attr->attribute_value ?? '' ? str_replace(['.', ','], ['', '.'], $attr->attribute_value) : null;

                        ProductAttribute::create($attributes);
                    }
                }
            }

            $width_max = 420;
            $height_max = 480;

            list($width_orig, $height_orig) = getimagesize($request->img_principal);
            $ratio_orig = $width_orig / $height_orig;
            if ($width_max / $height_max > $ratio_orig) {
                $width_max = $height_max * $ratio_orig; //----
            } else {
                $height_max = $width_max / $ratio_orig; //----
            }
            $img_principal = Image::make($request->img_principal)->resize($width_max, $height_max);
            $img_name = Str::random() . '.' . $request->img_principal->extension();
            $img_principal->save($originalPath . $img_name);

            $create_img_principal['product_id'] = $products->id;
            $create_img_principal['sequence']   = 1;
            $create_img_principal['image_name'] = 'product_image/' . $img_name;
            ProductImage::create($create_img_principal);

            // Imagens multiplos
            if ($request->img_multipla) {
                foreach ($request->img_multipla as $for_img_multipla) {
                    $width_max = 420;
                    $height_max = 480;

                    list($width_orig, $height_orig) = getimagesize($for_img_multipla);
                    $ratio_orig = $width_orig / $height_orig;
                    if ($width_max / $height_max > $ratio_orig) {
                        $width_max = $height_max * $ratio_orig; //----
                    } else {
                        $height_max = $width_max / $ratio_orig; //----
                    }
                    $img_multipla = Image::make($for_img_multipla)->resize($width_max, $height_max);
                    $img_name_m = Str::random() . '.' . $for_img_multipla->extension();
                    $img_multipla->save($originalPath . $img_name_m);

                    $count_sequence = ProductImage::where('product_id', $products->id)->orderByDesc('sequence')->first();

                    $create_img_multipla['product_id'] = $products->id;
                    $create_img_multipla['sequence']   = ($count_sequence->sequence + 1);
                    $create_img_multipla['image_name'] = 'product_image/' . $img_name_m;
                    ProductImage::create($create_img_multipla);
                }
            }

            $main_category['product_id'] = $products->id;
            $main_category['category_id'] = $request->main_category;
            $main_category['category_pai'] = 'S';
            ProductCategory::create($main_category);

            if ($request->sub_category) {
                foreach ($request->sub_category as $sub_category) {
                    if ($sub_category) {
                        $sub_categorys['product_id'] = $products->id;
                        $sub_categorys['category_id'] = $sub_category;
                        $sub_categorys['category_pai'] = 'N';
                        ProductCategory::create($sub_categorys);
                    }
                }
            }
        }

        $product_image  = ProductImage::where('product_id', $products->id)->where('sequence', '1')->first();
        $image          = Storage::get($product_image->image_name);
        $mime_type      = Storage::mimeType($product_image->image_name);
        $image          = 'data:' . $mime_type . ';base64,' . base64_encode($image);

        return response()->json([
            'table' => '<tr class="tr-id-' . $products->id . '">
                <td>' . $products->id . '</td>
                <td><img width="100px" src="' . $image . '"></td>
                <td>' . $products->name . '</td>
                <td>' . $this->sales_unit_array[$products->sales_unit] . '</td>
                <td>R$ ' . number_format($products->value, 2, '', ',') . '</td>
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        <a href="#" class="btn btn-info btn-xs"><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>
            </tr>'
        ]);
    }

    public function atualizarProduto(Request $request)
    {
        $request->validate([
            'code'              => 'unique:products,code,' . $request->id,
            'name'              => 'required|string',
            'brief_description' => 'required|string',
            'sales_unit'        => 'required|string',
            'value'             => 'required|string',
            'main_category'     => 'required|string',
        ]);

        $originalPath = storage_path('/app/product_image/');

        $products['code']               = $request->code;
        $products['name']               = mb_convert_case($request->name, MB_CASE_TITLE);
        $products['brief_description']  = $request->brief_description;
        $products['sales_unit']         = $request->sales_unit;
        $products['value']              = str_replace(['.', ','], ['', '.'], $request->value);
        $products['brand']              = $request->brand;
        $products['product_type']       = $request->product_type ?? null;
        $products['weight']             = str_replace(['.', ','], ['', '.'], $request->weight);
        $products['height']             = $request->height;
        $products['width']              = $request->width;
        $products['length']             = $request->length;
        $products['has_preparation']    = $request->has_preparation;
        $products['preparation_time']   = $request->has_preparation == 'S' ? $request->preparation_time : null;
        $products['description']        = $request->description;
        $products['status']             = 1;

        $products = Product::where('id', $request->id)->update($products);

        ProductAttribute::where('product_id', $request->id)->delete();

        foreach ($request->attribute as $attr) {
            $attr = json_decode(json_encode($attr));
            if ($attr->icheck_attribute == 'S') {
                $attributes['product_id']       = $request->id;
                $attributes['parent_id']        = $attr->parent_id;
                $attributes['attribute_id']     = $attr->attribute_id ?? null;
                $attributes['attribute_name']   = $attr->attribute_name ?? null;
                $attributes['attribute_value']  = $attr->attribute_value ?? '' ? str_replace(['.', ','], ['', '.'], $attr->attribute_value) : null;

                ProductAttribute::create($attributes);
            }
        }

        if ($request->img_principal) {
            $width_max = 420;
            $height_max = 480;

            list($width_orig, $height_orig) = getimagesize($request->img_principal);
            $ratio_orig = $width_orig / $height_orig;
            if ($width_max / $height_max > $ratio_orig) {
                $width_max = $height_max * $ratio_orig; //----
            } else {
                $height_max = $width_max / $ratio_orig; //----
            }
            $img_principal = Image::make($request->img_principal)->resize($width_max, $height_max);
            $img_name = Str::random() . '.' . $request->img_principal->extension();
            $img_principal->save($originalPath . $img_name);

            $img_update_principal = ProductImage::where('product_id', $request->id)->where('sequence', '1')->first();
            Storage::delete($img_update_principal->image_name);

            $create_img_principal['image_name'] = $img_principal;
            $img_update_principal->update($create_img_principal);
        }

        if ($request->img_multipla) {
            $img_deleta_multiplas = ProductImage::where('product_id', $request->id)->where('sequence', '>=', '2')->get();
            foreach ($img_deleta_multiplas as $imgdm) {
                Storage::delete($imgdm->image_name);
                ProductImage::where('id', $imgdm->id)->delete();
            }

            foreach ($request->img_multipla as $for_img_multipla) {
                $count_sequence = ProductImage::where('product_id', $request->id)->orderByDesc('sequence')->first();

                $width_max = 420;
                $height_max = 480;

                list($width_orig, $height_orig) = getimagesize($for_img_multipla);
                $ratio_orig = $width_orig / $height_orig;
                if ($width_max / $height_max > $ratio_orig) {
                    $width_max = $height_max * $ratio_orig; //----
                } else {
                    $height_max = $width_max / $ratio_orig; //----
                }
                $img_multipla = Image::make($for_img_multipla)->resize($width_max, $height_max);
                $img_name_m = Str::random() . '.' . $for_img_multipla->extension();
                $img_multipla->save($originalPath . $img_name_m);

                $create_img_multipla['product_id'] = $request->id;
                $create_img_multipla['sequence']   = ($count_sequence->sequence + 1);
                $create_img_multipla['image_name'] = $img_multipla;
                ProductImage::create($create_img_multipla);
            }
        }

        ProductCategory::where('product_id', $request->id)->delete();

        $main_category['product_id'] = $request->id;
        $main_category['category_id'] = $request->main_category;
        $main_category['category_pai'] = 'S';
        ProductCategory::create($main_category);

        if ($request->sub_category) {
            foreach ($request->sub_category as $sub_category) {
                if ($sub_category) {
                    $sub_categorys['product_id'] = $request->id;
                    $sub_categorys['category_id'] = $sub_category;
                    $sub_categorys['category_pai'] = 'N';
                    ProductCategory::create($sub_categorys);
                }
            }
        }

        $product_image  = ProductImage::where('product_id', $request->id)->where('sequence', '1')->first();
        $image          = Storage::get($product_image->image_name);
        $mime_type      = Storage::mimeType($product_image->image_name);
        $image          = 'data:' . $mime_type . ';base64,' . base64_encode($image);

        $images_json = ProductImage::where('product_id', $request->id)->get();
        $data_images = [];
        foreach ($images_json as $imgjson) {
            // Pegando a imagem e tarnsformamndo em data
            $images         = Storage::get($imgjson->image_name);
            $mime_types     = Storage::mimeType($imgjson->image_name);
            $images         = 'data:' . $mime_types . ';base64,' . base64_encode($images);

            // Adiconando a um array para depois ser utilizados
            $data_images[] = [
                'sequence'  => $imgjson->sequence,
                'image'     => $images
            ];
        }

        return response()->json([
            'product_id' => $request->id,
            'image_data' => $image,
            'product_name' => mb_convert_case($request->name, MB_CASE_TITLE),
            'sales_unit' => $this->sales_unit_array[$request->sales_unit],
            'value' => 'R$ ' . $request->value,
            'discount' => '',
            'dados' => Product::where('id', $request->id)->with(['productImage'])->first(),
            'dados_image' => $data_images
        ]);
    }

    public function inativarProduto(Request $request)
    {
        $product = Product::where('id', $request->product_id)->update(['status' => '0']);

        return response()->json(['product_id' => $request->product_id]);
    }
}
