<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
$id_user = $session->get('id');
?>
<!DOCTYPE html>

<style>
    .dropdown-menu {
        top: auto;
    }

    .dataTables_empty {
        text-align: center;
    }

    .btn {
        white-space: nowrap;
        width: 150px;
    }

    .btn-success,
    .btn-danger {
        width: auto;
    }

    #nav_pag {
        float: right;
    }

    #nav_pag a {
        cursor: pointer
    }

    .btn-check:focus+.btn,
    .btn:focus {
        box-shadow: none;
    }

    .d-flex {
        float: right;
    }

    @media (max-width: 442px) {
        #nav_pag {
            float: none;
        }

        .page-link {
            padding: 7px 8px;
        }

        #div_pag {
            padding-left: 0px;
        }
    }

    th.sortable {
        cursor: pointer;
        position: relative !important;
    }

    th.sortable::after {
        font-family: FontAwesome;
        content: "\2193";
        position: absolute;
        right: 8px;
        color: #000;
    }

    th.sortable.asc::after {
        content: "\2191";
    }

    th.sortable.desc::after {
        content: "\2193";
    }
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
                    <h3 class="mb-0">Base Produtos</h3>
                </div>
            </div>
            <div class="form-group mb-3">
                <div class="d-flex col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4">
                    <input onkeydown="if (event.keyCode == 13){ buscar(this) }" type="text" id="buscar" class="form-control" placeholder="Faça uma busca...">
                    <button type="button" onclick="buscar(this)" class="btn btn-light" style="width:50px"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <table class="table table-striped my-0 ">
                <thead>
                    <tr>
                        <th class="d-none d-md-table-cell"><input class="check form-check-input" onclick="checkAll()" type="checkbox" id="checkAll"></th>
                        <th class="sortable <?php echo ($orderBy == 'NAME') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'NAME')" style="padding: 0px 0px 11px 0px;">Nome</th>
                        <th class="d-none d-md-table-cell">Marca</th>
                        <th class="d-none d-md-table-cell">Categoria</th>
                        <th class="d-none d-md-table-cell">Fabricante</th>
                        <th class="d-none d-md-table-cell">EAN</th>
                        <th class="d-none d-md-table-cell"><button style="background-color: rgb(248, 158, 36);border-color: rgb(248, 158, 36);display: none;justify-content: center;text-align: center;" onclick="importSelectedsProducts()" type="button" id="importAllButton" class="btn btn-warning"></button></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($products)) {
                        foreach ($products as $product) { ?>
                            <tr id="<?php echo $product["id"] ?>">
                                <td>
                                    <?php if (in_array($product["ean"], $ean_array)) { ?>

                                    <?php } else { ?>
                                        <input id="<?php echo $product["id"] ?>" onclick="checkItem()" class="check form-check-input checkItemInput ckeck-id-<?php echo $product["id"] ?>" type="checkbox">
                                    <?php } ?>
                                </td>
                                <td><?php echo $product["name"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $product["brand"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $product["category"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $product["producer"] ?> </td>
                                <td><?php echo $product["ean"] ?> </td>
                                <td class="d-none d-md-table-cell">
                                    <?php if (in_array($product["ean"], $ean_array)) { ?>
                                        <button style="background-color: #babec2;border-color: #babec2;" type="button" class="btn btn-warning" disabled>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                            </svg>
                                            Importado
                                        </button>
                                    <?php } else { ?>
                                        <button style="background-color: #f89e24;border-color: #f89e24;" onclick="importProduct(<?php echo $product['id'] ?>)" type="button" class="btn btn-warning btn-import imported-id-<?php echo $product['id'] ?>">Importar Produto</button>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php }
                    }
                    if ($total == 0) { ?>
                        <tr class="odd">
                            <td valign="top" colspan="8" class="dataTables_empty">Nenhum registro.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="row align-items-center col-12 mb-2 mt-2">
                <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                    <span><?php echo $total ?> resultados</span>
                </div>
                <div id="div_pag" class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-6 mt-2">
                    <nav aria-label="Page navigation example" id="nav_pag">
                        <ul class="pagination ">
                            <?php if ($max_pages == 0) { ?>
                                <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                                <li class="page-item disabled"><a class="page-link">Próximo</a></li>
                            <?php } else if ($max_pages == 1) { ?>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                            <?php } else if ($max_pages == 2 && $pc == $max_pages) { ?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                            <?php } else if ($max_pages == 2) { ?>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php } else if ($max_pages == 3 && $pc == 1) { ?>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo_1 ?>)" class="page-link"><?php echo $proximoString_1 ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php } else if ($max_pages == 3 && $pc == $max_pages) { ?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                            <?php } else if ($max_pages == 3) { ?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php } else if ($pc == $max_pages) { ?>
                                <li class="page-item"><a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <li class="page-item"><a onclick="mudarURL('pagina', 1)" class="page-link" tabindex="-1">1</a> </li>
                                <li class="page-item disabled"><a class="page-link">...</a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item disabled"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php } else if ($pc == 1) { ?>
                                <li class="page-item disabled"> <a class="page-link" tabindex="-1">Anterior</a> </li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo_1 ?>)" class="page-link"><?php echo $proximoString_1 ?> </a></li>
                                <li class="page-item disabled"><a class="page-link">...</a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $max_pages ?>)" class="page-link"><?php echo $max_pages ?> </a></li>
                                <li class="page-item"> <a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link">Próximo</a> </li>
                            <?php } else { ?>
                                <li class="page-item"> <a class="page-link" tabindex="-1" onclick="mudarURL('pagina', <?php echo $anterior ?>)">Anterior</a> </li>
                                <?php if ($pc >= 4) { ?>
                                    <li class="page-item"><a onclick="mudarURL('pagina', 1)" class="page-link" tabindex="-1">1</a> </li>
                                    <li class="page-item disabled"><a class="page-link">...</a></li>
                                <?php } ?>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $anterior ?>)" class="page-link"><?php echo $anteriorString ?></a></li>
                                <li class="page-item active"><a class="page-link"><?php echo $pc ?> <span class="sr-only">(current)</span></a></li>
                                <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $proximo ?>)" class="page-link"><?php echo $proximoString ?> </a></li>
                                <?php if ($pc < ($max_pages - 1)) { ?>
                                    <li class="page-item disabled"><a class="page-link">...</a></li>
                                    <li class="page-item"><a onclick="mudarURL('pagina', <?php echo $max_pages ?>)" class="page-link"><?php echo $max_pages ?> </a></li>
                                <?php } ?>
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
    function remove_product(product) {
        let id = $(product).data('id');
        $('.btn-success').attr('data-id', id);
        let name = $(product).data('name');
        $('#certeza').text('Tem certeza que deseja deletar  ' + name + ' ?');
    }

    function deleta(product) {
        let id = $(product).data('id');
        window.location.href = "<?php echo base_url('index.php/delete_product?id=') ?>" + id;
    }

    let order = "<?php echo $order ? $order : 'DESC' ?>";

    function buscar(event) {
        let dataFormatada;
        let data;
        let valor = $('#buscar').val();

        if (valor.includes('/')) {
            data = valor.split('/');
            dataFormatada = data[2] + "-" + data[1] + "-" + data[0];
        } else if (valor.includes('#')) {
            dataFormatada = valor.substr(1);
        } else {
            dataFormatada = valor;
        }

        var newUrl = updateURLParameter(window.location.href, 'pagina', 1)
        window.location.href = updateURLParameter(newUrl, 'pesquisa', dataFormatada)
    }

    function mudarOrdenacao(param, paramVal) {
        order = (order == 'DESC') ? 'ASC' : 'DESC';
        var newUrl = updateURLParameter(window.location.href, 'order', order)
        window.location.href = updateURLParameter(newUrl, param, paramVal)
    }

    function mudarURL(param, paramVal) {
        window.location.href = updateURLParameter(window.location.href, param, paramVal)
    }

    function updateURLParameter(url, param, paramVal) {
        var TheAnchor = null;
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";

        if (additionalURL) {
            var tmpAnchor = additionalURL.split("#");
            var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
            if (TheAnchor)
                additionalURL = TheParams;

            tempArray = additionalURL.split("&");

            for (var i = 0; i < tempArray.length; i++) {
                if (tempArray[i].split('=')[0] != param) {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        } else {
            var tmpAnchor = baseURL.split("#");
            var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];

            if (TheParams)
                baseURL = TheParams;
        }

        if (TheAnchor)
            paramVal += "#" + TheAnchor;

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }
</script>
<script>
    // ---------------------- Config de importacao ----------------------

    var buttonText = document.querySelector("#importAllButton")
    // Check o produto especifico importado
    const checkItem = () => {
        var checkedItem = document.querySelectorAll('.checkItemInput');
        var count = 0;
        for (i in checkedItem) {
            if (checkedItem[i].checked === true) {
                if (isNaN(checkedItem[i].id) === false) {
                    count++
                }
            }
        }
        buttonText.innerHTML = `Importar (${count})`
        if (count === 0) {
            buttonText.style.display = 'none'
        } else {
            buttonText.style.display = 'flex'
        }
    }
    // Check todos os produtos importados
    const checkAll = () => {
        var checkboxes = document.querySelectorAll('.check');
        var check = document.querySelector("#checkAll").checked
        var count = 0;
        for (i in checkboxes) {
            checkboxes[i].checked = check
            if (isNaN(checkboxes[i].id) === false) {
                count++
            }
        }
        if (check === true) {
            buttonText.innerHTML = `Importar (${count})`
            buttonText.style.display = 'flex'
        } else {
            buttonText.style.display = 'none'
        }
    }

    // marca o botao de importar produto como importado
    const setCheckedImpoted = (id) => {
        var importedProduct = document.querySelector(`.imported-id-${id}`);
        var removeCheckbox = document.querySelector(`.ckeck-id-${id}`);
        buttonText.style.display = 'none'
        removeCheckbox.remove();
        importedProduct.style.backgroundColor = "#babec2";
        importedProduct.style.borderColor = "#babec2";
        importedProduct.disabled = true
        importedProduct.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                    </svg> Importado`;
    }

    function montarPost(object) {
        var settings = {
            "url": `<?php echo base_url('index.php/bigdata_import'); ?>/${JSON.stringify(object)}`,
            "method": "POST"
        };
        return settings
    }

    // Importa um produto por vez
    const importProduct = (id) => {
        $.ajax(montarPost([id])).done(function(response) {
            setCheckedImpoted(id);
        });
    }

    // importa todos os produtos selecionados de uma vez
    const importSelectedsProducts = () => {
        var toImport = [];
        var checkboxes = document.querySelectorAll('.check');
        var check = document.querySelector("#checkAll").checked
        for (i in checkboxes) {
            checkboxes[i].checked = check
            if (isNaN(checkboxes[i].id) === false) {
                toImport.push(checkboxes[i].id)
            }
        }
        $.ajax(montarPost(toImport)).done(function(response) {
            for (i in toImport) {
                setCheckedImpoted(toImport[i])
            }
        });
    }
</script>
