<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function pesquisaCategoria(Request $request)
    {
        if($request->category_id) {
            $category = Category::whereNull('parent_id')->get();
            return response()->json($category);
        }

        if($request->parent_id) {
            $category = Category::where('parent_id', $request->parent_id)->get();
            return response()->json($category);
        }

        return response()->json(['msg' => 'Necessario um id!'], 412);
    }

    public function novaCategoria(Request $request)
    {
        $request->validate(['category_name' => 'required|string']);

        if($request->parent_id){
            $categories = Category::where('parent_id', $request->parent_id)->get()->count();
    
            if($categories == 18) return response()->json(['msg_alert' => 'Maximo 18 Sub Categorias criadas!', 'icon_alert' => 'warning'], 412);

            $category['name'] = mb_convert_case($request->category_name, MB_CASE_TITLE);
            $category['parent_id'] = $request->parent_id;
    
            $category_id = Category::create($category);
    
            return response()->json([
                'table' => '<tr class="tr-id-'.$category_id->id.'">
                    <td>'.$category_id->id.'</td>
                    <td>'.$category_id->name.'</td>
                    <td>'.$category_id->slug.'</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                            <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCategoria" data-dados="\''.json_encode($category_id).'\'"><i class="fas fa-edit"></i> Alterar</a>
                            <a href="#" class="btn btn-danger btn-xs btn-excluir-categoria" data-toggle="modal" data-target="#excluirCategoria" data-dados="\''.json_encode($category_id).'\'"><i class="fas fa-trash"></i> Apagar</a>
                        </div>
                    </td>
                </tr>'
            ]);
        }else{
            $categories = Category::whereNull('parent_id')->get()->count();
    
            if($categories == 10) return response()->json(['msg_alert' => 'Maximo 10 Categorias criadas!', 'icon_alert' => 'warning'], 412);
    
            $category['name'] = mb_convert_case($request->category_name, MB_CASE_UPPER);
    
            $category_id = Category::create($category);
    
            return response()->json([
                'table' => '<tr>
                    <td>'.$category_id->id.'</td>
                    <td>'.$category_id->name.'</td>
                    <td>'.$category_id->slug.'</td>
                    <td>0</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                            <a href="'.url('admin/cadastro/categoria_menu', $category_id->id).'" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Vizualizar</a>
                            <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCategoria" data-dados="\''.json_encode($category_id).'\'"><i class="fas fa-edit"></i> Alterar</a>
                            <a href="#" class="btn btn-danger btn-xs btn-excluir-categoria" data-toggle="modal" data-target="#excluirCategoria" data-dados="\''.json_encode($category_id).'\'"><i class="fas fa-trash"></i> Apagar</a>
                        </div>
                    </td>
                </tr>'
            ]);
        }
    }

    public function atualizarCategoria(Request $request)
    {
        $request->validate(['category_name' => 'required|string']);
        $td_catgoria = '';
        $a_categoria = '';

        $verifica_category = Category::where('id', $request->id)->first();
        if($verifica_category->parent_id == null){
            $category_name = mb_convert_case($request->category_name, MB_CASE_UPPER);
            $td_catgoria = '<td>'.Category::where('parent_id', $request->id)->get()->count().'</td>';
            $a_categoria = '<a href="'.url('admin/cadastro/categoria_menu', $request->id).'" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Vizualizar</a>';
        }else{
            $category_name = mb_convert_case($request->category_name, MB_CASE_TITLE);
        }

        $category = Category::where('id', $request->id)->update(['name' => $category_name]);
        $category = Category::where('id', $request->id)->first();

        return response()->json([
            'tb_id' => $category->id,
            'tb_up' => '
                <td>'.$category->id.'</td>
                <td>'.$category->name.'</td>
                <td>'.$category->slug.'</td>
                '.$td_catgoria.'
                <td>
                    <div class="btn-group" role="group" aria-label="">
                        '.$a_categoria.'
                        <a href="#" class="btn btn-info btn-xs btn-editar" data-toggle="modal" data-target="#editarCategoria" data-dados="\''.json_encode($category).'\'"><i class="fas fa-edit"></i> Alterar</a>
                        <a href="#" class="btn btn-danger btn-xs btn-excluir-categoria" data-toggle="modal" data-target="#excluirCategoria" data-dados="\''.json_encode($category).'\'"><i class="fas fa-trash"></i> Apagar</a>
                    </div>
                </td>'
        ]);
    }

    public function pesquisaCategoriaProduto(Request $request)
    {
        $dados = Product::where('main_category', $request->id)->where('status', '!=', '0')->get();
        $tipo = 'produto';

        if($dados->count() == 0){
            $dados = Category::where('parent_id', $request->id)->get();
            $tipo = 'categoria';
        }

        return response()->json(['dados' => $dados, 'tipo' => $tipo]);
    }

    public function excluirCategoria(Request $request)
    {
        $category = Category::where('id', $request->id)->delete();

        return response()->json(['category_id' => $request->id]);
    }
}
