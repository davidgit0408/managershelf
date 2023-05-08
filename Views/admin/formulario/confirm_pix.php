<link href="/assets/pages/css/client/formulario_pesquisa.css" rel="stylesheet">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12">
            <form id="msform" onsubmit="return false;">
                <img src="<?php echo base_url("/assets/img/brand/logo.png") ?>">
                <fieldset>
                    <div id="form">
                        <h2 class="fs-title">Confirme seus dados abaixo</h2>
                        <h3 class="fs-subtitle">Infelizmente não conseguimos efetuar seu pix. Confirme seus dados abaixo para tentarmos novamente.</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required />
                            </div>
                            <div class="col-md-6">
                                <label>Sobrenome</label>
                                <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome" required />
                            </div>
                            <div class="col-md-6">
                                <label>Telefone</label>
                                <input type="text" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" class="form-control" id="telefone" name="telefone" placeholder="Telefone" required />
                            </div>
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required />
                            </div>
                            <div class="col-md-6">
                                <label for="genre">Gênero</label>
                                <select id="genre" name="genre" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="age">Qual sua idade?</label>
                                <select id="age" name="age" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="encerrar">24 anos ou menos</option>
                                    <option value="1">Entre 25 e 30 anos</option>
                                    <option value="2">Entre 31 e 40 anos</option>
                                    <option value="3">Entre 41 e 55 anos</option>
                                    <option value="encerrar">56 anos ou mais</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Sua chave PIX</label>
                                <input type="text" class="form-control" id="chave-pix" name="chave-pix" placeholder="Insira sua chave pix" required />
                            </div>
                            <div class="col-md-6">
                                <label for="tipo-pix">Qual o tipo de sua chave pix?</label>
                                <select id="tipo-pix" name="tipo-pix" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="celular">Celular</option>
                                    <option value="email">E-mail</option>
                                    <option value="aleatoria">Chave aleatória</option>
                                    <option value="cpf">CPF/CNPJ</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="submit action-button" style="width: 200px;">Prosseguir</button>
                    </div>
                    <div id="sucesso" class="hidden">
                        Obrigado!
                        Em breve entraremos em contato.
                    </div>
                </fieldset>
            </form>
            <div id="toast" style="border-radius: 25px;">
                <div id="img"><img style="width: 85px;margin: -30px 0px 0px -40px;position: absolute;" src="<?php echo base_url('/assets/theme/bootstrap5/img/brand/favicon.png') ?>"></div>
                <div id="desc" hidden style="margin-top: -2px; border-radius: 10px 20px 30px; font-size: 14px;">Preencha todos os campos obrigatórios.</div>
            </div>
        </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
    <script>

        // ----- Formatadores e validadores -----

        function formatarMoeda() {
            var elemento = document.getElementById('renda_familiar');
            var valor = elemento.value;
            valor = valor + '';
            valor = parseInt(valor.replace(/[\D]+/g, ''));
            valor = valor + '';
            valor = valor.replace(/([0-9]{2})$/g, ",$1");
            if (valor.length > 6) {
                valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
            }
            elemento.value = ((valor.length < 3) ? valor : `R$ ${valor}`);
            if (valor == 'NaN') elemento.value = '';
        }

        function formatarCep() {
            var elemento = document.getElementById('cep');
            var valor = elemento.value;
            if (String(valor).length < 9) {

                valor = valor.replace(/\D/g, "")
                valor = valor.replace(/^(\d{5})(\d)/, "$1-$2")
                elemento.value = valor;
                if (valor == 'NaN') elemento.value = '';
            }
        }


        function mask(o, f) {
            setTimeout(function() {
                var v = mphone(o.value);
                if (v != o.value) {
                    o.value = v;
                }
            }, 1);
        }

        function mphone(v) {
            var r = v.replace(/\D/g, "");
            r = r.replace(/^0/, "");
            if (r.length > 10) {
                r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (r.length > 5) {
                r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (r.length > 2) {
                r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
            } else {
                r = r.replace(/^(\d*)/, "($1");
            }
            return r;
        }
        
        var current_fs, next_fs, previous_fs;
        var left, opacity, scale;
        var animating;

        const checkSelects = (e) => {
            let elementValue = $(e).val();
            if(!elementValue) return;
            $(".rankings select").not(e).each((i, e) => {
                if(elementValue === $(e).val()) $(e).val("").change();
            })
        }

        const checkInputs = () => {

            // --- Verificar inputs vazios na aba ativa antes de continuar ---

            var exist_clear = false
            $(".active_tab")
                .find('input, select, textarea')
                .each(function() {
                    if ($(this).val() === "" || $(this).val() === null) {
                        if ($(this).prop('required')) {
                            $(this).css({
                                'border-color': "red"
                            });
                            exist_clear = true
                        }
                    } else {
                        $(this).css({
                            'border-color': ""
                        });
                    }
                });

            $(".active_tab").find(".checkbox-required").each((index, element) => {
                let size = $(element).find("[type=checkbox]").size();
                let sizeChecked = $(element).find("[type=checkbox]:checked").size();
                if(size > 0 && sizeChecked === 0){
                    $(element).addClass("error");
                    exist_clear = true
                } else {
                    $(element).removeClass("error");
                }
            })

            if(exist_clear) launch_toast();

            return exist_clear;
        }

        $("#msform").submit(function(event) {

            if (checkInputs() || validar_pesquisa()) return;

            var formJqObj = $("#msform");
            var formDataObj = {};
            (function() {
                formJqObj.find(":input")
                    .not("[type='submit']")
                    .not("[type='reset']")
                    .not("[name='next']")
                    .not("[name='previous']")
                    .each(function() {
                        var thisInput = $(this);
                        if (thisInput.attr('type') === "checkbox" && thisInput.is(":checked")) {
                            if(!formDataObj[thisInput.attr("name")]){
                                formDataObj[thisInput.attr("name")] = "";
                            }
                            formDataObj[thisInput.attr("name")] += thisInput.val()+"; ";
                        } else if (thisInput.attr('type') !== "checkbox") {
                            formDataObj[thisInput.attr("name")] = thisInput.val();
                        }
                    });
            })();

            let uuid = $("#email").val();
            let qtd_compra = $("#quantidade_frascos_por_compra").val();
            

            $.ajax({
                type: "POST",
                url: "/index.php/form/save_confirm_pix",
                contentType: "application/json",
                data: JSON.stringify({
                    id_user: uuid,
                    answers: formDataObj
                }),
                success: (s) => {
                    console.log(s);
                    $("form fieldset div#form").hide();
                    $("form fieldset div#sucesso").show("slow");
                },
                error: (e) => {
                    console.log(e);
                }
            });
        });

        let validar_pesquisa = () => {

            let have_errors = false;
            if($(".active_tab").find("[name='trabalha_na_area']").length > 0){
                let trabalha_na_area_size = $("[name='trabalha_na_area']:checked").size();
                let trabalha_na_area_value = $(".nao-trabalha-na-area:checked").is(":checked");
                if( !(trabalha_na_area_size === 1 && trabalha_na_area_value ) ) {
                    console.log("Encerrar por trabalha_na_area");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find("#genre").length > 0){
                let genre = $("#genre").val() == "masculino";
                if(genre) {
                    console.log("Encerrar por genre");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find("#age").length > 0){
                let age = $("#age").val() == "encerrar";
                if(age) {
                    console.log("Encerrar por age");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find(".faixa-economica").length > 0){
                let total = 0;
                $(".faixa-economica").each((index, element) => {
                    let valor = $(element).val();
                    if(valor) total += parseInt(valor);
                });
                $("input#classe_social").val(total)
                if(total < 17) {
                    console.log("Encerrar por total", total);
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find("#quem-escolhe").length > 0){
                let quem_escolhe = $("#quem-escolhe").val();
                if(quem_escolhe == "3" || quem_escolhe == "4") {
                    console.log("Encerrar por quem_escolhe");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find("#usa_em_casa").length > 0){
                let usa_em_casa = $("#usa_em_casa").find(".coloracao").is(":checked");
                if(!usa_em_casa) {
                    console.log("Encerrar por usa_em_casa");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find("#onde-compra-coloracao").length > 0){
                let onde_compra_1 = $(".hidden").find(".farmacia").is(":checked");
                let onde_compra_3 = $(".hidden").find(".supermercado").is(":checked");
                if(!onde_compra_1 && !onde_compra_3) {
                    console.log("Encerrar por onde_compra");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            if($(".active_tab").find("#quais-cores").length > 0){
                let quais_cores_vermelho_value = $(".quais-cores-vermelho").is(":checked");
                let quais_cores_outros_value = $(".quais-cores-outros").is(":checked");
                let quais_cores_size = $("[name='quais-cores']:checked").size();
                console.log(quais_cores_size, quais_cores_outros_value);
                console.log(quais_cores_size, quais_cores_vermelho_value);
                if( (quais_cores_size === 1 && (quais_cores_vermelho_value || quais_cores_outros_value) ) ) {
                    console.log("Encerrar por quais_cores");
                    have_errors = true;
                    window.location.href="/index.php/form/obrigado";
                }
            }

            

            return have_errors;
        }

        function launch_toast() {
            var x = document.getElementById("toast")
            var msg = document.getElementById("desc")
            x.className = "show";
            setTimeout(function() {
                msg.hidden = false;
            }, 600)
            setTimeout(function() {
                msg.hidden = true;
            }, 4000);
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 5000);
        }
    </script>
</body>

</html>
