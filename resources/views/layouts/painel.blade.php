<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Painel E-Commerce</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

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
        {{-- Colopicker --}}
        <link rel="stylesheet" href="{{asset('plugin/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('plugin/AdminLTE/css/adminlte.min.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('plugin/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('plugin/summernote/summernote-bs4.min.css')}}">

        <link rel="stylesheet" href="{{asset('painel/style.min.css')}}">

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
    <body class="hold-transition sidebar-mini layout-fixed text-sm sidebar-collapse">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-dark navbar-navy">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{asset('./')}}" target="_blank" class="nav-link">Minha Loja</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt"></i> Sair</a>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="index3.html" class="brand-link">
                    <span class="brand-text font-weight-light">Painel E-Commerce</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{asset('admin')}}" class="nav-link @if(Request::is('admin')) active @endif">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('admin/comercial/*')) menu-open @endif">
                                <a href="#" class="nav-link @if(Request::is('admin/comercial/*')) active @endif">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Comercial <i class="fas fa-angle-left right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{asset('admin/comercial/pedido_finalizado')}}" class="nav-link @if(Request::is('admin/comercial/pedido_finalizado')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pedidos Finalizados</p>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a href="{{asset('admin/comercial/pedido_andamento')}}" class="nav-link @if(Request::is('admin/comercial/pedido_andamento')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pedidos em Andamento</p>
                                        </a>
                                    </li> --}}
                                    {{-- <li class="nav-item">
                                        <a href="{{asset('admin/comercial/produto_vendido')}}" class="nav-link @if(Request::is('admin/comercial/produto_vendido')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Produtos Vendidos</p>
                                        </a>
                                    </li> --}}
                                </ul>
                            </li>
                            <li class="nav-item @if(Request::is('admin/cadastro/*')) menu-open @endif">
                                <a href="#" class="nav-link @if(Request::is('admin/cadastro/*')) active @endif">
                                    <i class="nav-icon fas fa-save"></i>
                                    <p>Cadastros <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cadastro/categoria_menu')}}" class="nav-link @if(Request::is('admin/cadastro/categoria_menu') || Request::is('admin/cadastro/categoria_menu/*')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Categorias/Menus</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cadastro/atributos')}}" class="nav-link @if(Request::is('admin/cadastro/atributos')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Atributos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cadastro/produtos')}}" class="nav-link @if(Request::is('admin/cadastro/produtos')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Produtos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cadastro/promocoes')}}" class="nav-link @if(Request::is('admin/cadastro/promocoes')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Promoções</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item @if(Request::is('admin/cliente/*')) menu-open @endif">
                                <a href="#" class="nav-link @if(Request::is('admin/cliente/*')) active @endif">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Clientes <i class="fas fa-angle-left right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cliente/clientes')}}" class="nav-link @if(Request::is('admin/cliente/clientes')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Clientes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cliente/afiliados')}}" class="nav-link @if(Request::is('admin/cliente/afiliados')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Afiliados</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item @if(Request::is('admin/cupom/*')) menu-open @endif">
                                <a href="#" class="nav-link @if(Request::is('admin/cupom/*')) active @endif">
                                    <i class="nav-icon fas fa-percentage"></i>
                                    <p>Cupons <i class="fas fa-angle-left right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{asset('admin/cupom/cupons')}}" class="nav-link @if(Request::is('admin/cupom/cupons')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Cupons</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{asset('admin/transportes')}}" class="nav-link @if(Request::is('admin/transportes')) active @endif">
                                    <i class="nav-icon fas fa-shipping-fast"></i>
                                    <p>Transportes</p>
                                </a>
                            </li>
                            <li class="nav-header">Configurações da Conta</li>
                            <li class="nav-item">
                                <a href="{{asset('admin/perfil')}}" class="nav-link @if(Request::is('admin/perfil')) active @endif">
                                    <i class="nav-icon far fa-user"></i>
                                    <p>Perfil</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{asset('admin/contas')}}" class="nav-link @if(Request::is('admin/contas')) active @endif">
                                    <i class="nav-icon far fa-address-card"></i>
                                    <p>Contas</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <div class="content-wrapper">
                @yield('container')
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- jQuery -->
        <script src="{{asset('plugin/jquery-3.6.0.min.js')}}"></script>
        <!-- MaskJquery -->
        <script src="{{asset('plugin/mask.jquery.js')}}"></script>
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
        <!-- Colorpicker -->
        <script src="{{asset('plugin/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
        <!-- DateRangerPicker -->
        <script src="{{asset('plugin/daterangepicker/daterangepicker.js')}}"></script>
        <!-- ChartJS -->
        <script src="{{asset('plugin/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('plugin/summernote/summernote-bs4.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('plugin/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('plugin/AdminLTE/js/adminlte.min.js')}}"></script>
        {{-- Funções do painel --}}
        <script src="{{asset('painel/painel.funcoes.min.js')}}"></script>
    </body>
</html>