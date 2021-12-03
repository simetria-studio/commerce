<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiteController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:clear');
    // return what you want
});

Route::any('/cep/{cep}', [SiteController::class, 'cepConsulta']);

// Site ###############
Route::get('/', [SiteController::class, 'indexHome']);
Route::get('/pesquisa', [SiteController::class, 'indexPesquisa']);
Route::get('/contato', [SiteController::class, 'indexFaleConosco']);
Route::get('/carrinho', [SiteController::class, 'indexCarrinho']);
Route::get('/categoria/{slug?}', [SiteController::class, 'indexCategoria']);
Route::get('/produto/{slug?}', [SiteController::class, 'indexProduto']);
Route::post('/carrinhoAdd', [CartController::class, 'cartAdd']);
Route::post('/carrinhoRemove', [CartController::class, 'cartRemove']);
// ####################

Route::middleware(['auth:sanctum', 'verified', 'auth.check.permission.perfil'])->group(function () {
    Route::get('/perfil', [SiteController::class, 'perfil']);
    Route::get('/conta', [SiteController::class, 'conta']);
    Route::get('/pedidos/{id?}', [SiteController::class, 'pedido']);

    Route::get('/meus_pedidos', [SiteController::class, 'indexPedidos']);

    Route::post('perfilSave', [AccountController::class, 'perfilSave']);
    Route::post('senhaSave', [AccountController::class, 'senhaSave']);
    Route::post('enderecoSave', [AccountController::class, 'enderecoSave']);
    Route::get('apagarEndereco/{id}', [AccountController::class, 'apagarEndereco']);

    Route::get('pagamento/{url?}', [SiteController::class, 'indexPagamento']);
    Route::post('atualizarPagamento', [SiteController::class, 'atualizarPagamento']);

    Route::any('finalizarPagamento', [PaymentController::class, 'finalizarPagamento']);
    Route::any('notificaPagamento', [PaymentController::class, 'notificaPagamento']);

    Route::post('aplicarCupom', [CouponController::class, 'appCoupon']);
});

Route::middleware(['auth:sanctum', 'verified', 'auth.check.permission'])->group(function(){
    Route::prefix('/admin')->group(function(){
        Route::get('/', [PainelController::class, 'dashboard']);
        Route::get('/perfil', [PainelController::class, 'indexPerfil']);
        Route::get('/contas', [PainelController::class, 'indexContas']);
        Route::get('/transportes/{id?}', [PainelController::class, 'indexTransporte']);

        // Alteração de dados das contas
        Route::post('/novaConta', [AccountController::class, 'novaConta'])->name('novaConta');
        Route::post('/atualizarConta', [AccountController::class, 'atualizarConta'])->name('atualizarConta');
        Route::post('/excluirConta', [AccountController::class, 'excluirConta'])->name('excluirConta');
        Route::post('/atualizarSenha', [AccountController::class, 'atualizarSenha'])->name('atualizarSenha');

        // Alteraçãp de dados do perfil
        Route::post('/nomePerfil', [AccountController::class, 'nomePerfil'])->name('nomePerfil');
        Route::post('/emailPerfil', [AccountController::class, 'emailPerfil'])->name('emailPerfil');
        Route::post('/senhaPerfil', [AccountController::class, 'senhaPerfil'])->name('senhaPerfil');

        // Transportadora
        Route::post('/novaTransportadora', [ShippingController::class, 'novaTransportadora'])->name('novaTransportadora');
        Route::post('/atualizarTransportadora', [ShippingController::class, 'atualizarTransportadora'])->name('atualizarTransportadora');
        Route::post('/excluirTransportadora', [ShippingController::class, 'excluirTransportadora'])->name('excluirTransportadora');
        Route::post('/novoServico', [ShippingController::class, 'novoServico'])->name('novoServico');
        Route::post('/atualizarServico', [ShippingController::class, 'atualizarServico'])->name('atualizarServico');
        Route::post('/excluirServico', [ShippingController::class, 'excluirServico'])->name('excluirServico');

        Route::prefix('/cadastro')->group(function(){
            // Categorias
            Route::get('/categoria_menu/{id?}', [PainelController::class, 'indexCategoria']);
            Route::post('/nova_categoria', [CategoryController::class, 'novaCategoria'])->name('novaCategoria');
            Route::post('/atualizar_categoria', [CategoryController::class, 'atualizarCategoria'])->name('atualizarCategoria');
            Route::post('/pesquisa_categoria', [CategoryController::class, 'pesquisaCategoria']);
            Route::post('/pesquisa_categoria_produto', [CategoryController::class, 'pesquisaCategoriaProduto']);
            Route::post('/excluir_categoria', [CategoryController::class, 'excluirCategoria']);

            Route::get('/produtos', [PainelController::class, 'indexProduto']);
            Route::post('/novo_produto', [ProductController::class, 'novoProduto'])->name('novoProduto');
            Route::post('/atualizar_produto', [ProductController::class, 'atualizarProduto'])->name('atualizarProduto');
            Route::post('/inativar_produto', [ProductController::class, 'inativarProduto']);

            Route::get('/promocoes', [PainelController::class, 'indexPromocao']);
            Route::post('/nova_promocao', [PromotionController::class, 'novaPromocao'])->name('novaPromocao');
            Route::post('/atualizar_promocao', [PromotionController::class, 'atualizarPromocao'])->name('atualizarPromocao');
            Route::post('/apagar_promocao', [PromotionController::class, 'apagarPromocao'])->name('apagarPromocao');

            Route::get('atributos/{id?}', [PainelController::class, 'indexAtributo']);
            Route::post('/novo_atributo', [AttributeController::class, 'novoAtributo'])->name('novoAtributo');
            Route::post('/atualizar_atributo', [AttributeController::class, 'atualizarAtributo'])->name('atualizarAtributo');
            Route::post('/apagar_atributo', [AttributeController::class, 'apagarAtributo'])->name('apagarAtributo');
        });

        Route::prefix('/cliente')->group(function(){
            Route::get('/clientes', [PainelController::class, 'indexClientes']);
            Route::get('/afiliados', [PainelController::class, 'indexAfiliados']);
            Route::get('/enderecos/{id}', [PainelController::class, 'indexEnderecos']);
            
            Route::post('/novoCliente', [AccountController::class, 'novoCliente'])->name('novoCliente');
            Route::post('/novoAfiliado', [AccountController::class, 'novoAfiliado'])->name('novoAfiliado');
            Route::post('/atualizarCliente', [AccountController::class, 'atualizarCliente'])->name('atualizarCliente');
            Route::post('/excluirCliente', [AccountController::class, 'excluirCliente'])->name('excluirCliente');
            Route::post('/atualizarSenhaCliente', [AccountController::class, 'atualizarSenhaCliente'])->name('atualizarSenhaCliente');

            Route::post('/novoEndereco', [AccountController::class, 'novoEndereco'])->name('novoEndereco');
            Route::post('/atualizarEndereco', [AccountController::class, 'atualizarEndereco'])->name('atualizarEndereco');
            Route::post('/excluirEndereco', [AccountController::class, 'excluirEndereco'])->name('excluirEndereco');
        });

        Route::prefix('/cupom')->group(function(){
            Route::get('/cupons', [PainelController::class, 'indexCupons']);

            Route::post('/novoCupom', [CouponController::class, 'novoCupom'])->name('novoCupom');
            Route::post('/atualizarCupom', [CouponController::class, 'atualizarCupom'])->name('atualizarCupom');
            Route::post('/excluirCupom', [CouponController::class, 'excluirCupom'])->name('excluirCupom');

            Route::post('/pagarAfiliado', [CouponController::class, 'pagarAfiliado'])->name('pagarAfiliado');
        });

        Route::prefix('/comercial')->group(function(){
            Route::get('/pedido_finalizado', [PainelController::class, 'indexFinalizados']);
        });
    });
});