<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = session();
    $id_user = $session->get('id');
?>
<!DOCTYPE html>
<style>
    .dropdown-menu{top: auto;}
    .dropdown-item{padding: .10rem 1.5rem;}
</style>

<div class="row">
    <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 d-flex">
		<div class="card flex-fill p-3">
            <div class="row align-items-center col-12 mb-3">
    			<div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Estudos</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
                    <a href="new_company" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar estudo</a>
                </div>
            </div>
			<table id="datatables-dashboard-projects" class="table table-striped my-0">
				<thead>
					<tr>
					    <th scope="col">Nome</th>
                        <th scope="col">Qtd. Máx. de Cenários</th>
                        <th class="d-none d-md-table-cell">Qtd. Máx. de Pesquisas</th>
                        <th class="d-none d-md-table-cell">Qtd. Máx. de Eye Tracking</th>
                        <th class="d-none d-md-table-cell">Status</th>
                        <?php if($id_user == 1){ ?><th class="d-none d-md-table-cell">Criador</th><?php } ?>
                        <th class="d-none d-xl-table-cell">Data de inicio</th>
                        <th class="d-none d-xl-table-cell">Data Final</th>
                        <th scope="col"></th>
					</tr>
				</thead>
				<tbody>
                    <?php if(is_array($companies)){foreach($companies as $company){ ?>
                    <tr>
                        <th><?php echo $company['name']; ?></th>
                        <td><?php echo $company['qtd_scenarios'];?></td>
                        <td class="d-none d-md-table-cell"><?php echo $company['qtd_pesquisa']; ?></td>
                        <td class="d-none d-md-table-cell"><?php echo $company['qtd_eyetracking']; ?></td>
                        <td class="d-none d-md-table-cell"><?php echo $company['status']; ?></td>
                        <?php if($id_user == 1){  ?><td class="d-none d-md-table-cell"><?php echo $company['id_user']; ?></td><?php } ?>
                        <td class="d-none d-xl-table-cell"><?php echo date('d/m/Y', strtotime($company['dt_begin'])); ?></td> 
                        <td class="d-none d-xl-table-cell"><?php echo date('d/m/Y', strtotime($company['dt_end'])); ?></td>
                        <td class="text-right">
                            <div class="d-inline-block dropdown show">
                                <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="<?php echo base_url('index.php/edit_company?id_company='.$company['id']) ?>">Gerenciar</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php }}?>
                </tbody>
			</table>
		</div>
	</div>
</div>
<script>
   	$(function() {
		// Datatables basic
		$('#datatables-dashboard-projects').DataTable({
			//responsive: true
		});
		datatablesButtons.buttons().container().appendTo("#datatables-dashboard-projects .col-md-6:eq(0)")
	});
</script>