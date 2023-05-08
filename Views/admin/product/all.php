<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = session();    
    $id_user = $session->get('id');
?>
<!DOCTYPE html>

<style>
    .dropdown-menu { top: auto; }
    .dataTables_empty{text-align:center;}
    .btn{white-space:nowrap; width:150px;}
    .btn-success, .btn-danger{width:auto;}
    #nav_pag{float:right;}
    #nav_pag a{cursor:pointer}
    .btn-check:focus+.btn, .btn:focus {box-shadow: none;}
    .d-flex {float: right;}
    @media (max-width: 442px){#nav_pag {float: none;} .page-link {padding: 7px 8px;} #div_pag{padding-left:0px;}} 
    th.sortable { cursor: pointer; position: relative !important;}
    th.sortable::after { font-family: FontAwesome; content: "\2193"; position: absolute; right: 8px; color: #000;}
    th.sortable.asc::after {content: "\2191";}
    th.sortable.desc::after { content:"\2193";}
</style>
<?php
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : null;
    if (!$pagina) $pc = 1;
    else $pc = $pagina; 

    $anterior = $pc - 1;
    $proximo = $pc + 1;
    $proximo_1 = $pc + 2;

    $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : null;
    $order = isset($_GET['order']) ? $_GET['order'] : null;

    $anteriorString = $anterior;
    $proximoString = $proximo;
    $proximoString_1 = $proximo_1;
    
    $max_pages = ceil($total / $total_reg);
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex"> 
		<div class="card flex-fill p-3">
		    <div class="row align-items-center col-12 mb-3">
    			<div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Produtos</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-1">
                    <a href="new_product" class="col-5 btn bg-orange text-white float-end m-1" >Adicionar Produto</a>
                    <a href="import_export" class="col-5 btn btn-github text-white float-end m-1" >Importar/Exportar</a>
                </div>
            </div>
            <div class="form-group mb-3">
                <div class="d-flex col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4">
                    <input onkeydown="if (event.keyCode == 13){ buscar(this) }" type="text" id="buscar" class="form-control" placeholder="Faça uma busca..." >
                    <button type="button" onclick="buscar(this)" class="btn btn-light" style="width:50px"><i class="fas fa-search"></i></button>
                </div>
            </div>     
			<table class="table table-striped my-0 ">
				<thead>
					<tr>
                        <th class="d-none d-md-table-cell sortable <?php echo ($orderBy == 'ID') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'ID')">ID</th>
                        <th class="sortable <?php echo ($orderBy == 'NAME') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'NAME')">Nome</th>
                        <th class="d-none d-md-table-cell">Marca</th>
                        
                        <th class="d-none d-md-table-cell">Categoria</th>
                        <th class="d-none d-md-table-cell">Fabricante</th>
                        <th class="sortable <?php echo ($orderBy == 'EAN') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'EAN')">EAN</th>
                        <?php if($id_user == 1){?><th class="d-none d-md-table-cell">Criador</th><?php } ?>
                        <th class="d-none d-md-table-cell"></th>
					</tr>
				</thead>
				<tbody>
				    <?php if(is_array($products)){ foreach($products as $product){?>
    				<tr>
                        <td class="d-none d-md-table-cell"><?php echo $product["id"] ?></td>
                        <td><?php echo $product["name"] ?> </td>
                        <td class="d-none d-md-table-cell"><?php echo $product["brand"] ?> </td>
                        
                        <td class="d-none d-md-table-cell"><?php echo $product["categoria"] ?> </td>
                        <td class="d-none d-md-table-cell"><?php echo $product["producer"] ?> </td>
                        <td><?php echo $product["ean"] ?> </td>
                        <?php if($id_user == 1){?><td class="d-none d-md-table-cell"><?php echo $product["id_user"] ?></td><?php } ?>
                        <td class="d-none d-md-table-cell">
                            <div class="d-inline-block dropdown show">
                                <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static"  >
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="<?php echo base_url('index.php/edit_product/'.$product['id']) ?>">Editar Produto</a>
                                    <a class="dropdown-item" onclick="remove_product(this)" href="#" data-id="<?php echo $product['id']?>" data-name="<?php echo $product['name']?>" data-bs-toggle="modal" data-bs-target="#delete">Excluir Produto</a>
                                </div>
                          </div>
                        </td>
                    </tr>
                    <?php }}if($total == 0){ ?>
                        <tr class="odd">
                            <td valign="top" colspan="8" class="dataTables_empty">Nenhum registro.</td>
                        </tr>
                    <?php } ?>
				</tbody>
			</table>
            
             <!-- Modal confirmação ao deletar produto -->
             <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar produto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body m-3" >
                            <div class="text-center">
                                <h4 id="certeza" class="mb-4"></h4> 
                                <button type="button" class="btn btn-success" onclick="deleta(this)" data-id="" >SIM</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">NÃO</button>
                            </div>          
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center col-12 mb-2 mt-2"> 
                <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                    <span><?php echo $total?> resultados</span>
                </div>
                <div id="div_pag" class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-6 mt-2">
                    <nav aria-label="Page navigation example" id="nav_pag">
                        <ul class="pagination ">
                            <?php if($max_pages == 0){?>
                                <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                                <li class="page-item disabled"><a class="page-link">Próximo</a></li>
                            <?php }else if($max_pages == 1){?>
                                 <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                            <?php }else if($max_pages == 2 && $pc == $max_pages){?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                            <?php }else if($max_pages == 2){?>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php }else if($max_pages == 3 && $pc == 1){?>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo_1 ?>)"  class="page-link"><?php echo $proximoString_1 ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php }else if($max_pages == 3 && $pc == $max_pages){?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                            <?php }else if($max_pages == 3){?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php }else if($pc == $max_pages){?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', 1)"  class="page-link" tabindex="-1" >1</a> </li>
                                <li class="page-item disabled"><a class="page-link">...</a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item disabled"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php }else if($pc == 1){?>
                                <li class="page-item disabled">  <a class="page-link" tabindex="-1" >Anterior</a> </li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo_1 ?>)"  class="page-link"><?php echo $proximoString_1 ?> </a></li>
                                <li class="page-item disabled"><a class="page-link">...</a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $max_pages ?>)" class="page-link"><?php echo $max_pages ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php }else{?>
                                <li class="page-item">  <a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <?php if($pc >= 4){?>
                                    <li class="page-item"><a onclick="mudarURL('pagina', 1)"  class="page-link" tabindex="-1" >1</a> </li>
                                    <li class="page-item disabled"><a class="page-link">...</a></li>
                                <?php }?>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <?php if($pc < ($max_pages - 1 )){?>
                                    <li class="page-item disabled"><a class="page-link">...</a></li>
                                    <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $max_pages ?>)" class="page-link"><?php echo $max_pages ?> </a></li>
                                <?php }?>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php } ?>
                        </ul>
                    </nav> 
                </div> 
            </div>
		</div>
	</div>
</div>

<script>
function remove_product(product){
    let id = $(product).data('id');
    $('.btn-success').attr('data-id', id);
    let name = $(product).data('name');
    $('#certeza').text('Tem certeza que deseja deletar  '+name+' ?');
}

function deleta(product){
    let id = $(product).data('id');
    window.location.href= "<?php echo base_url('index.php/delete_product?id=') ?>"+id;
}

let order = "<?php echo $order ? $order : 'DESC' ?>";

function buscar(event){
    let dataFormatada;
    let data;
    let valor = $('#buscar').val();
   
    if(valor.includes('/')){
        data = valor.split('/');
        dataFormatada = data[2] + "-" + data[1] + "-" + data[0];
    }else if(valor.includes('#')){
        dataFormatada = valor.substr(1);
    }else{
        dataFormatada = valor;
    }
   
    var newUrl = updateURLParameter(window.location.href, 'pagina', 1)
    window.location.href = updateURLParameter(newUrl, 'pesquisa', dataFormatada)
}

function mudarOrdenacao(param, paramVal){
    order = (order == 'DESC') ? 'ASC' : 'DESC';
    var newUrl = updateURLParameter(window.location.href, 'order', order)
    window.location.href = updateURLParameter(newUrl, param, paramVal)
}

function mudarURL(param, paramVal){
    window.location.href = updateURLParameter(window.location.href, param, paramVal)
}

function updateURLParameter(url, param, paramVal){
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL) 
    {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
        if(TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (var i=0; i<tempArray.length; i++)
        {
            if(tempArray[i].split('=')[0] != param)
            {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }        
    }
    else
    {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor  = tmpAnchor[1];

        if(TheParams)
            baseURL = TheParams;
    }

    if(TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}
</script>