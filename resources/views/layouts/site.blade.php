<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MOBBIN - COMMERCE</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        {{-- Slick --}}
        <link rel="stylesheet" href="{{asset('plugin/slick-1.8.1/slick.css')}}"/>
        <link rel="stylesheet" href="{{asset('plugin/slick-1.8.1/slick-theme.css')}}"/>

        <link rel="stylesheet" href="{{asset('plugin/bootstrap-4.6.0/css/bootstrap.min.css')}}">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{asset('plugin/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('plugin/fontawesome-free/css/all.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{asset('plugin/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
        <!-- DateRangerPicker -->
        <link rel="stylesheet" href="{{asset('plugin/daterangepicker/daterangepicker.css')}}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('plugin/select2/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugin/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('plugin/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('plugin/summernote/summernote-bs4.min.css')}}">
        <!-- Custom css -->
        <link rel="stylesheet" href="{{asset('site/css/custom.min.css')}}">
        <link rel="stylesheet" href="{{asset('site/css/custom.menu.min.css')}}">

        <style>
            select[readonly].select2-hidden-accessible + .select2-container {
                pointer-events: none;
                touch-action: none;
            }

            select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
                background: #eee;
                box-shadow: none;
            }

            select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
                display: none;
            }
        </style>
</head>
<body>
    <header>
        {{-- Topo --}}
        <section class="container-fluid top-header">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="py-1">
                            <span class="">
                                Bem-vindo,
                                @if (auth()->check())
                                    <a href="{{asset('perfil')}}" style="text-decoration: underline;">{{auth()->user()->name}}</a> -
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt"></i> Sair</a>
                                @else
                                    <a href="{{route('login')}}" style="text-decoration: underline;">identifique-se</a> para fazer pedidos
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Campo de pesquisa --}}
                    <div class="col-12 col-sm-8 col-md-4 col-lg-6 col-xl-5">
                        <form action="{{asset('pesquisa')}}" method="get">
                            <div class="input-group ">
                                <input type="text" class="form-control form-control-sm" name="q" value="{{$q ?? ''}}" placeholder="O que você deseja?">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary btn-sm" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 col-sm-4 col-md-3 col-lg-2 col-xl-4 top-config pt-2 pt-sm-0 d-flex justify-content-between">
                        <div class="btn-group d-xl-none">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i> Mais Opções
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{asset('contato')}}" class="dropdown-item"><i class="fas fa-comment"></i> Fale Conosco</a>
                                <a href="{{asset('meus_pedidos')}}" class="dropdown-item"><i class="fas fa-list-ul"></i> Meus Pedidos</a>
                                <a href="{{asset('perfil')}}" class="dropdown-item"><i class="fas fa-user"></i> Meu Perfil</a>
                                @if (auth()->check())
                                    @if (auth()->user()->permission == 2)
                                        <a href="{{asset('conta')}}" class="dropdown-item"><i class="fas fa-user"></i> Minha Conta</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="d-none d-xl-block">

                            <ul class="nav justify-content-end">
                                <li class="nav-item"><a href="{{asset('contato')}}" class="nav-link"><i class="fas fa-comment"></i> Fale Conosco</a></li>
                                <li class="nav-item"><a href="{{asset('meus_pedidos')}}" class="nav-link"><i class="fas fa-list-ul"></i> Meus Pedidos</a></li>
                                <li class="nav-item">
                                    <div class="btn-group d-none d-xl-block">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i> Mais Opções
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{asset('perfil')}}" class="dropdown-item"><i class="fas fa-user"></i> Meu Perfil</a>
                                            @if (auth()->check())
                                                @if (auth()->user()->permission == 2)
                                                    <a href="{{asset('conta')}}" class="dropdown-item"><i class="fas fa-user"></i> Minha Conta</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="data-cart-top d-sm-none">
                            <span class="total-price">R$ <span>{{number_format(cart_show()->total, 2, ',', '')}}</span></span>

                            <span class="total-quantity">{{cart_show()->quantidade}}</span>
                            <span class="button-cart"><i class="fas fa-shopping-cart"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container-fluid top-menu">
            <div class="container">
                <div class="row">
                    <div class="col-8 col-sm-5 col-lg-2 col-xl-2 text-center"><a href="{{asset('/')}}"><img class="img-fluid" src="{{asset('imgs/LOGO.png')}}" alt=""></a></div>

                    {{-- Menu Desktop --}}
                    <div class="col-lg-8 col-xl-8 d-none d-lg-block">
                        <section class="container-fluid mt-3 text-center menu">
                            <div class="container">
                                <div id="mainNav">
                                    <div class="row">
                                        <div class="col-10">
                                            <ul id="main-menu" class="sm sm-read-simple">
                                                @forelse (getCategories() as $category)
                                                    <li>
                                                        <a class="nav-link" href="{{asset('categoria/'.$category->slug)}}">{{$category->name}}</a>

                                                        @if ($category->subCategories->count() > 0)
                                                            <ul>
                                                                @forelse ($category->subCategories as $subCategory)
                                                                    <li>
                                                                        <a class="nav-link" href="{{asset('categoria/'.$subCategory->slug)}}">{{$subCategory->name}}</a>
                                                                    </li>
                                                                @empty
                                                                @endforelse
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @empty
                                                @endforelse
                                            </ul>
                                        </div>
                                        {{-- <div class="col-2">
                                            <ul id="mainMenuOption" class='sm sm-read-simple'>
                                                <li>
                                                    <a class="nav-link more-options" href="#">MAIS OPÇÕES</a>

                                                    <ul class='hidden-links hidden sm sm-read-simple'></ul>
                                                </li>
                                            </ul>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- Menu Mobile --}}
                    <div class="col-4 col-sm-3 text-center d-lg-none">
                        <button type="button" class="btn btn-default" id="openMenu"><i class="fa fa-bars"></i></button>

                        <div id="menuSlider" class="close-menu">
                            <div class="menu-box">
                                <button type="button" class="m-2 close close-menu">X</button>

                                <div class="menu-content">
                                    <div class="container my-5">
                                        <ul id="sub-main-menu" class="sm sm-sub-read-simple">
                                            @forelse (getCategories() as $category)
                                                <li>
                                                    <a class="nav-link" href="{{asset('categoria/'.$category->slug)}}">{{$category->name}}</a>

                                                    @if ($category->subCategories->count() > 0)
                                                        <ul>
                                                            @forelse ($category->subCategories as $subCategory)
                                                                <li>
                                                                    <a class="nav-link" href="{{asset('categoria/'.$subCategory->slug)}}">{{$subCategory->name}}</a>
                                                                </li>
                                                            @empty
                                                            @endforelse
                                                        </ul>
                                                    @endif
                                                </li>
                                            @empty
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 col-lg-2 col-xl-2 d-none d-sm-block data-cart">
                        <span class="total-price">R$ <span>{{number_format(cart_show()->total, 2, ',', '')}}</span></span>

                        <span class="total-quantity">{{cart_show()->quantidade}}</span>
                        <span class="button-cart"><i class="fas fa-shopping-cart"></i></span>
                    </div>
                </div>
            </div>
        </section>

        <div class="py-1 services-links">
            <div class="container">
                <ul class="nav">
                    <li class="nav-item pt-2">Outros Serviços: </li>
                    <li class="nav-item"><a href="#" class="nav-link">Sobre Nos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Oferta do dia</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Ajuda</a></li>
                </ul>
            </div>
        </div>
    </header>

    {{-- Carrinho --}}
    <section id="cart_shop" class="close-cart">
        <div class="cart-box">
            <div class="cart-content">
                <a href="#" class="m-2 cart-produto-apagar"><i class="fa fa-trash"></i> Limpar Carrinho</a>

                <button type="button" class="m-3 close close-cart">X</button>

                <div class="container my-5">
                    {{-- {{print_r(cart_show())}} --}}
                    @foreach (cart_show()->content as $modal_cart)
                        @php
                            $img        = Storage::get($modal_cart->attributes->product_image);
                            $mime_type  = Storage::mimeType($modal_cart->attributes->product_image);
                            $image      = 'data:'.$mime_type.';base64,'.base64_encode($img);
                        @endphp
                        <div class="cart-produto">
                            <div class="cart-image"><img src="{{$image}}" alt=""></div>
                            <div class="cart-produto-body d-flex flex-column justify-content-center">
                                <p class="cart-produto-nome">{{$modal_cart->name}}</p>
                                <p class="cart-produto-valor">R$ {{$modal_cart->quantity}} x R$ {{number_format($modal_cart->price, 2, ',', '')}}</p>
                            </div>
                            <div class="cart-produto-button d-flex flex-column justify-content-center"><a href="#" class="btn btn-danger btn-delete-product" data-row_id="{{$modal_cart->row_id}}"><i class="fa fa-trash"></i></a></div>
                        </div>
                    @endforeach

                    <div class="cart-button">
                        <div><a href="{{asset('pagamento/dados')}}" class="btn btn-default">Finalizar</a></div>
                        <div><a href="{{asset('carrinho')}}" class="btn btn-default">Carrinho</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container-fluid px-0">
        @yield('container')
    </main>

    <footer class="footer">
        {{-- footer pre-bottom --}}
        <div class="container-fluid footer-pre-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div id="footerContato" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                        <h4 class="contato-titulo"><strong>CONTATO</strong></h4>
                        <ul class="nav flex-column col-contato">
                            <li class="nav-item">TELEFONES:</li>
                            <li class="nav-item">(41) 9999-9999</li>
                            <li class="nav-item">(41) 9 99858-8598</li>
                        </ul>

                        <ul class="nav flex-column col-contato">
                            <li class="nav-item">EMAIL:</li>
                            <li class="nav-item">contato@mobbin.app.com</li>
                        </ul>
                    </div>

                    <div id="footerAtendimento" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                        <h4 class="atendimento-titulo"><strong>ATENDIMENTO</strong></h4>
                        <ul class="nav flex-column col-atendimento">
                            <li class="nav-item"><a href="#" class="link">Sobre Nós</a></li>
                            <li class="nav-item"><a href="#" class="link">Fale Conosco</a></li>
                            <li class="nav-item"><a href="#" class="link">Minha Conta</a></li>
                            <li class="nav-item"><a href="#" class="link">Politica de Privacidade</a></li>
                            <li class="nav-item"><a href="#" class="link">Politica de Segurança</a></li>
                            <li class="nav-item"><a href="#" class="link">Trocas e Devoluções</a></li>
                        </ul>
                    </div>

                    <div id="footerFormaPagamento" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                        <h4 class="forma-pagamento-titulo"><strong>FORMAS DE PAGAMENTO</strong></h4>
                        <div class="bandeiras">
                            @php
                                $imagens = scandir('./imgs/bandeiras');
                                $imagem_ignore = ['0','1'];
                            @endphp
                            @foreach ($imagens as $key => $imagem)
                                @php
                                    if(in_array($key, $imagem_ignore)) continue;
                                @endphp
                                <img class="rounded mx-1" src="{{asset('imgs/bandeiras/'.$imagem)}}" alt="{{$imagem}}">
                            @endforeach
                        </div>
                    </div>

                    <div id="footerRedeSocial" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                        <h4 class="rede-social-titulo"><strong>REDES SOCIAIS</strong></h4>
                        <div class="row">
                            <div class="col-2"><a href="#" class="btn btn-link btn-sm"><img width="40px" class="rounded" src="{{asset('imgs/instagram.jpg')}}" alt=""></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- footer bottom --}}
        <div class="container-fluid footer-bottom">
            <div class="container text-center">
                MOBBIN {{date("Y")}} &COPY;
            </div>
        </div>
    </footer>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- jQuery -->
    <script src="{{asset('plugin/jquery-3.6.0.min.js')}}"></script>
    {{-- Slick --}}
    <script src="{{asset('plugin/slick-1.8.1/slick.min.js')}}"></script>
    <!-- MaskJquery -->
    <script src="{{asset('plugin/mask.jquery.js')}}"></script>
    <script src="{{asset('plugin/mask.money.js')}}"></script>
    <!-- ValidaCnpjCpf -->
    <script src="{{asset('plugin/valida_cpf_cnpj.js')}}"></script>
    <!-- bootstrap-4.6.0 -->
    <script src="{{asset('plugin/bootstrap-4.6.0/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('plugin/select2/js/select2.full.min.js')}}"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugin/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Moment -->
    <script src="{{asset('plugin/moment/moment.min.js')}}"></script>
    <!-- DateRangerPicker -->
    <script src="{{asset('plugin/daterangepicker/daterangepicker.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('plugin/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('plugin/summernote/summernote-bs4.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('plugin/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    {{-- Smartmenus --}}
    <script src="{{ asset('site/js/jquery.smartmenus.min.js') }}"></script>

    <script src="{{ asset('site/js/custom.min.js') }}"></script>

    <script>
        $(function() {

            var $btn = $('#mainNav .menu-option');
            var $vlinks = $('#mainNav #main-menu');
            var $hlinks = $('#mainNav .hidden-links');

            var numOfItems = 0;
            var totalSpace = 0;
            var closingTime = 1000;
            var breakWidths = [];

            // Get initial state
            $vlinks.children().outerWidth(function(i, w) {
                totalSpace += w;
                numOfItems += 1;
                breakWidths.push(totalSpace);
            });

            var availableSpace, numOfVisibleItems, requiredSpace, timer;

            function check() {

                // Get instant state
                availableSpace = $vlinks.width() - 100;
                numOfVisibleItems = $vlinks.children().length;
                requiredSpace = breakWidths[numOfVisibleItems - 1];

                // There is not enought space
                if (requiredSpace > availableSpace) {
                    $vlinks.children().last().prependTo($hlinks);
                    numOfVisibleItems -= 1;
                    check();
                    // There is more than enough space
                } else if (availableSpace > breakWidths[numOfVisibleItems]) {
                    $hlinks.children().first().appendTo($vlinks);
                    numOfVisibleItems += 1;
                    check();
                }
                // Update the button accordingly
                $btn.attr("count", numOfItems - numOfVisibleItems);
                if (numOfVisibleItems === numOfItems) {
                    $btn.addClass('hidden');
                } else $btn.removeClass('hidden');
            }

            // Window listeners
            $(window).resize(function() {
                check();
            });

            $btn.on('click', function() {
                $hlinks.toggleClass('hidden');
                clearTimeout(timer);
            });

            $hlinks.on('mouseleave', function() {
                // Mouse has left, start the timer
                timer = setTimeout(function() {
                    $hlinks.addClass('hidden');
                }, closingTime);
            }).on('mouseenter', function() {
                // Mouse is back, cancel the timer
                clearTimeout(timer);
            })

            check();

        });

        $(document).ready(function(){
            $(function() {
                $("#main-menu, #mainMenuPages, #mainMenuOption").smartmenus({
                    subMenusSubOffsetX: 6,
                    subMenusSubOffsetY: -8
                });
            });

            $(function() {
                $("#sub-main-menu").smartmenus({
                    mainMenuSubOffsetX: 1,
                    mainMenuSubOffsetY: -8,
                    subMenusSubOffsetX: 1,
                    subMenusSubOffsetY: -8
                });
            });

            // Botão menu mobile
            $(document).on("click", "#openMenu", function(){
                $("#menuSlider").addClass('menu-body');
                $("#menuSlider").find(".menu-box").addClass('menu-box-size');

                $("body").addClass("modal-open");
            });

            $(document).on("click", ".close-menu", function(e){
                let close_box = $(e.target).is("#menuSlider") ? true : false;
                let close_button = e.target == document.querySelector("button.close-menu") ? true : false;

                if (close_box || close_button) {
                    $("#menuSlider").find(".menu-box").removeClass("menu-box-size");
                    setTimeout(function() {
                        $("#menuSlider").removeClass("menu-body");

                        $("body").removeClass("modal-open");
                    }, 1000);
                }
            });
            // Botão menu mobile

            // Botão open cart
            $(document).on("click", ".button-cart", function(){
                $("#cart_shop").addClass('cart-body');
                $("#cart_shop").find(".cart-box").addClass('cart-box-size');

                $("body").addClass("modal-open");
            });

            $(document).on("click", ".close-cart", function(e){
                let close_box = $(e.target).is("#cart_shop") ? true : false;
                let close_button = e.target == document.querySelector("button.close-cart") ? true : false;

                if (close_box || close_button) {
                    $("#cart_shop").find(".cart-box").removeClass("cart-box-size");
                    setTimeout(function() {
                        $("#cart_shop").removeClass("cart-body");

                        $("body").removeClass("modal-open");
                    }, 1000);
                }
            });
            // Botão open cart
        });

    </script>

    @yield('js')
</body>
</html>
