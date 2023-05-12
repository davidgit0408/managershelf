<?php
error_reporting(E_ALL & ~E_NOTICE);
$planograma = $planograma[0];
$zero = 0;
$share_product = [];
$share_product["planograma"] = $planograma["name"];
for ($c = 1; $c <= $columns_qtd; $c++) {
    $shelves = isset($columns[$c]) ? $columns[$c] : array();
    for ($i = 1; $i <= $shelves_qtd; $i++) {
        $positions = isset($shelves[$i]) ? $shelves[$i] : array();
        $share[$i]["width"] = 0;
        $y = 1;
        if (is_array($positions)) {
            $data_position = 0;
            foreach ($positions as $position) {
                foreach ($products as $k => $product) {
                    if ($position["id_product"] === $product["id"]) {
                        var_dump(111111);
                        $product_total = intval($product["width"]) * intval($position["views"]);

                        $share[$i]["width"] += ($product_total);

                        $product = array_merge($product, array(
                                'created_at', $position['created_at'],
                                'scenario_name', $position['scenario_name'],
                                'qty', $position['qty'],
                                'views', $position['views'],
                                'alert_count', $position['alert_count'],
                        ));
                        $share_product["facing"][$position["id_product"]]["id"] = $position["id_product"];
                        $share_product["facing"][$position["id_product"]]["nome"] = $product["name"];
                        $share_product["facing"][$position["id_product"]]["marca"] = $product["brand"];
                        $share_product["facing"][$position["id_product"]]["img"] = $product["image"];
                        $share_product["facing"][$position["id_product"]]["ean"] = $product["ean"];
                        $share_product["facing"][$position["id_product"]]["created_at"] = $position['created_at'];
                        $share_product["facing"][$position["id_product"]]["id_scenario"] = $position['id_scenario'];
                        $share_product["facing"][$position["id_product"]]["id_position"] = $position['id'];
                        $share_product["facing"][$position["id_product"]]["shelf"] = $position['shelf'];
                        $share_product["facing"][$position["id_product"]]["scenario_name"] = $position['scenario_name'];
                        $share_product["facing"][$position["id_product"]]["qty"] = $position['qty'];
                        $share_product["facing"][$position["id_product"]]["views"] = $position['views'];
                        $share_product["facing"][$position["id_product"]]["alert_count"] = $position['alert_count'];

                        if (!isset($share_product["facing"][$position["id_product"]]["total_frentes"]))
                            $share_product["facing"][$position["id_product"]]["total_frentes"] = 0;
                        $share_product["facing"][$position["id_product"]]["total_frentes"] = $share_product["facing"][$position["id_product"]]["total_frentes"] + $position["views"];
                        $share_product["facing"][$position["id_product"]]["percent"] = ($share_product["facing"][$position["id_product"]]["total_frentes"] * $product["width"] * 100);
                        if (!$product["width"]) $zero++;
                    }
                }
            }
        }
        if (!isset($share_product["total_width"])) $share_product["total_width"] = 0;
        $share_product["total_width"] = $share_product["total_width"] + $share[$i]["width"];
    }
}
// echo json_encode($share_product["facing"]);
?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/share.css') ?>" rel="stylesheet" />
<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="progress-bar1" data-percent="20" data-duration="1000" data-color="#ccc,#E74C3C"></div>
            <div class="card-header" style="background-color: #fc9700;">
                <h3 class="mt-1 text-white">Share de gôndola - <?php echo $planograma["name"] ?></h3>
                <div class="list-group list-group-flush" role="tablist">
<!--                    <a data-bs-toggle="list" role="tab" href="#product"><button class="btn btn-view float-end m-1 " title="Share por produtos" id="produto" style="color:#3e4676"><i class="fas fa-wine-bottle"></i></i> Produtos</button></a>-->
<!--                    <a data-bs-toggle="list" role="tab" href="#gondola"><button class="btn btn-view float-end m-1 active" title="Share por gôndola" id="prateleira"><i class="far fa-eye"></i> Gôndola</button></a>-->
<!--                    <a href="--><?php //echo base_url('/index.php/edit_planogram/' . $_GET["planogram"]) ?><!--"><button class="btn btn-view float-end m-1" style="color:#3e4676"><i class="fas fa-pencil-alt"></i> Editar Planograma</button></a>-->
<!--                    <span class="text-white legenda" style="top: 10px;">Relatório de participação dos produtos em cada gôndola.</span>-->
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show " id="gondola" role="tabpanel">
                    <?php if (isset($share_product["facing"])) { ?>
                        <div class="buttons">
                            <?php if (isset($zero)) { ?>
                                <!-- <button data-toggle="modal" data-target="#confirm_export" class="btn btn-success m-2" ><i class="far fa-file-excel"></i>  Exportar Planilha</button> -->
                            <?php } else { ?>
                                <!-- <button id="excel" onclick="exporta(this)" class="btn btn-success m-2" ><i class="far fa-file-excel"></i>  Exportar Planilha</button> -->
                            <?php } ?>
<!--                            <button id="img" class="btn btn-light m-2"><i class="far fa-file-image"> </i> Exportar Imagem</button>-->
                        </div>
                    <?php } ?>
                    <div id="testecard" class="card-body">
                        <div class="gondola mt-5">
                            <?php for ($c = 1; $c <= $columns_qtd; $c++) {
                                $shelves = isset($columns[$c]) ? $columns[$c] : array(); ?>

                                <div class="column column-<?php echo $c ?>" data-column="<?php echo $c ?>">
                                    <?php for ($i = 1; $i <= $shelves_qtd; $i++) {
                                        $positions = isset($shelves[$i]) ? $shelves[$i] : array();
                                        $y = 1; ?>
                                        <div class="card-group col-12 mt-2">
                                            <h6 class="col-6 col-md-6 col-sm-8 col-lg-8 col-xl-8 card-subtitle text-muted">Prateleira <?php echo $i ?> - Coluna <?php echo $c ?> </h6>
                                            <div class="col-6 col-md-6 col-sm-4 col-lg-4 col-xl-4 card-subtitle text-muted text-end">
                                                <?php
                                                $rest = $share[$i]["width"] % 100000;
                                                $m  = floor($rest / 100);
                                                $cm = $rest % 100;
                                                echo 'Largura total: ' . $m . 'm ' . $cm . 'cm';
                                                ?>
                                            </div>
                                        </div>

                                        <hr class="mt-2">
                                        <div class="products" data-shelf="<?php echo $i ?>">
                                            <ol id="result<?php echo $i ?>" data-shelf="<?php echo $i ?>">
                                                <?php if (is_array($positions)) {
                                                    $data_position = 0;
                                                    foreach ($positions as $position) {
                                                        if (empty($position["id_product"])) continue;

                                                        $position["largura"] = $position["product_width"];
                                                        //largura do produto multiplicada pelo total de frentes
                                                        $product_total = intval($position["product_width"]) * intval($position["views"]);
                                                        //calculando as porcentagens de cada produto sob cada gondola
                                                        if ($share[$i]["width"]) $percent = ($product_total * 100) / $share[$i]["width"];
                                                        else $percent = 0;
                                                        $share[$i]["percents"][$position["id_product"]] = number_format($percent, 2, ",", ".");
                                                        if ($share[$i]["percents"][$position["id_product"]] == '100,00') $share[$i]["percents"][$position["id_product"]] = '100';

                                                        //altura e largura das imagens em suas posições
                                                        if (!$position["width"]) $pos_width = 'auto';
                                                        else $pos_width = $position["width"];
                                                        if (!$position["height"]) $pos_height = 'auto';
                                                        else $pos_height = $position["height"];



                                                        $data_position = $data_position + 1;

                                                        foreach ($share[$i]["percents"] as $key => $value) {
                                                            if ($share_product["facing"][$position["id_product"]]["id"] == $key) {
                                                                $share_product["facing"][$position["id_product"]]["position"][$i]["shelf"] = $position["shelf"];
                                                                $share_product["facing"][$position["id_product"]]["position"][$i]["column"] = $position["column"];
                                                                $share_product["facing"][$position["id_product"]]["position"][$i]["number"] = $data_position;
                                                                $share_product["facing"][$position["id_product"]]["position"][$i]["percent"] = $value;
                                                            }
                                                        }

                                                ?>
                                                        <li class='produto_sortido' data-id='<?php echo  $position["id"]; ?>' data-id_product='<?php echo $position["id_product"] ?>' data-id_scenario='<?php echo $position["id_scenario"]; ?>' data-shelf='<?php echo $position["shelf"] ?>' data-id_position='<?php echo $data_position ?>' data-views='<?php echo $position["views"] ?>'>
                                                            <div class='images' style='overflow-y:hidden;'>
                                                                <?php for ($x = 1; $x <= $position["views"]; $x++) { ?>
                                                                    <img class="img_scenario" width="<?php echo ($position["position_width"]) ? $position["position_width"] : "auto"; ?>" data-largura="<?php echo $position["largura"]; ?>" src='<?php echo base_url($position["product_image"]) ?>' />
                                                                <?php } ?>
                                                                <div class="overlay">
                                                                    <?php if (empty(intval($share[$i]["percents"][$position["id_product"]]))) {  ?>
                                                                        <div class="overlay" style="background: #54514b57;">
                                                                            <div class="percent alterarPorcentagem-<?php echo $position["id_product"]; ?>" style="color: #ff000b; display: flex; flex-direction: column; ">
                                                                                <div data-id="<?php echo $position["id_product"]; ?>" onclick="why(this)" id="why" data-toggle="modal" data-target="#corrigir" title="Clique para corrigir">
                                                                                    <span style="position:relative" title="Clique para corrigir"><i class="fas fa-question-circle"></i></span>
                                                                                </div>
                                                                                <div class="percent-number" style="margin-top:-10px">0%</div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="percent alterarPorcentagem-<?php echo $position["id_product"]; ?>"><?php echo $share[$i]["percents"][$position["id_product"]] . '%'; ?></div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                <?php $y = strval(floatval($position["position"]) + 1);
                                                    }
                                                } ?>
                                            </ol>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active " id="product" role="tabpanel">
                    <?php if (isset($share_product["facing"])) { ?>
                        <div class="buttons">
                            <?php if (isset($zero)) { ?>
                                <!-- <button data-toggle="modal" data-target="#confirm_export" class="btn btn-success m-2" ><i class="far fa-file-excel"></i>  Exportar Planilha</button> -->
                            <?php } else { ?>
                                <!-- <button id="excel" onclick="exporta(this)" class="btn btn-success m-2" ><i class="far fa-file-excel"></i>  Exportar Planilha</button> -->
                            <?php } ?>
<!--                            <button id="img_product" class="btn btn-light m-2"><i class="far fa-file-image"> </i> Exportar Imagem</button>-->
                        </div>
                    <?php } ?>
                    <div class="row mt-5">
                        <?php if (isset($share_product["facing"])) {
                            foreach ($share_product["facing"] as $product) {
                                if ($share_product["total_width"]) $porcentagem = number_format(($product["percent"] / $share_product["total_width"]), 2, ",", ".");
                                else $porcentagem = 0;
                        ?>
                                <div class="row col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 mb-3 p-4" style="border: 1px solid #8686863b; display: inline-flex;">
                                    <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
                                        <h3 class="mt-2" style="color: #fc9700;"><?php echo $product["nome"]; ?>
                                            <?php if (empty(intval($porcentagem))) { ?>
                                                <a data-id="<?php echo $product["id"]; ?>" onclick="why(this)" id="alert" style="cursor: pointer;" data-toggle="modal" data-target="#corrigir" title="Clique para corrigir">
                                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                                </a>
                                            <?php } ?>
                                        </h3>
                                        <div class="row">
                                            <div class="col-12 col-md-3 col-sm-6 col-lg-3 col-xl-3">
                                                <img class="img_scenario" height="150px" style="margin: 0 auto; display: flex;" src='<?php echo $product["img"] ?>' />
                                            </div>
                                            <div class="col-12 col-md-7 col-sm-6 col-lg-9 col-xl-9">
                                                <div style="display: flex">
                                                    <h4 class="card-title"><a>EAN: <?php echo $product['ean']; ?> </a></h4>
                                                    <h4 class="card-title" style="margin-left: 30px;"><a>Dados Atualizados: <?php echo $product['created_at']; ?> </a></h4>
                                                </div>
                                                <h4 class="card-title"><a>Categoris: <?php echo $product['scenario_name']; ?> </a></h4>
                                                <div class="row col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
                                                    <div class="col-12 col-md-3 col-sm-6 col-lg-3 col-xl-3" style="width:250px;white-space:nowrap;border-left: 2px solid #a2a2a291;border-right: 2px solid #a2a2a291;">
                                                        <h5>Toal de Frenties: <?php echo $product["views"]; ?></h5>
                                                        <h5>Toal na Categoria: <?php echo $all_planogram_count; ?></h5>
                                                        <h5>Alerta de Rompitura: <?php echo $product["alert_count"]; ?></h5>
                                                    </div>
                                                    <div class="col-12 col-md-7 col-sm-6 col-lg-9 col-xl-9" style="width:250px;">
                                                        <div style="text-align: center;">
                                                            <h7>QUANTIDADE ATUAL: <?php echo $product['qty']; ?></h7>
                                                            <br>
                                                            <br>
                                                            <?php if($product['qty'] <= $product['alert_count']) {?>
                                                            <button data-bs-toggle="modal" data-bs-target="#edit_product" type="button" class="btn btn-danger" id="btn-success_edit"
                                                                    product_name="<?php echo $product["nome"]; ?>" qty="<?php echo $product["qty"]; ?>" views="<?php echo $product["views"]; ?>"
                                                                    alert_count="<?php echo $product["alert_count"]; ?>" id_product="<?php echo $product["id"]; ?>" onclick="alert_produce_edit(this)"
                                                                    id_scenario="<?php echo $product["id_scenario"]; ?>" id_position="<?php echo $product["id_position"]; ?>" shelf="<?php echo $product["shelf"]; ?>" data-id="">FAZER REPOSITÓRIO</button>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3" style="display: none;">
                                        <svg class="radial-progress" data-percentage="<?php echo $porcentagem; ?>" viewBox="0 0 80 80" onmousemove="showTooltip(evt, 'Participação total');" onmouseout="hideTooltip();">
                                            <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                                            <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 39.58406743523136;"></circle>
                                            <?php if ($porcentagem == '0,00') { ?>
                                                <text class="percentage" style="fill:#f2545b;" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">0% </text>
                                            <?php } else if ($porcentagem == '100,00') { ?>
                                                <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">100%</text>
                                            <?php } else { ?>
                                                <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><?php echo $porcentagem . '%'; ?></text>
                                            <?php } ?>
                                        </svg>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id='edit_product' class='modal fade' role='dialog' tabindex="-1" aria-hidden="true">
        <div class='modal-dialog' style='box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Editar Produto</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class='modal-body'>
                    <div class='row'>
                        <div class='form-group'>
                            <div class='row col-lg-12'>
                                <input type='number' min='1' name='shelf' class='form-control form-control-alternative' placeholder='Quantas frentes serão exibidas?' value='' style="display: none;">
                                <input type='number' min='1' name='id_scenario' class='form-control form-control-alternative' placeholder='Quantas frentes serão exibidas?' value='' style="display: none;">
                                <input type='number' min='1' name='id' class='form-control form-control-alternative' placeholder='Quantas frentes serão exibidas?' value='' style="display: none;">
                                <div class='col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12'>
                                    <label class='form-control-label' for='shelves'>Produto</label>
                                    <select class='form-control form-control-alternative mb-2' id="produto" name='id_product' readonly disabled>
                                        <?php foreach ($share_product["facing"] as $k => $product1) { ?>
                                            <option value='<?php echo $k ?>'><?php echo $product1['nome'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4'>
                                    <label class='form-control-label' for='name' style='white-space:nowrap;'>Frentes exibidas</label>
                                    <input type='number' min='1' name='views' class='form-control form-control-alternative' placeholder='Quantas frentes serão exibidas?' value=''>
                                </div>
                                <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4'>
                                    <label class='form-control-label' for='name' style='white-space:nowrap;'>Total de produtos</label>
                                    <input type='number' min='1' name='qty' class='form-control form-control-alternative' placeholder='Qual o total de produtos?' value=''>
                                </div>
                                <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4'>
                                    <label class='form-control-label' for='name' style='white-space:nowrap;'>Produtos de advertência</label>
                                    <input type='number' min='1' name='alert' class='form-control form-control-alternative' placeholder='Produtos de advertência?' value=''>
                                </div>
                                <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 d-flex align-items-end'>
                                    <button onclick="save_edit()" class='btn btn-outline-warning' style='white-space:nowrap;'>Salvar Frentes</button>
                                </div>
                            </div>
                        </div>
                        <div id="old_product" style="display:none;"></div>
                        <div id="new_product" style="display:none;">
                            <input type="hidden" data-id_product="" data-views="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="tooltip" display="none" style="position: absolute; display: none;"></div>

<!-- modal corrigir dimensoes -->
<div id="corrigir" data-backdrop="static" tabindex="-1" role="dialog" class="modal fade" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Corrigir dimensões</h4>
                <button title="Fechar" id="close" class="btn mb-1 btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body ">
                <div class="text-center">
                    <h4><i class="fas fa-exclamation-triangle text-danger mb-3"></i> Atenção!</h4>
                    <p>As dimensões deste produto ainda não foram definidas, isso prejudica os resultados do share de gôndola. Por favor, corrija as dimensões abaixo:</p>
                    <div class="row">
                        <div class="col-6">
                            <label>Largura</label>
                            <input type="text" name="width" class="form-control form-control-alternative" placeholder="Largura em centimetros do produto" required>
                        </div>
                        <div class="col-6">
                            <label>Altura</label>
                            <input type="text" name="height" class="form-control form-control-alternative" placeholder="Altura em centimetros do produto" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-3 text-end m-1">
                    <button onclick="enviar(this)" id="envia" class="btn bg-orange text-white">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal confirma exportação -->
<div id="confirm_export" tabindex="-1" role="dialog" class="modal fade" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Exportar planilha Excel</h4>
                <button title="Fechar" id="close" class="btn mb-1 btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body ">
                <div class="text-center">
                    <h4><i class="fas fa-exclamation-triangle text-danger mb-3"></i> Atenção!</h4>
                    <?php if (isset($zero)) { ?><p><?php echo $zero; ?> produto(s) deste planograma não tem medidas de largura e altura definidas. Isso pode prejudicar os resultados de share. </p><?php } ?>
                    <h4 id="certeza" class="mb-4"></h4>
                    <button type="button" class="btn btn-success" onclick="exporta(this)" data-id="">Exportar assim mesmo</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Corrigir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Layout para exportar gondula -->
<div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 card shadow" id="print_gondula" style="display:none;z-index: -1000;
">
    <div class="header_data">
        <h4 class="text-center"><?php echo $formatter->format($hoje) . ' | ' . date('G:i'); ?></h4>
    </div>
    <div class="card-group p-5 align-items-center justify-content-between">
        <div class="dkma">
            <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width:180px;height:auto;">
        </div>
        <div class="logo_marca">
            <img class="logo " src="<?php echo base_url($usuario["img_url"]); ?>" alt="logo">
            <h3 class="logo_text">Share de Gôndola</h3>
            <h4 class="logo_text"><?php echo $planograma["name"] ?></h4>
        </div>
    </div>
    <div class="gondola2">
        <?php for ($c = 1; $c <= $columns_qtd; $c++) {
            $shelves = isset($columns[$c]) ? $columns[$c] : array();  ?>
            <div style="padding:0;min-height: 130px;flex: none;" class="card-body">
                <div class="result" style="display: inline-block;white-space: nowrap;border: 6px solid;border-radius: 0;outline: 6px solid red;border-bottom-style: none;" id="column<?php echo $c ?>">
                    <?php for ($i = 1; $i <= $shelves_qtd; $i++) {
                        $positions = isset($shelves[$i]) ? $shelves[$i] : array();
                        $y = 1;  ?>
                        <div class="card-group col-12 mt-2">
                            <h6 class="col-6 col-md-6 col-sm-8 col-lg-8 col-xl-8 card-subtitle text-muted">Prateleira <?php echo $i ?> - Coluna <?php echo $c ?></h6>
                            <div class="col-6 col-md-6 col-sm-4 col-lg-4 col-xl-4 card-subtitle text-muted text-end">
                                <?php
                                $rest = $share[$i]["width"] % 100000;
                                $m  = floor($rest / 100);
                                $cm = $rest % 100;
                                echo 'Largura total: ' . $m . 'm ' . $cm . 'cm';
                                ?>
                            </div>
                        </div>

                        <hr class="mt-2">
                        <div class="products" data-shelf="<?php echo $i ?>">
                            <ol id="result<?php echo $i ?>" data-shelf="<?php echo $i ?>">
                                <?php if (is_array($positions)) {
                                    $data_position = 0;

                                    foreach ($positions as $position) {
                                        if ($position["id_product"] == 0) continue;
                                        foreach ($products as $product) {
                                            if ($position["id_product"] === $product["id"]) {
                                                $position["product_image"] = $product["image"];
                                                $position["product_price"] = $product["price"];
                                                $position["largura"] = $product["width"];
                                                //largura do produto multiplicada pelo total de frentes
                                                $product_total = intval($product["width"]) * intval($position["views"]);
                                            }
                                            //altura e largura das imagens em suas posições
                                            if (!$position["width"]) $pos_width = 'auto';
                                            else $pos_width = $position["width"];
                                            if (!$position["height"]) $pos_height = 'auto';
                                            else $pos_height = $position["height"];
                                        }
                                        $data_position = $data_position + 1;

                                ?>
                                        <li class='produto_sortido' data-id='<?php echo  $position["id"]; ?>' data-id_product='<?php echo $position["id_product"] ?>' data-id_scenario='<?php echo $position["id_scenario"]; ?>' data-shelf='<?php echo $position["shelf"] ?>' data-id_position='<?php echo $data_position ?>' data-views='<?php echo $position["views"] ?>'>
                                            <div class='images' style='overflow-y:hidden;'>
                                                <?php for ($x = 1; $x <= $position["views"]; $x++) { ?>
                                                    <img class="img_scenario" width="<?php echo ($position["position_width"]) ? $position["position_width"] : "auto"; ?>" data-largura="<?php echo $position["largura"]; ?>" src='<?php echo base_url($position["product_image"]) ?>' />
                                                <?php } ?>
                                                <div class="overlay">
                                                    <?php if (empty(intval($share[$i]["percents"][$position["id_product"]]))) {  ?>
                                                        <div class="overlay" style="background: #54514b57;">
                                                            <div class="percent alterarPorcentagem-<?php echo $position["id_product"]; ?>" style="color: #ff000b">0%
                                                                <div data-id="<?php echo $position["id_product"]; ?>" onclick="why(this)" id="why" data-toggle="modal" data-target="#corrigir" title="Clique para corrigir"><i class="fas fa-question-circle"></i></div>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="percent alterarPorcentagem-<?php echo $position["id_product"]; ?>"><?php echo $share[$i]["percents"][$position["id_product"]] . '%'; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                <?php $y = strval(floatval($position["position"]) + 1);
                                    }
                                } ?>
                            </ol>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="footer_export">
        <h5>DKMA MANAGER SHELF LTDA</h5>
        <h5>Av. Nova Cantareira, 2014 - Cj 121/122/123</h5>
        <h5>00 000 000/0001-00</h5>
    </div>
</div>

<!-- Layout para exportar por produtos -->
<div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-8 card shadow " id="print_product" style="margin-top: 0px; display:none; background-color: #FFF;z-index: -1">
    <div class="header_data">
        <h4 class="text-center"><?php echo $formatter->format($hoje) . ' | ' . date('G:i'); ?></h4>
    </div>
    <div class="card-group p-5 align-items-center justify-content-between">
        <div class="dkma">
            <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width:180px;height:auto;">
        </div>
        <div class="logo_marca">
            <img class="logo " src="<?php echo base_url($usuario["img_url"]); ?>" alt="logo">
            <h3 class="logo_text">Share por produtos</h3>
            <h4 class="logo_text"><?php echo $planograma["name"] ?></h4>
        </div>
    </div>
    <div class="row produtos p-2">
        <?php if (isset($share_product["facing"])) foreach ($share_product["facing"] as $product) {
            if ($share_product["total_width"]) $porcentagem = number_format(($product["percent"] / $share_product["total_width"]), 2, ",", ".");
            else $porcentagem = 0;
        ?>
            <div class="row col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 mb-3 p-4" style="background-color: #fff; border: 1px solid #8686863b;">
                <div class="col-8">
                    <h3 class="mt-2" style="color: #fc9700;"><?php echo $product["nome"]; ?>
                        <?php if (empty(intval($porcentagem))) { ?>
                            <a data-id="<?php echo $product["id"]; ?>" onclick="why(this)" id="alert" style="cursor: pointer;" data-toggle="modal" data-target="#corrigir" title="Clique para corrigir">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                            </a>
                        <?php } ?>
                    </h3>
                    <div class="row">
                        <div class="col-12 col-md-3 col-sm-6 col-lg-3 col-xl-4">
                            <img class="img_scenario" height="150px" style="margin: 0 auto; display: flex;" src='<?php echo base_url($product["img"]) ?>' />
                        </div>
                        <div class="col-12 col-md-7 col-sm-6 col-lg-9 col-xl-8">
                            <h4 class="card-title"><a>Marca: <?php echo $product["marca"]; ?> </a></h4>
                            <h4 class="card-title"><a>Total de frentes no planograma: <?php echo $product["total_frentes"]; ?> </a></h4>
                            <div class="row col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
                                <?php if (isset($product["position"])) {
                                    foreach ($product["position"] as $position) { ?>
                                        <div class="col-5 col-sm-4 col-md-4 col-lg-3 col-xl-3 m-2" style="white-space:nowrap;border-left: 2px solid #a2a2a291;">
                                            <?php if ($position["percent"] == '0,00') { ?>
                                                <h3 class="text-danger ">0%<h3>
                                                    <?php } else { ?>
                                                        <h3 class=""><?php echo $position["percent"]; ?>%<h3>
                                                            <?php } ?>

                                                            <h5>Posição <?php echo $position["number"]; ?></h5>
                                                            <h5 style="color:#ff6000">Prateleira <?php echo $position["shelf"]; ?></h5>
                                        </div>
                                <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <svg class="radial-progress" data-percentage="<?php echo $porcentagem; ?>" viewBox="0 0 80 80">
                        <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                        <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 39.58406743523136;"></circle>
                        <?php if ($porcentagem == '0,00') { ?>
                            <text class="percentage" style="fill:#f2545b;" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">0% </text>
                        <?php } else if ($porcentagem == '100,00') { ?>
                            <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">100%</text>
                        <?php } else { ?>
                            <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><?php echo $porcentagem . '%'; ?></text>
                        <?php } ?>
                    </svg>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="footer_export">
        <h5>DKMA MANAGER SHELF LTDA</h5>
        <h5>Av. Nova Cantareira, 2014 - Cj 121/122/123</h5>
        <h5>00 000 000/0001-00</h5>
    </div>
</div>

<script src="https://cdn.rawgit.com/tsayen/dom-to-image/bfc00a6c5bba731027820199acd7b0a6e92149d8/dist/dom-to-image.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="<?php echo base_url('assets/js/progressbar.js') ?>"></script>
<script>
    $(function() {
        let dataString = JSON.parse(`<?php echo json_encode($widths) ?>`);
        let total_width = <?php echo $total_width ?>;
        let max = Object.keys(dataString).length;

        $.each(dataString, function(i, item) {
            let porcentagem = 0.00;
            if (dataString[i]) {
                porcentagem = ((dataString[i] / total_width) * 100).toFixed(2);
            }
            let text_per = porcentagem.toString().replace(".", ",");
            let resto = 100 - porcentagem;
            // $("#percent"+i).text(text_per+'%');
            // $(".alterarPorcentagem-"+i).text(text_per+'%');
            // $(".alterarPorcentagem-"+i).attr("title", text_per+'%');

            if (porcentagem == 0.00) {
                // $("#percent"+i).addClass('zero');
            }
        });
    });

    function alert_produce_edit(scenario) {
        let qty = $(scenario).attr('qty');
        let views = $(scenario).attr('views');
        let alert_count = $(scenario).attr('alert_count');
        let id_product = $(scenario).attr('id_product');
        let id_scenario = $(scenario).attr('id_scenario');
        let id_position = $(scenario).attr('id_position');
        let shelf = $(scenario).attr('shelf');
        console.log(alert_count)
        console.log(alert_count)
        console.log(alert_count)
        $('#edit_product input[name="views"]').val(views);
        $('#edit_product input[name="qty"]').val(qty);
        $('#edit_product input[name="alert"]').val(alert_count);
        $('#edit_product select[name="id_product"]').val(id_product);
        $('#edit_product input[name="id_scenario"]').val(id_scenario);
        $('#edit_product input[name="id"]').val(id_position);
        $('#edit_product input[name="shelf"]').val(shelf);
    }

    function save_edit() {
        let qty = $('#edit_product input[name="views"]').val();
        let views = $('#edit_product input[name="qty"]').val();
        let alert_count = $('#edit_product input[name="alert"]').val();
        let id_product = $('#edit_product select[name="id_product"]').val();
        let id_position = $('#edit_product input[name="id"]').val();
        let id_scenario = $('#edit_product input[name="id_scenario"]').val();
        let shelf = $('#edit_product input[name="shelf"]').val();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/edit_position'); ?>",
            data: {
                id: id_position,
                qty: qty,
                alert_count: alert_count,
                id_product: id_product,
                id_scenario: id_scenario,
                shelf: shelf,
                views: views
            },
            success: function(data) {
                window.location.reload();
            }
        });
    }
</script>

<script>
    //grafico export png
    $(".progress-bar").loading();

    //exportar planilha do share
    function exporta(excel) {
        dataString = `<?php echo base_url('index.php/admin/export_share?data=' . json_encode($share_product)); ?>`;
        window.open(dataString, '_blank');
    }

    //exportar imagem do share de gondola
    $('#img').click(function() {
        $('#print_gondula').css('display', '');
        $('body').toggleClass('disablescroll');
        window.scrollTo(0, 0);
        setTimeout(function() {
            let gondola = document.getElementById("print_gondula");
            domtoimage.toPng(document.getElementById("print_gondula"), {
                    width: 100,
                    height: 100
                })
                .then(function(dataUrl) {
                    var img = new Image();
                    img.src = dataUrl;
                    console.log(img)
                    var link = document.createElement('a');
                    link.download = 'Share_de_gondola';
                    link.href = img.src;
                    link.click();

                })
                .catch(function(error) {
                    console.error("oops, something went wrong!", error);
                });
            $('body').toggleClass('disablescroll');
        }, 500);

    });

    //exportar imagem do share por produtos
    $('#img_product').click(function() {
        $('body').toggleClass('disablescroll');
        $('#print_product').css('display', 'block');
        setTimeout(function() {
            screen()
            $('body').toggleClass('disablescroll');
        }, 100);
    });

    function screen() {
        const render = node =>
            domtoimage.toPng(node, {
                width: 100,
                height: 100
            })
            .then(dataUrl => {
                console.log(performance.now() - pf)
                console.log(dataUrl);
                const img = new Image();
                img.src = dataUrl;
                var link = document.createElement('a');
                link.download = 'Share_de_produtos';
                link.href = dataUrl;
                link.click();

            })
            .catch(error =>
                console.error('oops, something went wrong!', error)
            );

        const foo = document.getElementById('print_product');

        var pf = performance.now();
        render(foo);
    }

    //tooltip do grafico 
    function showTooltip(evt, text) {
        let tooltip = document.getElementById("tooltip");
        tooltip.innerHTML = text;
        tooltip.style.display = "block";
        tooltip.style.left = evt.pageX + 10 + 'px';
        tooltip.style.top = evt.pageY + 10 + 'px';
    }

    function hideTooltip() {
        var tooltip = document.getElementById("tooltip");
        tooltip.style.display = "none";
    }

    //legenda do share
    $('.list-group').click(function() {
        if ($('a[href="#product"]').hasClass('active')) {
            $('#produto').css('color', '#3e4676');
            $('#prateleira').css('color', '#fc9701');
            $('.legenda').text('Relatório de participação de cada produto no planograma inteiro.');
        } else {
            $('#produto').css('color', '#fc9701');
            $('#prateleira').css('color', '#3e4676');
            $('.legenda').text('Relatório de participação dos produtos em cada gôndola.');
        }
    });

    //grafico de produtos
    $('a[href="#product"]').click(function() {
        $('svg.radial-progress').each(function(index, value) {
            $(this).find($('circle.complete')).removeAttr('style');
        });

        $('svg.radial-progress').each(function(index, value) {
            percent = $(value).data('percentage');
            percent = percent.replace(",", ".");
            radius = $(this).find($('circle.complete')).attr('r');
            circumference = 2 * Math.PI * radius;
            strokeDashOffset = circumference - ((percent * circumference) / 100);
            $(this).find($('circle.complete')).animate({
                'stroke-dashoffset': strokeDashOffset
            }, 1250);
        });
    });

    //informações para definir as dimensões
    function why(product) {
        $('input[name="width"]').val('');
        $('input[name="height"]').val('');
        let id = $(product).data('id');
        $('#envia').attr('data-id', id);
    }

    //salvar as dimensões do produto
    function enviar(btn) {
        let id = $(btn).attr('data-id');
        let width = $('input[name="width"]').val();
        let height = $('input[name="height"]').val();

        $.ajax({
            url: '<?php echo base_url('index.php/update_product_dimension'); ?>',
            method: 'POST',
            data: {
                id: id,
                width: width,
                height: height
            },
            success: function(data) {
                window.location.reload();
            }
        });
    }
</script>