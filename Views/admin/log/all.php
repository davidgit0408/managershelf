<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<style>
    @media (max-width: 442px){.page-link {padding: 4px;}} 
</style>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex"> 
		<div class="card flex-fill p-3">
		    <div class="row align-items-center col-12 mb-3">
    			<div class="col-8">
                    <h3 class="mb-0">Logs</h3>
                </div>
            </div>
			<table id="datatables-dashboard-projects" class="table table-striped my-0 ">
				<thead>
					<tr>
					    <th scope="col">ID</th>
                        <th scope="col">Ação</th>
                        <th class="d-none d-md-table-cell">Tipo</th>
                        <th class="d-none d-md-table-cell">Mensagem</th>
                        <th class="d-none d-md-table-cell">Usuário</th>
                        <th class="d-none d-md-table-cell">IP</th>
                        <th class="d-none d-md-table-cell">Data</th>
					</tr>
				</thead>
				<tbody>
				    <?php if(is_array($logs)){foreach($logs as $log){ ?>
    				<tr>
                        <td><?php echo $log["id"] ?></td>
                        <td><?php echo $log["action"] ?></td>
                        <td class="d-none d-md-table-cell"><?php echo $log["type"] ?></td>
                        <td class="d-none d-md-table-cell"><?php echo $log["message"] ?></td>
                        <td class="d-none d-md-table-cell"><?php echo $log["id_user"] ?></td>
                        <td class="d-none d-md-table-cell"><?php echo $log["ip"] ?></td>
                        <td class="d-none d-md-table-cell"><?php echo date('d/m/Y H:i:s', strtotime($log["created_at"]));?></td>                 
                    </tr>
                    <?php }} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
$(function() {
    $('#datatables-dashboard-projects').DataTable({
    });
});
</script>

