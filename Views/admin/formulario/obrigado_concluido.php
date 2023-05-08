<link href="/assets/pages/css/client/formulario_pesquisa.css" rel="stylesheet">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12 bg-white px-5 pb-5 pt-3 mt-5 text-center" style="border-radius: 10px;">
            <img src="<?php echo base_url("/assets/img/brand/logo.png") ?>">
            <a href="https://api.whatsapp.com/send?phone=551129963966&text=Ol%C3%A1!%20Finalizei%20a%20minha%20pesquisa.%20Minha%20chave%20pix%20%C3%A9%3A%20<?php echo session()->get("pix") ?>.%20Meu%20token%20%C3%A9%3A%20<?php echo session()->get("token") ?>.">
                <img src="<?php echo base_url("/assets/uploads/theme/Pop 3_10reais.png") ?>" style="max-width: 100%;">
            </a>
            <p>Seu token é: <?php echo session()->get("token") ?></p>
            <p>Guarde seu token para garantir o recebimento da transferência.</p>
            <div id="toast" style="border-radius: 25px;">
                <div id="img"><img style="width: 85px;margin: -30px 0px 0px -40px;position: absolute;" src="<?php echo base_url('/assets/theme/bootstrap5/img/brand/favicon.png') ?>"></div>
                <div id="desc" hidden style="margin-top: -2px; border-radius: 10px 20px 30px; font-size: 14px;">Preencha todos os campos obrigatórios.</div>
            </div>
        </div>
    </div>
</body>

</html>
