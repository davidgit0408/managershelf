<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $company = $companies[0];
    $session = \Config\Services::session();
    $error_msg= $session->getFlashdata('error_msg');
?>
<!DOCTYPE html>
<div class="row">
    <div class="col-md-12 container mt-3">
    	<div class="card">
            <div class="card-header border-0" style="background-color: #fc9700;">
                <div class="row align-items-center col-12 ">
        			<div class="col-8" style="background-color: #fc9700;">
                        <h3 class="mb-0 text-white">Editar Estudo</h3>
                    </div>
                    <div class="col-4 text-right p-0 mt-1"></div>
                </div> 
            </div>
    		<div class="card-body">
    		    <h6 class="mt-2 card-subtitle text-muted ">Informações do Estudo</h6>
                <hr class="mt-2">
    		    <form role="form" method="post" action="<?php echo base_url('index.php/update_company') ?>">
                    <?php if($error_msg){ ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="alert-message"> <?php echo $error_msg; ?></div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <input type="hidden" name="id" class="form-control" value="<?php echo $company['id']?>" >
                        <div class="col-lg-3">
                          <div class="form-group">
                                <label class="mt-2 mb-1 form-control-label" for="name">Nome</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $company['name']?>" required>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                                <label class="mt-2 mb-1 form-control-label" for="name">Qtd. Máx. de Cenários</label>
                                <input type="number" name="qtd_scenarios" class="form-control" value="<?php echo $company['qtd_scenarios']?>" required >
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                                <label class="mt-2 mb-1 form-control-label" for="email">Qtd. Máx. de Pesquisas</label>
                                <input type="number" name="qtd_pesquisa" class="form-control" value="<?php echo $company['qtd_pesquisa']?>" required>
                          </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mt-2 mb-1 form-control-label" for="qtd_pesquisa">Qtd. Máx. de Eye Tracking</label>
                                <input type="number" min='1' name="qtd_eyetracking" class="form-control" placeholder="Quantidade máxima de Eye Tracking" value="<?php echo $company['qtd_eyetracking']?>"  >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mt-3 mb-1 form-control-label" for="link">Link de saída</label>
                                <input type="text" name="link" class="form-control" value="<?php echo $company['link']?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <?php $dt_begin= str_replace("/", "-",$company['dt_begin']); $dt_begin= date('Y-m-d', strtotime($dt_begin)); ?>
                                <label class="mt-3 mb-1 form-control-label" for="dt_begin">Data de ínicio</label>
                                <input type="date" name="dt_begin" id="dt_begin" class="form-control" value="<?php echo $dt_begin ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <?php $dt_end= str_replace("/", "-",$company['dt_end']); $dt_end= date('Y-m-d', strtotime($dt_end)); ?>
                                <label class="mt-3 mb-1 form-control-label" for="dt_end">Data Final</label>
                                <input type="date" name="dt_end" class="form-control" value="<?php echo $dt_end?>" required >
                            </div>
                        </div>
                        <div class="col-lg-12 text-center mt-3">
                    	    <button type="submit" class="btn bg-orange text-white">Salvar</button>
                        </div>
                    </div>
    			</form>
    		</div>
    	</div>
    </div>
</div>	
<script>
if("<?php echo $error_msg; ?>"){
    $('#dt_begin').focus();
}
</script>