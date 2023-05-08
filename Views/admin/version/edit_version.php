<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/simditor/simditor.css")?>" />
<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header" style="background-color: #fc9700;">
                <h3 class="mt-1 text-white">Editar Versão</h3>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle text-muted mt-1">Informações da versão</h6>
                <hr class="mt-2"/>
                <form id="add_versions" role="form" method="post" action="<?php echo base_url('index.php/admin/update_version') ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group3">
                                <label class="mt-2 form-control-label" for="name">Número da Versão</label>
                                <input type="text" name="number" class="mt-2 form-control form-control-alternative" value="<?php echo $version[0]['number'] ?>" required>
                            </div>
                        </div>
                        <div class="form-group modelo-contract">
                            <label class="mt-3">Nota sobre a versão</label>
                            <textarea name="note" id="note" class="form-control form-control-alternative mt-2"><?php echo $version[0]['note'] ?></textarea>
                            <input type="hidden" value="<?php echo $version[0]['id']?>" name="id">
                        </div>
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn bg-orange text-white mt-3">Cadastrar Versão</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url("assets/js/simditor/module.js")?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/simditor/hotkeys.js")?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/simditor/uploader.js")?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/simditor/simditor.js")?>"></script>
<script>
$(document).ready(function(){
    var editor = new Simditor({
        textarea: $('#note')
    });
});
</script>
