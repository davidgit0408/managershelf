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
                    <h3 class="mb-0">Entrevistados</h3>
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
                        <th class="d-none d-md-table-cell">Nome</th>
                        <th class="d-none d-md-table-cell">CPF</th>
                        <th class="d-none d-md-table-cell">Telefone</th>
                        <th class="sortable <?php echo ($orderBy == 'GENERO') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'GENERO')">Gênero</th>
                        <th class="sortable <?php echo ($orderBy == 'IDADE') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'IDADE')">Idade</th>
                        <th class="sortable <?php echo ($orderBy == 'ESTADO') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'ESTADO')">Estado</th>
                        <th class="d-none d-md-table-cell">Valor Gasto por mês</th>
                        <th class="sortable <?php echo ($orderBy == 'CIDADE') ? strtolower($order) : '' ?>" onclick="mudarOrdenacao('orderBy', 'CIDADE')">Cidade</th>
                        <th class="d-none d-md-table-cell">Profissão</th>
                        <th class="d-none d-md-table-cell">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($persons)) {
                        foreach ($persons as $person) { ?>
                            <tr>
                                <td class="d-none d-md-table-cell"><?php echo $person["name"] ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $person["cpf"] ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $person["telefone"] ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $person["genero"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $person["idade"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $person["estado"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $person["valor_compra"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $person["cidade"] ?> </td>
                                <td class="d-none d-md-table-cell"><?php echo $person["profissao"] ?> </td>
                                <td class="d-none d-md-table-cell">
                                    <button onclick='openModalContent(<?php echo $person["id"] ?>)' style="width: 80px;height: 31px;background-color: #f89e24;" type="button" class="btn btn-warning">Ver tudo</button>
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
            <div id="modalInfoPerson"></div>
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
    function openModalContent(id) {
        $('#modalInfoPerson').html('');

        var settings = {
            "url": `http://localhost/ManagerShelf/index.php/form/get_interview_person?id=${id}`,
            "method": "GET",
        };

        $.ajax(settings).done(function(result) {

            var response = JSON.parse(result)

            $('#modalInfoPerson').html(`
                <button hidden id="openModal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalContent">
                </button>
                <div class="modal fade" id="modalContent" tabindex="-1" role="dialog" aria-labelledby="modalContentLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #f89e24;">
                                <h4 class="modal-title" id="modalContentLabel">${response.name}</h4>
                                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <svg style="color: red;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 194px);">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Nome:</td>
                                            <td>${response.name}</td>
                                        </tr>
                                        <tr>
                                            <td>Renda:</td>
                                            <td>${response.renda}</td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td>${response.email}</td>
                                        </tr>
                                        <tr>
                                            <td>Idade:</td>
                                            <td>${response.idade}</td>
                                        </tr>
                                        <tr>
                                            <td>CPF:</td>
                                            <td>${response.cpf}</td>
                                        </tr>
                                        <tr>
                                            <td>Telefone:</td>
                                            <td>${response.telefone}</td>
                                        </tr>
                                        <tr>
                                            <td>CEP:</td>
                                            <td>${response.cep}</td>
                                        </tr>
                                        <tr>
                                            <td>Logradouro:</td>
                                            <td>${response.logradouro}</td>
                                        </tr>
                                        <tr>
                                            <td>Bairro:</td>
                                            <td>${response.bairro}</td>
                                        </tr>
                                        <tr>
                                            <td>Categorias:</td>
                                            <td><div style="margin: 0;width: 140px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">${response.categorias}</div></td>
                                        </tr>
                                        <tr>
                                            <td>Filhos:</td>
                                            <td>${response.filhos}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantos Filhos:</td>
                                            <td>${response.qtd_filhos}</td>
                                        </tr>
                                        <tr>
                                            <td>Idade dos Filhos:</td>
                                            <td>${response.age_filhos}</td>
                                        </tr>
                                        <tr>
                                            <td>Intervalo de compras:</td>
                                            <td>${response.compras}</td>
                                        </tr>
                                        <tr>
                                            <td>Valor Gasto nas compras:</td>
                                            <td>${response.valor_compra}</td>
                                        </tr>
                                        <tr>
                                            <td>Cupom:</td>
                                            <td>${response.cupom}</td>
                                        </tr>
                                        <tr>
                                            <td>Profissao:</td>
                                            <td>${response.profissao}</td>
                                        </tr>
                                        <tr>
                                            <td>Reposicao:</td>
                                            <td>${response.reposicao}</td>
                                        </tr>
                                        <tr>
                                            <td>Fluxo online:</td>
                                            <td>${response.fluxo_online}</td>
                                        </tr>
                                        <tr>
                                            <td>Entrevista:</td>
                                            <td>${response.entrevista}</td>
                                        </tr>
                                        <tr>
                                            <td>Notebook:</td>
                                            <td>${response.notebook}</td>
                                        </tr>
                                        <tr>
                                            <td>Webcam:</td>
                                            <td>${response.webcam}</td>
                                        </tr>
                                        <tr>
                                            <td>Outro dispositivo:</td>
                                            <td>${response.outro_dispositivo}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 90px;">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>`);
            document.getElementById('openModal').click();
        });


    }
</script>