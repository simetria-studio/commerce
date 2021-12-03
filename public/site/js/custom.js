$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[name="birth_date"]').mask('00/00/0000');
    $('[name="post_code"]').mask('00000-000');
    $('[name="number"]').mask('0000000000');
    $('[name="phone1"]').mask('(00) 0000-0000');
    $('[name="phone2"]').mask('(00) 00000-0000');
    $('[name="cardNumber"]').mask('0000000000000000');
    $('[name="securityCode"]').mask('000');

    // Card Flag
    $('[name="card_number"]').on("keyup", function(){
        function getCardFlag(cardnumber) {
            var cardnumber = cardnumber.replace(/[^0-9]+/g, '');

            var cards = {
                visa        : /^4[0-9]{12}(?:[0-9]{3})/,
                master      : /^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))/,
                amex        : /^3[47][0-9]{13}/,
                hipercard   : /^(606282\d{10}(\d{3})?)|(3841\d{15})/,
                elo         : /^((((636368)|(627780)|(636505)|(636297)|(506699)|(504175)|(438935)|(457631)|(457632)|(451416)|(50900))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})/,
            };

            for (var flag in cards) {
                if(cards[flag].test(cardnumber)) {
                    return flag;
                }
            }       

            return false;
        }

        var brand = getCardFlag($(this).val());

        if(brand !== false){
            $('[name="card_brand"]').val(brand).trigger('change');
        }else{
            $('[name="card_brand"]').val('').trigger('change');
        }
    });

    $('.select2').select2();

    var options = {
        onKeyPress: function (cpf, ev, el, op) {
            var masks = ['000.000.000-000', '00.000.000/0000-00'];
            $('[name="cnpj_cpf"]').mask((cpf.length > 14) ? masks[1] : masks[0], op);
        }
    }
    var options2 = {
        onKeyPress: function (cpf, ev, el, op) {
            var masks = ['000000000000', '00000000000000'];
            $('[name="cnpj_cpf2"]').mask((cpf.length > 11) ? masks[1] : masks[0], op);
        }
    }
    $('[name="cnpj_cpf"]').length > 11 ? $('[name="cnpj_cpf"]').mask('00.000.000/0000-00', options) : $('[name="cnpj_cpf"]').mask('000.000.000-00#', options);
    $('[name="cnpj_cpf2"]').length > 11 ? $('[name="cnpj_cpf2"]').mask('00000000000000', options2) : $('[name="cnpj_cpf2"]').mask('00000000000#', options2);

    // Aciona a validação ao sair do input
    $('[name="cnpj_cpf"], [name="cnpj_cpf2"]').blur(function(){
        var thiss = $(this);
    
        // O CPF ou CNPJ
        var cpf_cnpj = $(this).val();

        if(cpf_cnpj){
            // Testa a validação
            if ( valida_cpf_cnpj( cpf_cnpj ) ) {
    
            } else {
                Swal.fire({
                    icon: 'error',
                    text: 'CNPJ/CPF informado invalido!',
                }).then((result)=>{
                    // thiss.focus();
                });
            }
        }
    });

    // Busca dos estados
    $(function(){
        if($('[name="state"]')){
            $.ajax({
                url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/',
                type: 'GET',
                success: (data) => {
                    // console.log(data);
                    for(var i=0; data.length>i; i++){
                        $('[name="state"]').append('<option value="'+data[i].sigla+'" data-sigla_id="'+data[i].id+'">'+data[i].sigla+' - '+data[i].nome+'</option>');
                    }
                }
            });
        }
    });

    // Busca das cidades/municipios
    $(document).on('change', '[name="state"]', function(){
        let sigla_id = $(this).find(':selected').data('sigla_id');
        let select = $(this).parent().parent().find('select[name="city"]');

        $.ajax({
            url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/'+sigla_id+'/municipios',
            type: 'GET',
            success: (data) => {
                // console.log(data);
                select.empty();
                select.append('<option value="">::Selecione uma Opção::</option>');

                for(var i=0; data.length>i; i++){
                    select.append('<option value="'+data[i].nome+'">'+data[i].nome+'</option>');
                }
            }
        });
    });

    $('[name="post_code"]').on('keyup blur', function(){
        $(this).parent().parent().find('input, select').attr('readonly', false);
        $(this).parent().parent().find('input, select').trigger('change');

        if($(this).val().length == 9){
            $('.loadCep').removeClass('d-none');
            $.ajax({
                url: '/cep/'+$(this).val(),
                type: 'GET',
                success: (data) => {
                    $('[name="address"]').val(data.logradouro);
                    if(data.logradouro) $('[name="address"]').prop('readonly', true);
                    $('[name="address2"]').val(data.bairro);
                    if(data.bairro) $('[name="address2"]').prop('readonly', true);
                    $('[name="state"]').val(data.uf);
                    if(data.uf) {
                        $('[name="state"]').attr('readonly', true);
                        $('[name="state"]').trigger('change');
                    }
                    setTimeout(() => {
                        $('[name="city"]').val(data.localidade);
                        if(data.localidade) {
                            $('[name="city"]').attr('readonly', true);
                            $('[name="city"]').trigger('change');
                        }
                    }, 800);

                    $('[name="number"]').focus();
                    $('.loadCep').addClass('d-none');
                }
            });
        }
    });
    
    $(".imagem-produto-1").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: ".imagem-produto-2"
    });
    
    $(".imagem-produto-2").slick({
        slidesToShow: 3,
        asNavFor: ".imagem-produto-1",
        dots: true,
        centerMode: true,
        focusOnSelect: true
    });

    $('.popover-dismiss').popover({trigger: 'focus'});

    $(document).on('click', '[data-target="#enderecos"]', function(){
        $('#enderecos').find('[name="id"]').val('');
        $('#enderecos').find('input[type="text"]').val('');
        $('#enderecos').find('input').attr('readonly', false);
        $('#enderecos').find('select').val('');
        $('#enderecos').find('select').attr('readonly', false);

        var dados = $(this).data('dados'); // dados que serão passados aos campos
        $.each(dados, (key, value) => {
            $('#enderecos').find('[name="'+key+'"').val(value); // os campos name são iguais aos das colunas vidna do banco
        });

        $('#enderecos').find('[name="post_code"]').trigger('keyup');
        $('#enderecos').find('select').trigger('change');
    });

    $(document).on('click', '.btn-excluir-address', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var url = $(this).data('url');

        Swal.fire({
            icon: 'error',
            title: 'Apagar Endereço?',
            showCancelButton: true,
            confirmButtonText: 'SIM',
            cancelButtonText: 'NÃO',
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = url+'/'+id;
            }
        });
    });

    // ###############################################################################
    // ##################### Controloando o produto n carrinho #######################
    $(document).on('click', '#comprar_produto', function(){
        var data = {
            originalId:             $('#originalId').val(),
            originalValue:          $('#originalValue').val(),
            promotion:              $('#promotion').val(),
            promotionValue:         $('#promotionValue').val(),
            promotionPorcent:       $('#promotionPorcent').val(),
            originalName:           $('#originalName').val(),
            hasPreparation:         $('#hasPreparation').val(),
            preparationTime:        $('#preparationTime').val(),
            productImage:           $('#productImage').val(),
            productWeight:          $('#productWeight').val(),
            productHeight:          $('#productHeight').val(),
            productWidth:           $('#productWidth').val(),
            ProductLength:          $('#ProductLength').val(),
            originalSalesUnit:      $('#originalSalesUnit').val(),
            customProjectValue:     $('#customProjectValue').val(),
            customProjectWidth:     $('#customProjectWidth').val(),
            customProjectHeight:    $('#customProjectHeight').val(),
            customProjectMeters:    $('#customProjectMeters').val(),
            customValue:            $('#customValue').val(),
            attributes_aux:         [],
            project:                [],
            qty_total:              $('.qty_total').val(),
            note:                   $('#product_ob').val(),
        };

        $('.select-data-attribute.attr-selecionado').each(function(){
            data['attributes_aux'].push($(this).val());
        });

        $('.customModuloProject').each(function(){
            data['project'].push($(this).val());
        });

        $(this).html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');

        $.ajax({
            url: '/carrinhoAdd',
            type: 'POST',
            data: data,
            success: (data) => {
                // console.log(data);
                window.location.href = '/carrinho';
            }
        });
    });

    $(document).on('click', '.btn-delete-product', function(){
        var this_var = $(this);
        var row_id = $(this).data('row_id');
        var repagina = $(this).data('repagina');

        $.ajax({
            url: '/carrinhoRemove',
            type: 'POST',
            data: {row_id: row_id},
            success: (data) => {
                if(repagina == 'sim'){
                    location.reload();
                }else{
                    this_var.parent().parent().remove();
                }
            }
        });
    });
    // ###############################################################################
    // ###############################################################################
    // ###############################################################################

    // ###############################################################################
    // ################### Função especifica de mteros qudrados ######################
    // ###############################################################################
    // Função para calcular o projeto
    function calcula_campo_project(data){
        var width   = data.parent().parent().find('.project-width');
        width       = parseFloat(width.val().replace(',','.')) || 0;
        var height  = data.parent().parent().find('.project-height');
        height      = parseFloat(height.val().replace(',','.')) || 0;
        var square  = data.parent().parent().find('.project-square');
        var modulo  = data.parent().parent().find('.customModuloProject');
        var text_square  = data.parent().parent().find('.text-square');

        var type_calc = $('[name="prodcuct_project"]:checked').val();

        if(width && height){
            if(type_calc == 'centimeters'){
                width = width/100;
                height = height/100;
            }
            var cont_square = width*height;
            square.val(cont_square.toFixed(2));
            modulo.val(width+'|'+height+'|'+cont_square.toFixed(2));

            text_square.html((cont_square.toFixed(2).toString().replace('.',','))+' m²');
        }
    }

    $(document).on('keyup', '.project-width', function(){
        var this_var = $(this);
        calcula_campo_project(this_var);
    });
    $(document).on('keyup', '.project-height', function(){
        var this_var = $(this);
        calcula_campo_project(this_var);
    });

    // Calcula o projeto todo
    $(document).on('click', '.btn-calc-project', function(){
        var custom_width    = 0;
        var custom_height   = 0;
        var custom_square   = 0;
        // Pega os dados fracionado no projeto para juntar em so
        $('.customModuloProject').each(function(){
            var custom = $(this).val().split('|');

            custom_width += parseFloat(custom[0]) || 0;
            custom_height += parseFloat(custom[1]) || 0;
            custom_square += parseFloat(custom[2]) || 0;
        });

        // Pegamos o vaor antigo e tiramos do campo custom value para não dar problema no valor final
        var custom_project_value = parseFloat($('#customProjectValue').val()) || 0;
        var custom_value = parseFloat($('#customValue').val()) || 0;
        $('#customValue').val((custom_value - custom_project_value).toFixed(2));
        var custom_value = parseFloat($('#customValue').val()) || 0;

        // Pegamos o vaor orinal do produto para multiplicar com os metros quadrados somado do projeto
        var originalValue = parseFloat($('#originalValue').val()) || 0;
        if($('#promotion').val() == 'S') originalValue = parseFloat($('#promotionValue').val()) || 0;
        var custom_value2 = custom_square * originalValue;

        // Setamos em cada campos para ser recuperado depois
        $('#customProjectWidth').val(custom_width.toFixed(2));
        $('#customProjectHeight').val(custom_height.toFixed(2));
        $('#customProjectMeters').val(custom_square.toFixed(2));
        $('#customProjectValue').val(custom_value2.toFixed(2));
        $('#customValue').val((custom_value2+custom_value).toFixed(2));

        $('.tela-projeto').html('<h4>'+custom_square.toFixed(2).toString().replace('.',',')+' m² - R$ '+custom_value2.toFixed(2).toString().replace('.',',')+'</h4>');

        var total_attributes = $('.attributes').length;
        var total_attr_selecionado = $('.attributes').find('.attr-selecionado').length;
        if(total_attributes == total_attr_selecionado){
            $('.valor-final').text('R$ '+((custom_value2+custom_value)).toFixed(2).toString().replace('.',','));
            $('#comprar_produto').prop('disabled', false);
        }
    });

    // Verifica se é em metrs ou centirmetros para calculo, zera os campos para nova contagem
    $(document).on('click', '[name="prodcuct_project"]', function(){
        var this_var = $(this).val();
        $('.project-campo-titulo, .project-campo, .btn-add-project-campo').removeClass('d-none');
        $('.project-campo').html(
            '<div class="row py-1">'+
                '<input type="hidden" class="customModuloProject">'+
                '<div class="col-3">'+
                    '<button type="button" class="btn btn-danger btn-sm remove-project-calc"><i class="fas fa-times"></i></button>'+
                '</div>'+
                '<div class="col-3">'+
                    '<input type="text" class="form-control form-control-sm project-width">'+
                '</div>'+
                '<div class="col-3">'+
                    '<input type="text" class="form-control form-control-sm project-height">'+
                '</div>'+
                '<div class="col-3 text-square"></div>'+
            '</div>'
        );

        if(this_var == 'meters'){
            $('.project-width, .project-height').maskMoney({precision: 2, decimal:',', thousands: '.'});
        }else if(this_var == 'centimeters'){
            $('.project-width, .project-height').maskMoney({precision: 2, decimal:'', thousands: ''});
        }

        $('#customProjectWidth').val('');
        $('#customProjectHeight').val('');
        $('#customProjectMeters').val('');
        $('.tela-projeto').empty();
        $('.valor-final').empty();

        var custom_project_value = parseFloat($('#customProjectValue').val()) || 0;
        var custom_value = parseFloat($('#customValue').val()) || 0;
        $('#customValue').val((custom_value - custom_project_value).toFixed(2));

        $('#customProjectValue').val('');

        $('#comprar_produto').prop('disabled', true);
    });

    $(document).on('click', '.btn-add-project-calc', function(){
        $('.project-campo').append(
            '<div class="row py-1">'+
                '<input type="hidden" class="customModuloProject">'+
                '<div class="col-3">'+
                    '<button type="button" class="btn btn-danger btn-sm remove-project-calc"><i class="fas fa-times"></i></button>'+
                '</div>'+
                '<div class="col-3">'+
                    '<input type="text" class="form-control form-control-sm project-width">'+
                '</div>'+
                '<div class="col-3">'+
                    '<input type="text" class="form-control form-control-sm project-height">'+
                '</div>'+
                '<div class="col-3 text-square"></div>'+
            '</div>'
        );

        var this_var = $('[name="prodcuct_project"]:checked').val();
        if(this_var == 'meters'){
            $('.project-width, .project-height').maskMoney({precision: 2, decimal:',', thousands: '.'});
        }else if(this_var == 'centimeters'){
            $('.project-width, .project-height').maskMoney({precision: 2, decimal:'', thousands: ''});
        }
    });

    $(document).on('click', '.remove-project-calc', function(){
        $(this).parent().parent().remove();
    });
    // ###############################################################################
    // ###############################################################################

    // Função para selecionar os atributos do produto
    $(document).on('click', '.select-attribute-bg', function(){
        var this_var = $(this);
        var custom_value = parseFloat($('#customValue').val()) || 0;
        var attr_value = this_var.parent().parent().parent().find('.attr-selecionado');

        
        if(attr_value.length > 0){
            attr_value = attr_value.val().split('|');
            attr_value = parseFloat(attr_value[2]) || 0;
            $('#customValue').val((custom_value - attr_value).toFixed(2));
        }

        this_var.parent().parent().parent().find('.select-attribute').removeClass('active');
        this_var.parent().parent().parent().find('.attr-selecionado').removeClass('attr-selecionado');

        this_var.parent().parent().find('.select-data-attribute').addClass('attr-selecionado');
        this_var.parent().addClass('active');

        var custom_value = parseFloat($('#customValue').val()) || 0;
        var attr_value = (this_var.parent().parent().find('.select-data-attribute').val()).split('|');
        attr_value = parseFloat(attr_value[2]) || 0;

        $('#customValue').val((custom_value + attr_value).toFixed(2));

        var custom_project_value = parseFloat($('#customProjectValue').val()) || 0;

        var total_attributes = $('.attributes').length;
        var total_attr_selecionado = $('.attributes').find('.attr-selecionado').length;
        $('#comprar_produto').prop('disabled', true);
        if(total_attributes == total_attr_selecionado){
            if(custom_project_value !== 0){
                $('.valor-final').text('R$ '+((attr_value+custom_value)).toFixed(2).toString().replace('.',','));
                $('#comprar_produto').prop('disabled', false);
            }
        }
    });

    // ###############################################################################
    // ################ Funções especificas para metros lineares #####################
    // ###############################################################################
    // Verifica se é em metrs ou centirmetros para calculo, zera os campos para nova contagem
    $(document).on('click', '[name="prodcuct_project_linear"]', function(){
        var this_var = $(this).val();
        $('.project-campo').removeClass('d-none');

        if(this_var == 'meters'){
            $('.linear-meters').maskMoney({precision: 2, decimal:',', thousands: '.'});
        }else if(this_var == 'centimeters'){
            $('.linear-meters').maskMoney({precision: 2, decimal:'', thousands: ''});
        }

        $('#customProjectMeters').val('');
        $('.linear-meters').val('');
        $('.tela-projeto').empty();
        $('.valor-final').empty();

        var custom_project_value = parseFloat($('#customProjectValue').val()) || 0;
        var custom_value = parseFloat($('#customValue').val()) || 0;
        $('#customValue').val((custom_value - custom_project_value).toFixed(2));

        $('#customProjectValue').val('');

        $('#comprar_produto').prop('disabled', true);
    });

    $(document).on('click', '.btn-calc-meters', function(){
        var custom_meters = parseFloat($('.linear-meters').val().replace(',','.')) || 0;
        // Pegamos o vaor antigo e tiramos do campo custom value para não dar problema no valor final
        var custom_project_value = parseFloat($('#customProjectValue').val()) || 0;
        var custom_value = parseFloat($('#customValue').val()) || 0;
        $('#customValue').val((custom_value - custom_project_value).toFixed(2));
        var custom_value = parseFloat($('#customValue').val()) || 0;

        // Pegamos o valor original do produto para multiplicar com os metros quadrados somado do projeto
        var originalValue = parseFloat($('#originalValue').val()) || 0;
        if($('#promotion').val() == 'S') originalValue = parseFloat($('#promotionValue').val()) || 0;
        var custom_value2 = custom_meters * originalValue;

        // Setamos em cada campos para ser recuperado depois
        $('#customProjectMeters').val(custom_meters.toFixed(2));
        $('#customProjectValue').val(custom_value2.toFixed(2));
        $('#customValue').val((custom_value2+custom_value).toFixed(2));

        $('.tela-projeto').html('<h4>'+custom_meters.toFixed(2).toString().replace('.',',')+' m - R$ '+custom_value2.toFixed(2).toString().replace('.',',')+'</h4>');

        var total_attributes = $('.attributes').length;
        var total_attr_selecionado = $('.attributes').find('.attr-selecionado').length;
        if(total_attributes == total_attr_selecionado){
            $('.valor-final').text('R$ '+((custom_value2+custom_value)).toFixed(2).toString().replace('.',','));
            $('#comprar_produto').prop('disabled', false);
        }
    });
    // ###############################################################################
    // ###############################################################################

    $(".qty_plus").on("click", function () {
        var plus = parseFloat($(".qty_total").val()) + 1;

        $(".qty_total").val(plus);
    });
    $(".qty_minus").on("click", function () {
        var minus = parseFloat($(".qty_total").val()) || 0;
        if ($(".qty_total").val() >= 2) {
            minus -= 1;
        }

        $(".qty_total").val(minus);
    });

    $(document).on('click', '[name="transport"]', function(){
        var frete_value = parseFloat($(this).val().split('|')[1]) || 0;
        var sub_total = parseFloat($('.sub_total').val()) || 0;

        $('.frete').text('R$ '+frete_value.toFixed(2).toString().replace('.',','));
        $('.total').text('R$ '+(frete_value+sub_total).toFixed(2).toString().replace('.',','));

        $('.next-payment').prop('disabled', false);
    });

    $(document).on('click', '.btn-visualizar', function(){
        var target = $(this).data('target'); // qual modal ta sendo acessado
        var dados = $(this).data('dados'); // dados que serão passados aos campos

        console.log(dados);

        $.each(dados, (key, value) => {
            $(target).find('._'+key).text(value); // quando o campo for texto

            if(key == 'product_value' || key == 'cost_freight' || key == 'total_value'){
                $(target).find('._'+key).text('R$ '+(parseFloat(value) || 0).toFixed(2).toString().replace('.',','));
            }

            switch(key){
                case 'birth_date':
                    var date = value.split('-');
                    $(target).find('._'+key).text(date[2]+'/'+date[1]+'/'+date[0]);
                break;
                case 'order_products':
                    $(target).find('.produtos_pedido').empty();
                    for(var i=0; i < value.length; i++){
                        var attributes = '';
                        for(var ii=0; ii < value[i].attributes.length; ii++){
                            var attr = value[i].attributes[ii].split('|');
                            attributes += '<div class="col-12">'+attr[1]+' - '+(parseFloat(attr[2] || 0).toFixed(2).toString().replace('.',','))+'</div>';
                        }
                        var project = '';
                        for(var ii=0; ii < value[i].project.length; ii++){
                            var attr = value[i].project[ii].split('|');
                            project += '<div class="col-12">L: '+attr[0]+' - A: '+attr[1]+' - M²: '+attr[2]+'</div>';
                        }
                        $(target).find('.produtos_pedido').append(
                            '<div class="row border-bottom my-2">'+
                                '<div class="col-12 col-md-4"><b>Codigo do Produto: </b>'+value[i].product_code+'</div>'+
                                '<div class="col-12 col-md-4"><b>Nome do Produto: </b>'+value[i].product_name+'</div>'+
                                '<div class="col-12 col-md-4"><b>Quantidade: </b>'+value[i].quantity+'</div>'+
                                '<div class="col-12 col-md-4"><b>Tipo de Venda: </b>'+value[i].product_sales_unit+'</div>'+
                                '<div class="col-12 col-md-4"><b>Largura Padrão: </b>'+value[i].product_width+' cm</div>'+
                                '<div class="col-12 col-md-4"><b>Altura Padrão: </b>'+value[i].product_height+' cm</div>'+
                                '<div class="col-12 col-md-4"><b>Comprimento Padrão: </b>'+value[i].product_length+' cm</div>'+
                                '<div class="col-12 col-md-4"><b>Peso Padrão: </b>'+value[i].product_weight+' kg</div>'+
                                '<div class="col-12"><h5>Calculo do projeto</h5></div>'+
                                '<div class="col-12 col-md-4"><b>Largura do Projeto: </b>'+value[i].project_width+'</div>'+
                                '<div class="col-12 col-md-4"><b>Altura do Projeto: </b>'+value[i].project_height+'</div>'+
                                '<div class="col-12 col-md-4"><b>M² do Projeto: </b>'+value[i].project_meters+'</div>'+
                                '<div class="col-12 my-2"><h5>Atributos</h5></div>'+
                                '<div class="col-12 col-md-4"><div class="row">'+attributes+'</div></div>'+
                                '<div class="col-12 my-2"><h5>Projeto</h5></div>'+
                                '<div class="col-12 col-md-4"><div class="row">'+project+'</div></div>'+
                            '</div>'
                        );
                    }
                break;
                case 'shipping_customer':
                    $(target).find('.entrega_pedido').empty();
                    
                    $(target).find('.entrega_pedido').append(
                        '<div class="row border-bottom my-2">'+
                        '<div class="col-12 col-md-6">'+value[0].address+', Nª '+value[0].number+' - '+value[0].address2+'</div>'+
                        '<div class="col-12 col-md-6">'+value[0].city+' / '+value[0].state+' - '+value[0].post_code+'</div>'+
                        '<div class="col-12">'+value[0].phone1+' / '+value[0].phone2+'</div>'+
                        '<div class="col-12 col-md-4"><b>Transportadora: </b>'+value[0].transport+' - <b>Data Estimada: </b> '+value[0].time+' dias</div>'+
                        '</div>'
                    );
                    break;
                case 'payment_order':
                    // console.log(value);
                    $(target).find('.pagamento_pedido').empty();
                    for(var i=0; i < value.length; i++){
                        if(value[i].status == 'approved')
                        $(target).find('.pagamento_pedido').append(
                            '<div class="row border-bottom my-2">'+
                                '<div class="col-12 col-md-4"><b>Total Pago: </b>'+(parseFloat(value[i].total_paid_amount) || 0).toFixed(2).toString().replace('.',',')+'</div>'+
                                '<div class="col-12 col-md-4"><b>Parcelamento: </b>'+value[i].installments+' x '+(parseFloat(value[i].installment_amount) || 0).toFixed(2).toString().replace('.',',')+'</div>'+
                                '<div class="col-12 col-md-4"><b>Metodo de Pagamento: </b>'+value[i].payment_method_id+'</div>'+
                                '<div class="col-12 col-md-4"><b>Nome do Pagador: </b>'+value[i].payer_name+'</div>'+
                                '<div class="col-12 col-md-4"><b>CNPJ/CPF do Pagador: </b>'+value[i].payer_cnpj_cpf+'</div>'+
                            '</div>'
                        );
                    }
                break;
            }
        });
    });

    // ############################
    // Adcionando cupom de desconto
    // ############################
    $(document).on('click', '.btn-add-coupon', function(){
        var thiss = $(this);
        var coupon = thiss.parent().parent().find('input').val();

        if(coupon !== ''){
            thiss.parent().parent().find('input,button').prop('disabled', true);
            thiss.html('<div class="spinner-border" role="status"></div>');

            $.ajax({
                url: '/aplicarCupom',
                type: 'POST',
                data: {coupon},
                success: (data) => {
                    console.log(data);

                    $('.sub_total').val(parseFloat(data.value).toFixed(2));
                    $('.campo-valor-coupon').text(parseFloat(data.desconto).toFixed(2).toString().replace('.',','));
                    $('.total').text(parseFloat(data.value).toFixed(2).toString().replace('.',','));

                    $('.coupon-d').removeClass('d-none');
                    thiss.parent().parent().parent().addClass('d-none');
                    thiss.parent().parent().find('input,button').prop('disabled', false);
                    thiss.html('Adicionar');

                    $('.frete').html('');
                    $('[name="transport"]').prop('checked', false);
                    $('.next-payment').prop('disabled', true);
                },
                error: (err) => {
                    Swal.fire({
                        icon: 'error',
                        title: err.responseJSON.erro
                    });

                    thiss.parent().parent().find('input,button').prop('disabled', false);
                    thiss.html('Adicionar');
                }
            });
        }
    });
});