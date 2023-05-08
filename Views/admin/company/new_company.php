<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = \Config\Services::session();
$error_msg = $session->getFlashdata('error_msg');
$company_values = $session->getFlashdata('company_values');
?>
<!DOCTYPE html>

<div class="row">
  <div class="col-md-12 container mt-3">
    <div class="card">
      <div class="card-header border-0" style="background-color: #fc9700;">
        <div class="row align-items-center col-12 ">
          <div class="col-8" style="background-color: #fc9700;">
            <h3 class="mb-0 text-white">Adicionar Estudo</h3>
          </div>
          <div class="col-4 text-right p-0 mt-1"></div>
        </div>
      </div>
      <div class="card-body">
        <h6 class="mt-2 card-subtitle text-muted ">Informações do Estudo</h6>
        <hr class="mt-2">
        <form role="form" method="post" action="<?php echo base_url('index.php/add_company') ?>">
          <div class="row">
            <?php if ($error_msg) { ?>
              <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="alert-message"> <?php echo $error_msg; ?></div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php } ?>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-2 mb-1 form-control-label" for="name">Nome</label>
                <input type="text" name="name" class="form-control" value="<?php echo empty($company_values['name']) ? '' : $company_values['name']; ?>" placeholder="Nome" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-2 mb-1 form-control-label" for="qtd_scenarios">Qtd. Máx. de Cenários</label>
                <input type="number" min='1' name="qtd_scenarios" class="form-control" value="<?php echo empty($company_values['qtd_scenarios']) ? '' : $company_values['qtd_scenarios']; ?>" placeholder="Quantidade máxima de cenários" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-2 mb-1 form-control-label" for="qtd_pesquisa">Qtd. Máx. de Pesquisas</label>
                <input type="number" min='1' name="qtd_pesquisa" class="form-control" value="<?php echo empty($company_values['qtd_pesquisa']) ? '' : $company_values['qtd_pesquisa']; ?>" placeholder="Quantidade máxima de pesquisas" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-2 mb-1 form-control-label" for="qtd_pesquisa">Qtd. Máx. de Eye Tracking</label>
                <input type="number" min='1' name="qtd_eyetracking" class="form-control" value="<?php echo empty($company_values['qtd_eyetracking']) ? '' : $company_values['qtd_eyetracking']; ?>" placeholder="Quantidade máxima de Eye Tracking">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="mt-3 mb-1 form-control-label" for="link">Link de saída</label>
                <div style="display: inline-block;" id="info" title="Link para o qual o usuário será redirecionado após concluir o fluxo de compra." data-toggle="tooltip" data-placement="top"><i class="fas fa-info-circle"></i></div>
                <input type="text" name="link" class="form-control" value="<?php echo empty($company_values['link']) ? '' : $company_values['link']; ?>" placeholder="Insira o link">
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-3 mb-1 form-control-label" for="dt_begin">Data de ínicio</label>
                <input type="date" name="dt_begin" id="dt_begin" class="form-control" value="<?php echo empty($company_values['dt_begin']) ? '' : $company_values['dt_begin']; ?>" placeholder="Insira a data de ínicio do estudo " required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-3 mb-1 form-control-label" for="dt_end">Data Final</label>
                <input type="date" name="dt_end" id="dt_end" class="form-control" value="<?php echo empty($company_values['dt_end']) ? '' : $company_values['dt_end']; ?>" placeholder="Insira a data final do estudo" required>
              </div>
            </div>
              <? if (in_array("TESTE_COMPRA", $permissions)) { ?>
                 <div class="col-lg-3 mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="test" value="yes" id="test">
                    <label class="form-check-label" for="test">
                      Simulação de compra
                    </label>
                    <div style="display: inline-block;" id="info" title="O valor do estudo sera de R$1,00" data-toggle="tooltip" data-placement="top"><i class="fas fa-info-circle"></i></div>
                  </div>
              </div>
                  <? }?>
              <div class="col-lg-12 text-center mt-3">
                <button type="submit" class="btn bg-orange text-white">Cadastrar</button>
              </div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  })
  if ("<?php echo $error_msg; ?>") {
    $('#dt_begin').focus();
  }
</script>
