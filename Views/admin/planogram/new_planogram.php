<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header" style="background-color: #fc9700;">
                <h3 class="mt-1 text-white">Adicionar Planograma</h3>
            </div>
            <div class="card-body">
                <h6 class="mt-2 card-subtitle text-muted ">Informações do planograma</h6>
                <hr class="mt-2">
                <form id="dropzone-form" role="form" method="post" action="<?php echo base_url('index.php/add_planogram') ?>">
                    <input type="hidden" name="release_flag" value="<?php echo $release_flag; ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="mt-2 mb-1 form-control-label" for="name">Nome</label>
                                <input type="text" name="name" class="form-control form-control-alternative" placeholder="Nome do Planograma" value="" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mt-2 mb-1 form-control-label" for="category">Categoria</label>
                            <input type="text" name="category" class="form-control form-control-alternative" placeholder="Nome da Categoria" value="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mt-2 mb-1 form-control-label" for="location">Localização</label>
                            <input type="text" name="location" class="form-control form-control-alternative" placeholder="Defina a Localização" value="" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="mt-2 mb-1 form-control-label" for="location">Planogram_type</label>
                            <input type="text" name="planogram_type" class="form-control form-control-alternative" placeholder="Defina a Planogram type" value="" required>
                        </div>
                        <div class="col-lg-12 text-center mt-4">
                            <button type="submit" class="btn bg-orange text-white">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
