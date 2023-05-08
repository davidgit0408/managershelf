<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/simditor/simditor.css")?>" />
<style>
    .dropdown-menu {top: auto; }
    .dropdown-item {padding: .10rem 1.5rem;}
</style>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex"> 
		<div class="card flex-fill p-3">
            <div class="row align-items-center col-12 mb-3">
    			<div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Versões</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
                    <a href="#add_versions" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar Versões</a>
                </div>
            </div>
			<table id="datatables-dashboard-projects" class="table table-striped my-0 ">
				<thead>
					<tr>
					    <th class="d-none d-md-table-cell">>Número da Versão</th>
                        <th scope="col">Nota</th>
                        <th scope="col"></th>
					</tr>
				</thead>
				<tbody>
				    <?php if(is_array($versions)){foreach($versions as $version){ ?>
    				<tr>
                        <td class="d-none d-md-table-cell"><?php echo $version["number"] ?></td>
                        <td><?php echo mb_strimwidth($version['note'], 0, 120, " (...)")?></td>
                        <td class="text-right">
                          <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static"  >
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="<?php echo base_url('index.php/admin/edit_version?id='.$version['id']) ?>">Editar Nota</a>
                                </div>
                          </div>
                        </td>
                    </tr>
                    <?php }} ?>
				</tbody>
			</table>
		</div>
	</div>

    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header" style="background-color: #fc9700;">
                <h3 class="mt-1 text-white">Adicionar Versão</h3>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle text-muted mt-2">Informações da versão</h6>
                <hr class="mt-2"/>
                <form id="add_versions" role="form" method="post" action="<?php echo base_url('index.php/admin/add_version') ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group3">
                              <label class="mt-2 form-control-label" for="name">Número da Versão</label>
                              <input type="text" name="number" class="mt-2 form-control form-control-alternative" placeholder="Número da Versão" required>
                            </div>
                        </div>
                        <div class="form-group modelo-contract">
                            <label class="mt-3">Nota sobre a versão</label>
                            <textarea name="note" id="note" class="form-control form-control-alternative mt-2"></textarea>
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

$(function() {
    $('#datatables-dashboard-projects').DataTable({
    });
});
	
</script>

