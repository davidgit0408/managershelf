<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = \Config\Services::session();
    $success_msg_imp= $session->getFlashdata('success_msg_imp');
    $success_msg= $session->getFlashdata('success_msg');
    $error_msg_imp= $session->getFlashdata('error_msg_imp');
    $error_msg= $session->getFlashdata('error_msg');
?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/mystyle.css') ?>" rel="stylesheet"/>
<style>
.input_name {position: relative; overflow: hidden;line-height: 30px; box-sizing: border-box; font-size: 15px; vertical-align: middle;width: 500px;border: 2px solid #dbdbdb;border-radius: 0;height: 2.2rem;}
.choose {background: #3479ce; border: none; border-radius: 0;  width: 130px; box-sizing: border-box;  height: 2.2rem; background-color: #e7e7e7; transition: all 0.6s; font-size: 15px; vertical-align: middle;cursor:pointer;}
.input_name{cursor:pointer; }
@media (max-width: 442px){#enviar{width: 100px; position: absolute; margin-top: 50px;}} 
</style>

<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header border-0" style="background-color: #fc9700;">
                <div class="row align-items-center col-12 ">
                    <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8" style="background-color: #fc9700;">
                        <h3 class="mb-0 text-white">Importar Produtos</h3>
                    </div>                                        
                    <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-1">
                        <a href="<?php echo base_url('writable/uploads/modelos/Modelo.csv') ?>"><button class="btn btn bg-white float-end m-1" style="white-space: nowrap">Baixar Modelo</button></a>
                    </div>
                </div> 
            </div>
            <div class="card-body mb-5">
                <h6 class="card-subtitle text-muted mt-2">Baixe o modelo de importação e importe os produtos no formato .CSV</h6>
                <hr class="mt-2"/>              
                <form method="post" enctype="multipart/form-data" action="store" class="validation-wizard wizard-circle m-t-40" >
                    <?php if($error_msg_imp){ ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="alert-message">
                            <?php echo $error_msg_imp; ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php }elseif($success_msg_imp){ ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <div class="alert-message">
                                    <?php echo $success_msg_imp; ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="person_type mb-2">Importar CSV</label>
                        <div class="file-field">
                            <input class="file d-none" type="file" name="file" accept=".csv, .xls">
                            <div class="d-flex flex-column align-items-start">
                                <div class="d-flex mb-3" id="input">
                                    <input type="button" class="choose" value="Escolher arquivo">
                                    <input type="text" class="input_name" readonly="readonly">
                                </div>
                                <button id="enviar" class="btn bg-orange text-white" name="submit" value="Upload" type="submit">Enviar</span>
                            </div>
                        </div>
                    </div>        
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.choose').on('click', function() {
        $('.file').trigger('click');
    });

    $('.input_name').on('click', function() {
        $('.file').trigger('click');
    });

    $('.file').on('change', function() {
        var fileName = $(this)[0].files[0].name;    
        $('.input_name').val(fileName);
    });

    $('#enviar').on('click', function() {
        var $txtNome = document.getElementById('input_name');
        if ($txtNome.value.length == 0){ 
            event.preventDefault();
            alert('Nenhum arquivo selecionado!');
        }
    });
})

</script>