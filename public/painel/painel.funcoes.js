$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.textarea').summernote({
        height:300,
        minHeight: null,
        maxHeight: null,
        dialogsInBody: true,
        dialogsFade: false
    });

    $('.date-mask').daterangepicker({
        singleDatePicker: false,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY',
            daysOfWeek: ['dom','seg','ter','qua','qui','sex','sab'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','outubro','Novembro','Dezembro'],
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar'
        }
    });

    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $('[name="post_code"]').mask('00000-000');
    $('[name="number"]').mask('0000000000');
    $('[name="phone1"]').mask('(00) 0000-0000');
    $('[name="phone2"]').mask('(00) 00000-0000');

    var options = {
        onKeyPress: function (cpf, ev, el, op) {
            var masks = ['000.000.000-000', '00.000.000/0000-00'];
            $('[name="cnpj_cpf"]').mask((cpf.length > 14) ? masks[1] : masks[0], op);
        }
    }
    $('[name="cnpj_cpf"]').length > 11 ? $('[name="cnpj_cpf"]').mask('00.000.000/0000-00', options) : $('[name="cnpj_cpf"]').mask('000.000.000-00#', options);

    // Aciona a validação ao sair do input
    $('[name="cnpj_cpf"]').blur(function(){
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
                if(select.is('.entrega')){
                    select.append('<option value="Toda Região">Toda Região</option>');
                }

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

    // Summernote se perde quando esta em um Modal então atualziamos o index dos modais e a função do summernote
    $(document).on("show.bs.modal", '#novoProduto, #editarProduto', function (event) {
        // console.log("Global show.bs.modal fire");
        var zIndex = 1050 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
        }, 0);
    }).on("hidden.bs.modal", '#novoProduto, #editarProduto', function (event) {
        // console.log("Global hidden.bs.modal fire");
        $(".modal:visible").length && $("body").addClass("modal-open");
    });
    $(document).on("show.bs.modal", '#novaCategoria', function (event) {
        // console.log("Global show.bs.modal fire");
        var zIndex = 1051 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
        }, 0);
    }).on("hidden.bs.modal", '#novaCategoria', function (event) {
        // console.log("Global hidden.bs.modal fire");
        $(".modal:visible").length && $("body").addClass("modal-open");
    });

    // modal para o note-modal -- colcando o index do summernote mais alto so para os popovers
    $(document).on("show.bs.modal", '.note-modal', function (event) {
        // console.log("Global show.bs.modal fire");
        var zIndex = 10000 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
        }, 0);
    }).on("hidden.bs.modal", '.note-modal', function (event) {
        // console.log("Global hidden.bs.modal fire");
        $(".modal:visible").length && $("body").addClass("modal-open");
    });
    $(document).on('inserted.bs.tooltip', function (event) {
        // console.log("Global show.bs.tooltip fire");
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var tooltipId = $(event.target).attr("aria-describedby");
        $("#" + tooltipId).css("z-index", zIndex);
    });
    $(document).on('inserted.bs.popover', function (event) {
        // console.log("Global inserted.bs.popover fire");
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var popoverId = $(event.target).attr("aria-describedby");
        $("#" + popoverId).css("z-index", zIndex);
    });

    // Nomes das Imagens nos inputs
    $('.custom-file input').change(function (e) {
        var files = [];
        for (var i = 0; i < $(this)[0].files.length; i++) {
            files.push($(this)[0].files[i].name);
        }
        $(this).next('.custom-file-label').html(files.join(', '));
    });

    // Funções extras da função de salvamento geral
    // Quando da success no ajax
    function funcaoSuccessExtra(data, target){
        switch(target){
            case '#postProductNovaCategoria': // Função do novo/editar Produto para poder criar novas categorias e avisar a view
                var modal_form = '#postEditarProduto'; // Setamos por padrão o modal_form por padrão

                if($('#novoProduto').is(':visible')) modal_form = '#postNovoProduto'; // Caso o modal do novo produto esteja aberto, seta o novo modal_form

                // Verificamos se a categroias principal esta selecionada para que possa criar uma nova categroia principa
                if($(modal_form).find('.main_category option:selected').data('new_category') == 'category_id'){
                    $(modal_form).find('.main_category').empty(); // Limpamos o campo da categoria princpal para que possa fazer a busca novamente
    
                    $(modal_form).find('.main_category').append('<option value="" data-new_category="category_id"> - Nova Categoria - </option>'); // adicionar sempre a opção nova categoria
    
                    var categories = buscaCategoria({category_id: 'S'}); // busca especifica das categorias
    
                    // Lendo os dados recebidos das categorias e setando normalmnte
                    $.each(categories.responseJSON, (key, value) => {
                        $(modal_form).find('.main_category').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
                // Caso a categoria principal não seja encontrada, chama o proximo para gerar categoria
                if($(modal_form).find('.main_category option:selected').data('new_category') !== 'category_id'){
                    let subCategories = $(modal_form).find('.sub_category').val();
                    $(modal_form).find('.sub_category').empty(); // Limpamos a sub catgeoria para prencher com as novas
    
                    var categories = buscaCategoria({parent_id: $(modal_form).find('.main_category option:selected').val()}); // busca especifica das categorias
                    // Lendo os dados recebidos das categorias e setando normalmnte
                    $.each(categories.responseJSON, (key, value) => {
                        $(modal_form).find('.sub_category').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });

                    if(subCategories.length > 0){
                        $(modal_form).find('.sub_category').val(subCategories);
                        $(modal_form).find('.sub_category').trigger('change');
                    }
                }
            break;
            case '#postEditarCategoria':
                // $('.tr-id-'+data.category_id).children().eq(1).text(data.category_name);
            break;
            case '#postEditarProduto':
                $('.tr-id-'+data.product_id).children().eq(1).html('<img width="100px" src="'+data.image_data+'">');
                $('.tr-id-'+data.product_id).children().eq(2).html(data.product_name);
                $('.tr-id-'+data.product_id).children().eq(3).html(data.measured_unit);
                $('.tr-id-'+data.product_id).children().eq(4).html(data.sales_uni);
                $('.tr-id-'+data.product_id).children().eq(5).html(data.value);
                $('.tr-id-'+data.product_id).find('.btn-editar').data('images', data.dados_image);
                $('.tr-id-'+data.product_id).find('.btn-editar').data('dados', data.dados);
            break;
            case '#postEditarPromocao':
                $('.tr-id-'+data.promotion_id).children().eq(2).html(data.value);
                $('.tr-id-'+data.promotion_id).children().eq(3).html(data.start_date);
                $('.tr-id-'+data.promotion_id).children().eq(4).html(data.final_date);
                $('.tr-id-'+data.promotion_id).find('.btn-editar').data('dados', data.dados);
            break;
        }
    }
    // Antes do ajax
    function funcaoEventoExtra(data, target){
        switch(target){
            case '#postProductNovaCategoria': // Uma função antes de dar sucesso para que possamos controlar alguma coisa
                var modal_form = '#postEditarProduto'; // Setamos por padrão o modal_form por padrão

                if($('#novoProduto').is(':visible')) modal_form = '#postNovoProduto'; // Caso o modal do novo produto esteja aberto, seta o novo modal_form

                // Verificando se a categoria principal esta selcionada
                if($(modal_form).find('.main_category option:selected').data('new_category') == 'category_id'){
                    $(modal_form).find('.sub_category').empty(); // limpando a subcategoria para não haver problemas
    
                    $(target).find('[name="parent_id"]').val(""); // limpando o parente ainda na nova categroia quando for a principal
    
                    $(modal_form).find('.sub_category').append('<option value="" data-new_category="parent_id"> - Nova Sub Categoria- </option>');
                }
            break;
            case '#postEditarCategoria':
                
            break;
            case '#postEditarProduto':
                
            break;
            case '#postEditarPromocao':

            break;
        }
    }

    // Função de busca da categorias
    function buscaCategoria(data){
        return $.ajax({
            url: './pesquisa_categoria',
            type: 'POST',
            data: data,
            async: !1,
        });
    }

    $('form').on('submit', function(e){
        e.preventDefault();
        $(this).find('.btn-salvar').trigger('click');
    });

    // Condifgurações
    var Toast = Swal.mixin({
        toast: true,
        position: 'center',
        showConfirmButton: false,
        timer: 4000
    });

    $('.select2').select2();

    // Para as variações do atributo
    $('.img_icon').on("change", function(){
        var form_img = $(this).parent().parent();
        form_img.find('.img-icon').empty();

        var preview = form_img.find('.img-icon');
        var files   = form_img.find('.img_icon').prop('files');

        function readAndPreview(file) {
            // Make sure `file.name` matches our extensions criteria
            if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
                var reader = new FileReader();

                reader.addEventListener("load", function () {
                var image = new Image();
                image.classList = 'rounded';
                image.width = 45;
                image.title = file.name;
                image.src = this.result;
                preview.append( image );
                }, false);

                reader.readAsDataURL(file);
            }
        }

        if (files) {
            [].forEach.call(files, readAndPreview);
        }
    });
    // Para o novo produto quando pegamos a imagem principal e colocamos uma pre visualzação
    $('.img_principal').on("change", function(){
        var form_img = $(this).parent().parent();
        form_img.find('.img-principal').empty();

        var preview = form_img.find('.img-principal');
        var files   = form_img.find('.img_principal').prop('files');

        function readAndPreview(file) {
            // Make sure `file.name` matches our extensions criteria
            if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
                var reader = new FileReader();

                reader.addEventListener("load", function () {
                var image = new Image();
                image.classList = 'rounded';
                image.height = 180;
                image.title = file.name;
                image.src = this.result;
                preview.append( image );
                }, false);

                reader.readAsDataURL(file);
            }
        }

        if (files) {
            [].forEach.call(files, readAndPreview);
        }
    });
    // Para o novo produto quando pegamos a imagens secudnadrias ou multiplas para pre visualização
    $('.img_multipla').on("change", function(){
        var form_img = $(this).parent().parent();
        form_img.find('.img-multipla').empty();

        var preview = form_img.find('.img-multipla');
        var files   = form_img.find('.img_multipla').prop('files');
    
        function readAndPreview(file) {
            // Make sure `file.name` matches our extensions criteria
            if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
                var reader = new FileReader();

                reader.addEventListener("load", function () {
                var image = new Image();
                image.classList = 'rounded mx-1';
                image.height = 80;
                image.title = file.name;
                image.src = this.result;
                preview.append( image );
                }, false);

                reader.readAsDataURL(file);
            }
        }

        if (files) {
            [].forEach.call(files, readAndPreview);
        }
    });

    // Limpa toda a area do modal novo
    $(document).on('click', '[data-toggle="modal"]', function() {
        $('.modal-content').find('.overlay').remove();
    });
    $(document).on('click', '[data-target="#novoProduto"], [data-target="#editarProduto"]', function() {
        $('#postProductNovaCategoria').find('input[name="parent_id"]').val("");

        $('#novoProduto, #editarProduto').find('input[name="has_preparation"]').val('N');
        $('#novoProduto, #editarProduto').find('.atributo-tab').addClass('d-none');

        $('#novoProduto, #editarProduto').find('.overlay').remove();
        $('#novoProduto, #editarProduto').find('input[type="text"]').val('');
        $('#novoProduto, #editarProduto').find('input[type="checkbox"]').prop('checked', false);
        $('#novoProduto, #editarProduto').find('select').val('');
        $('#novoProduto, #editarProduto').find('select').trigger('change');
        $('#novoProduto, #editarProduto').find('.img_principal').parent().find('.custom-file-label').html('Foto Principal');
        $('#novoProduto, #editarProduto').find('.img-principal').empty();
        $('#novoProduto, #editarProduto').find('.img_multipla').parent().find('.custom-file-label').html('Multiplas Foto');
        $('#novoProduto, #editarProduto').find('.img-multipla').empty();
    });
    // Função salva dados gerais
    $(document).on('click', '.btn-salvar', function(){
        // Pegamos os dados do data
        let save_target = $(this).data('save_target');
        let save_route = $(this).data('save_route');
        let update_table = $(this).data('update_table');
        let table_trash = $(this).data('trash');

        // Função extra antes de chamar o ajax para resolver antes de entrar aqui
        funcaoEventoExtra($(save_target).serializeArray(), save_target);

        // Por mais que tenha erro, limpamos para os outros que não tenha
        $(save_target).find('input').removeClass('is-invalid');
        $(save_target).find('.invalid-feedback').remove();

        // Pegamos o parente do id para adicionar um modelo de carregamento
        let modal = $(save_target).parent();
        if(modal.is('.modal-content')) modal.append('<div class="overlay d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync fa-spin"></i></div>');

        $.ajax({
            url: save_route,
            type: "POST",
            data: new FormData($(save_target)[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                // console.log(data);
                // Procuramos a div adcionada recentemente para removemos e fechamos o modal
                $(modal).find('.overlay').remove();
                $(modal).parent().parent().modal('hide');

                $(save_target).find('input[type="text"]').val('');
                $(save_target).find('input, select').attr('readonly', false);

                if(update_table == 'S') if(data.table) $('table tbody').append(data.table); // Inserindo novos dados
                if(update_table == 'S') if(data.tb_up) $('table tbody').find('.tr-id-'+data.tb_id).html(data.tb_up); // Editando dados

                if(table_trash == 'S'){ // Somente quando for apagar
                    if(data.tb_trash) $('table tbody').find('.tr-id-'+data.tb_trash).remove();

                    Toast.fire({
                        icon: 'success',
                        title: 'Os dados foram excluidos com successo!'
                    });
                }else{
                    Toast.fire({
                        icon: 'success',
                        title: 'Os dados foram salvos com successo!'
                    });
                }

                funcaoSuccessExtra(data, save_target);
            },
            error: (err) => {
                // console.log(err);
                $(modal).find('.overlay').remove();

                // Adicionamos os erros numa variavel
                let erro_tags = err.responseJSON.errors;
                // console.log(erro_tags);

                $.each(erro_tags, (key, value) => {
                    let tag = $(save_target).find('[name="'+key+'"]');
                    tag.addClass('is-invalid');

                    tag.parent().append('<div class="invalid-feedback">'+value[0]+'</div>');
                });

                if(err.responseJSON.msg_alert){
                    Swal.fire({
                        icon: err.responseJSON.icon_alert,
                        text: err.responseJSON.msg_alert,
                    });
                }
            }
        });
    });

    // Passar os dados nos campos paranão puxar um por e sim recueprar em json em um atributo
    $(document).on('click', '.btn-editar', function(){
        var target = $(this).data('target'); // qual modal ta sendo acessado
        var dados = $(this).data('dados'); // dados que serão passados aos campos
        var images = $(this).data('images'); // dados que serão passados aos campos

        // Fazemos uma leitura dosa campos
        var data = '';
        $.each(dados, (key, value) => {
            $(target).find('[name="'+key+'"').val(value); // os campos name são iguais aos das colunas vidna do banco
            $(target).find('.'+key).val(value); // quando o campo name por motivos especiais for diferente, pega por class tambem

            $(target).find('._'+key).text(value); // qunado campo for texto

            // Especifico para o modal editarProduto
            if(key == 'description'){
                $(target).find('.note-editable').html(value);
            }

            // Especifico para o modal editarProduto
            if(key == 'value'){
                if(target !== '#editarCupom' && target !== '#editarPromocao'){
                    if(value){
                        $(target).find('[name="'+key+'"').val(parseFloat(value).toFixed(2).toString().replace('.',','));
                    }
                }
            }

            if(key == 'price'){
                if(target !== '#editarCupom'){
                    if(value){
                        $(target).find('[name="'+key+'"').val(parseFloat(value).toFixed(2).toString().replace('.',','));
                    }
                }
            }

            if(key == 'weight'){
                if(target !== '#editarCupom'){
                    if(value){
                        $(target).find('[name="'+key+'"').val(parseFloat(value).toFixed(3).toString().replace('.',','));
                    }
                }
            }

            // Especifico para o modal editarProduto
            if(key == 'has_preparation' && value == 'S'){
                $(target).find('.has_preparation').trigger('click');
            }
            if(key == 'product_category'){
                var sub_category = [];
                for(var i=0; value.length>i; i++){
                    if(value[i].category_pai == 'S'){
                        $(target).find('[name="main_category"]').val(value[i].category_id);
                        $(target).find('.main_category').trigger('change');
                    }

                    if(value[i].category_pai == 'N'){
                        sub_category.push(value[i].category_id);
                    }
                }

                setTimeout(()=>{
                    $(target).find('.sub_category').val(sub_category).trigger('change');
                },1000, sub_category);
            }
            if(key == 'product_attribute'){
                for(var i=0; value.length>i; i++){
                    if(value[i].attribute_id == null) {
                        $(target).find('#edit_icheck_'+value[i].parent_id).trigger('click');
                    }
                    $(target).find('#edit_icheck_'+value[i].attribute_id).trigger('click');
                    $(target).find('[name="attribute['+value[i].attribute_id+'][attribute_value]"]').val(typeof value[i].attribute_value == 'number' ? value[i].attribute_value.toFixed(2).toString().replace('.',',') : '');
                }
            }

            if(target == '#editarCupom'){
                if(key == 'discount_accepted') $(target).find('.discount_accepted').val(JSON.parse(value));
                if(key == 'user_id') $(target).find('.user_id').val(JSON.parse(value));
            }

            // Especifico para promoções
            if(key == 'start_date'){
                var date = value.split('-');
                $(target).find('[name="start_end_date"]').data('daterangepicker').setStartDate(date[2]+'/'+date[1]+'/'+date[0]);
            }
            if(key == 'final_date'){
                var date = value.split('-');
                $(target).find('[name="start_end_date"]').data('daterangepicker').setEndDate(date[2]+'/'+date[1]+'/'+date[0]);
            }

            // especifico atributo
            if(key == 'hexadecimal') {
                if(value) $(target).find('[value="color"]').trigger('click');
                if(value) $(target).find('[name="color"]').trigger('change');
            }
            if(key == 'image') if(value) $(target).find('[value="image"]').trigger('click');
        });

        // Caso teha as imagens ele le e adiconsa
        if(images){
            for(var i=0; images.length>i; i++){
                if(i == 0){
                    $(target).find('.img-principal').append('<img class="rounded" style="height: 180px" src="'+images[i].image+'">');
                }else{
                    $(target).find('.img-multipla').append('<img class="rounded mx-1" style="height: 80px" src="'+images[i].image+'">');
                }
            }
        }

        // Especifico para o modal editarProduto
        $(target).find('.sales_unit').trigger('change');
        $(target).find('[name="post_code"]').trigger('keyup');
        $(target).find('select').trigger('change');
    });

    // Categoria principal em Produtos
    $(document).on('change', '.main_category', function(){
        let modal_form = $(this).parent().parent().parent(); // pegamos o parent dos this, para que não tenha que repetir no editar produto
        modal_form.find('.sub_category').empty(); // limpa o subcategory para não dar append
        
        if($(this).val()){
            var data = buscaCategoria({parent_id: $(this).val()}); // uma função especifica que busca categorias
            modal_form.find('.sub_category').parent().find('div').removeClass('d-none'); // Removendo a classe do botão de nova sub categoria
            modal_form.find('.main_category').parent().find('div').addClass('d-none'); // adicionado a classe do botão nova categoria principal

            $('#postProductNovaCategoria').find('input[name="parent_id"]').val($(this).val()); // quando selelcionado passa o id para o parent_id quando nova sub categoria

            // Lendo os dados da nova sub categoria
            $.each(data.responseJSON, (key, value) => {
                modal_form.find('.sub_category').append('<option value="'+value.id+'">'+value.name+'</option>');
            });
        }else{ // se não tier preenchido vem para ca
            modal_form.find('.sub_category').parent().find('div').addClass('d-none');
            modal_form.find('.main_category').parent().find('div').removeClass('d-none');

            modal_form.find('.sub_category').append('<option value="" data-new_category="parent_id"> - Nova Sub Categoria - </option>');

            $('#postProductNovaCategoria').find('input[name="parent_id"]').val("");
        }
    });
    // SubCategoria em Produtos
    $(document).on('change', '.sub_category', function(){
        let modal_form = $(this).parent().parent().parent(); // pegamos o parent dos this, para que não tenha que repetir no editar produto

        // if($(this).val().length > 1){
        //     modal_form.find('.sub_category').parent().find('div').addClass('d-none');
        // }else{
        //     if($(this).val()[0]) {
        //         modal_form.find('.sub_category').parent().find('div').addClass('d-none');
        //     }else if($(this).val().length == 0){
        //         modal_form.find('.sub_category').parent().find('div').addClass('d-none');
        //     }else{
        //         modal_form.find('.sub_category').parent().find('div').removeClass('d-none');
        //     }
        // }
    });

    // Apagar a Categoria -- especifico categoria
    $(document).on('click', '.btn-excluir-categoria', function(){ // Verificamos os dados passados
        var target = $(this).data('target');
        var dados = $(this).data('dados');

        $(target).find('.modal-title').html('Excluir Categoria <strong>"'+dados.name+'"</strong>'); // Passamos o titulo da categoria
        $(target).find('[name="id"]').val(dados.id); // o id da categoria caso for ser excluida

        $.ajax({ // Busca de produtos que estejam vinculados a categoria ou subcategorias vinculados a categoria
            url: '/admin/cadastro/pesquisa_categoria_produto',
            type: 'POST',
            data: {id: dados.id},
            success: (data)=>{
                // console.log(data);
                $(target).find('.modal-body').empty();

                // Verificando se é produto ou subcategoria vinculada
                if(data.tipo == 'produto'){
                    if(data.dados.length > 0){
                        $(target).find('.modal-footer').find('button').eq(1).addClass('d-none');
                        $(target).find('.modal-body').append('<p>Essa categoria não pode ser excluida porque possui <strong>'+data.dados.length+'</strong> Produtos vinculados!</p>');
                        $(target).find('.modal-body').append('<p>Inative os Produtos vinculados ou altere a Categoria vinculado ao Produto para excluir!</p>');
                    }else{
                        $(target).find('.modal-footer').find('button').eq(1).removeClass('d-none');
                        $(target).find('.modal-body').append('<p>Tem certeza que deseja excluir essa categoria?</p>');
                    }
                }else if(data.tipo == 'categoria'){
                    if(data.dados.length > 0){
                        $(target).find('.modal-footer').find('button').eq(1).addClass('d-none');
                        $(target).find('.modal-body').append('<p>Essa categoria não pode ser excluida porque possui <strong>'+data.dados.length+'</strong> Sub Categorias vinculados!</p>');
                        $(target).find('.modal-body').append('<p>Exclua as Sub Categorias vinculadas para excluir a Principal!</p>');
                    }else{
                        $(target).find('.modal-footer').find('button').eq(1).removeClass('d-none');
                        $(target).find('.modal-body').append('<p>Tem certeza que deseja excluir essa categoria?</p>');
                    }
                }
            }
        });
    });
    // Fazendo a exclusão da categoria
    $(document).on('click', '.btn-confirma-exclusao-categoria', function(){
        let modal = $('#postExcluirCategoria').parent();
        if(modal.is('.modal-content')) modal.append('<div class="overlay d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync fa-spin"></i></div>');
        
        $.ajax({ // Excluir categoria
            url: 'excluir_categoria',
            type: 'POST',
            data: $('#postExcluirCategoria').serialize(),
            success: (data)=>{
                $(modal).find('.overlay').remove();
                $(modal).parent().parent().modal('hide');

                $('.tr-id-'+data.category_id).remove();

                Toast.fire({
                    icon: 'success',
                    title: 'Os dados foram apagados com successo!'
                });
            },
            error: (err)=>{
                $(modal).find('.overlay').remove();
            }
        });
    });

    // Inativar o produto
    $(document).on('click', '.btn-excluir-produto', function(){
        var target = $(this).data('target');
        var dados = $(this).data('dados');

        $(target).find('.product-name').text(dados.name);
        $(target).find('[name="product_id"]').val(dados.id);
    });
    $(document).on('click', '.btn-confirma-exclusao-produto', function(){
        let modal = $('#postProductExcluirProduto').parent();
        if(modal.is('.modal-content')) modal.append('<div class="overlay d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync fa-spin"></i></div>');

        $.ajax({
            url: 'inativar_produto',
            type: 'POST',
            data: $('#postProductExcluirProduto').serialize(),
            success: (data)=>{
                $(modal).find('.overlay').remove();
                $(modal).parent().parent().modal('hide');

                $('.tr-id-'+data.product_id).remove();

                Toast.fire({
                    icon: 'success',
                    title: 'Os dados foram apagados com successo!'
                });
            },
            error: (err)=>{
                $(modal).find('.overlay').remove();
            }
        });
    });

    // Apagar Promoção
    $(document).on('click', '.btn-excluir-promocao', function(){
        var target = $(this).data('target');
        var dados = $(this).data('dados');

        $(target).find('[name="promotion_id"]').val(dados.id);
    });
    $(document).on('click', '.btn-confirma-exclusao-promocao', function(){
        let modal = $('#postPromotionExcluirPromocao').parent();
        if(modal.is('.modal-content')) modal.append('<div class="overlay d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync fa-spin"></i></div>');

        $.ajax({
            url: 'apagar_promocao',
            type: 'POST',
            data: $('#postPromotionExcluirPromocao').serialize(),
            success: (data)=>{
                $(modal).find('.overlay').remove();
                $(modal).parent().parent().modal('hide');

                $('.tr-id-'+data.promotion_id).remove();

                Toast.fire({
                    icon: 'success',
                    title: 'Os dados foram apagados com successo!'
                });
            },
            error: (err)=>{
                $(modal).find('.overlay').remove();
            }
        });
    });

    // Trocar entre categoria ou produto
    $(document).on('click', '[name="product_category"]', function(){
        $('#product_check_form, #category_check_form, #main_category_check_form, #sub_category_check_form').addClass('d-none');

        if($(this).val() == 'product') $('#product_check_form').removeClass('d-none');
        if($(this).val() == 'category') $('#category_check_form').removeClass('d-none');
    });
    // Trocar entre categoria principal ou sub
    $(document).on('click', '[name="categories"]', function(){
        $('#main_category_check_form, #sub_category_check_form').addClass('d-none');

        if($(this).val() == 'main_category') $('#main_category_check_form').removeClass('d-none');
        if($(this).val() == 'sub_category') $('#sub_category_check_form').removeClass('d-none');
    });

    // Trocar entre Imagem e cor
    $(document).on('click', '[name="attribute_check"]', function(){
        var thisKey = $(this).parent().parent().parent().parent();
        thisKey.find('.imagem_check_form').addClass('d-none');
        thisKey.find('.cor_check_form').addClass('d-none');

        if($(this).val() == 'image') thisKey.find('.imagem_check_form').removeClass('d-none');
        if($(this).val() == 'color') thisKey.find('.cor_check_form').removeClass('d-none');
    });

    // Especifico produto quando ouver tempo de preparo
    $(document).on('click', '.has_preparation', function(){
        if($(this).prop('checked')){
            $(this).parent().parent().parent().find('.preparation_time').removeClass('d-none');
            $(this).parent().find('input[name="has_preparation"]').val('S');
        }else{
            $(this).parent().parent().parent().find('.preparation_time').addClass('d-none');
            $(this).parent().find('input[name="has_preparation"]').val('N');
        }
    });

    $(document).on('click', '.icheck_attribute', function(){
        if($(this).prop('checked')){
            $(this).parent().find('.icheck_attribute_input').val('S');
        }else{
            $(this).parent().find('.icheck_attribute_input').val('N');
        }
    });

    // Verificando se o produto é de variação ou não
    $(document).on('change', '[name="product_type"]', function(){
        var this_form = $(this).closest('form');
        if($(this).val() == 'simples'){
            this_form.find('.atributo-tab').addClass('d-none');
        }else if($(this).val() == 'variacoes'){
            this_form.find('.atributo-tab').removeClass('d-none');
        }
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
                                '<div class="col-12 col-md-4"><b>Recebido no Total: </b>'+(parseFloat(value[i].net_received_amount) || 0).toFixed(2).toString().replace('.',',')+'</div>'+
                                '<div class="col-12 col-md-4"><b>Taxa Paga a Operadora: </b>'+value[i].rate_mp+'</div>'+
                                '<div class="col-12 col-md-4"><b>Metodo de Pagamento: </b>'+value[i].payment_method_id+'</div>'+
                                '<div class="col-12 col-md-4"><b>Tipo de Pagamento: </b>'+value[i].payment_type_id+'</div>'+
                                '<div class="col-12 col-md-4"><b>Nome do Pagador: </b>'+value[i].payer_name+'</div>'+
                                '<div class="col-12 col-md-4"><b>CNPJ/CPF do Pagador: </b>'+value[i].payer_cnpj_cpf+'</div>'+
                            '</div>'
                        );
                    }
                break;
                }
            });
    });
});