<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>

<style>
    .content-form-category {
        white-space: nowrap;
        background-color: #f0f6fe;
        padding: 10px 10px;
        width: max-content;
        margin: 3px 0 0 auto;
        z-index: 9;
        border-radius: 5px;
    }
    
    .category-name {
        padding: 8px;
        border-color: transparent;
        vertical-align: middle;
        border-radius: 5px;
    }
    .edit_category{ cursor: pointer; }
    .link:hover{ color:#f89e24; }
    .dropdown-menu { top: auto;}

</style>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex"> 
		<div class="card flex-fill p-3">
            <h3 class="mb-0">Categorias</h3>
		    <div class="row align-items-center col-12">
                <div class="text-right p-0 mb-3">
                    <span id="add_category" class="btn bg-orange text-white float-end">Adicionar Categoria</span>
                    
                    <div class="content-form-category mt-5" style="display: none;">
                        <form method="post" action="<?php echo base_url('index.php/admin/add_category') ?>">
                            <input class="category-name" type="text" name="category_name" placeholder="Insira o Nome da Categoria">
                            <button class="btn bg-orange text-white" type="submit">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
			<table id="datatables-dashboard-projects" class="table table-striped my-0">
				<thead>
					<tr>
					      <th scope="col">Id</th>
                <th scope="col">Nome</th>
                <th scope="col">Quantidade de Produtos</th>
                <th class="d-none d-md-table-cell"></th>
                <th class="d-none d-md-table-cell"></th>
					</tr>
				</thead>
			 <tbody>
          <?php if(is_array($categories)){
              foreach($categories as $category){
          ?>
          <tr id="categoria-<?php echo $category["id"] ?>">
                <th scope="row" id="id_category">
                  <div class="media align-items-center">
                    <div class="media-body">
                      <span class="mb-0 text-sm"><?php echo $category["id"] ?></span>
                      <input name="id_category" type="hidden" value="<?php echo $category["id"] ?>">
                    </div>
                  </div>
                </th>
                <th scope="row" class="editable" id="category_nome">
                  <div class="media align-items-center">
                    <div class="media-body">
                      <span class="mb-0 text-sm"><?php echo $category["name"] ?></span>
                      <input style="display: none;" class="form-control form-control-alternative" name="name_category" placeholder="Digite o Nome da Categoria" value="<?php echo $category["name"] ?>">
                    </div>
                  </div>
                </th>
                <th scope="row">
                  <div class="media align-items-center">
                    <div class="media-body">
                        <a href="<?php echo base_url('index.php/admin/all_products')?>" style="text-decoration: none; color:#000">
                            <span class="mb-0 text-sm link"><?php echo $category["qty_products"] ? $category["qty_products"] : 0 ?></span>
                        </a>
                    </div>
                  </div>
                </th>
                <th class="d-none d-md-table-cell">
                  <div class="media align-items-center editando_produto" style="display: none;" >
                    <div class="media-body">
                        <button class="btn bg-orange text-white salvar_category" type="button">Salvar</button>
                    </div>
                  </div>
                </th>
                <td class="d-none d-md-table-cell">
                  <div class="d-inline-block dropdown show">
                      <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static">
                          <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <span class="dropdown-item edit_category">Editar Categoria</span>
                          <a class="dropdown-item" href="<?php echo base_url('index.php/admin/delete_category?id='.$category['id']) ?>">Excluir Categoria</a>
                      </div>
                  </div>
                </td>
            </tr>
          <?php }} ?>
        </tbody>
			</table>
		</div>
	</div>
</div>
<script>
   	$(function() {
		// Datatables basic
		$('#datatables-dashboard-projects').DataTable({
		});
	});
   	
	$(document).ready( function () {
        $('.edit_category').click(function(){
            var row_id = $(this).parent().parent().parent().parent().attr('id');
            $('#' + row_id + ' .editando_produto').show();
            $('#' + row_id + ' .editable input').show();
            $('#' + row_id + ' .editable select').show();
            $('#' + row_id + ' .editable span').hide();
            
            $('#' + row_id + ' .salvar_category').click(function(){
                var id_category = $('#' + row_id + ' #id_category input').val();
                var category_name = $('#' + row_id + ' #category_nome input').val();
                var id_company = $('#' + row_id + ' #id_company input').val();
                // var id_company = $('#' + row_id + ' #id_company select').find('option:selected').val();
                $('#' + row_id + ' .editando_produto').hide();
                $('#' + row_id + ' .editable input').hide();
                $('#' + row_id + ' .editable select').hide();
                $('#' + row_id + ' .editable span').show();
                var inputs_values = [];
                var grupo = {'id':id_category, 'name':category_name, 'id_company':id_company};
                inputs_values.push(grupo);
                console.log(inputs_values);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('index.php/admin/update_category'); ?>",
                    data: {update_category: inputs_values },
                    success: function(data){
                        window.location.reload();
                    }
                });
            });
        });
            
            $('#add_category').click(function(){
               $('.content-form-category').toggle();
            });
        });

</script>

   