<?php
error_reporting(E_ALL & ~E_NOTICE);
$scenario = $scenario[0];
$id_scenario = $scenario["id"];
$id_company = $_GET['id_company'];
$status = isset($status) ? $status : null;
$session = session();
$fb_id = $session->get('facebook_id');
$name = $session->get('name');
if (isset($_GET['visual_eye_tracking'])) {
    $gazeEye = 'block';
} else {
    $gazeEye = 'none';
}

?>
<!DOCTYPE html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo base_url('assets/css/eye_tracking/style.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/theme/bootstrap5/docs/css/client_scenario.css') ?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

<script src="<?php echo base_url('/assets/theme/bootstrap5/docs/js/dropzone.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/html2canvas.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/eye_tracking/GazeCloudAPI.js') ?>" base_url="<?php echo base_url('/assets/') ?>"></script>
<script src="<?php echo base_url('/assets/js/pages/client/scenario/index.js') ?>"></script>
<!-- <script src="./js/heatmap-viewer.js"></script> -->
<div>
    <canvas id="canvas" width="320" height="240" hidden></canvas>
    <button id="saveImage" onclick="enviar_imagem();" hidden></button>
</div>

<style>

    [data-brand="BIOCOLOR"] img {
        width: 95px;
    }

    [data-brand="MAXTON"] img {
        width: 75px;
    }

    [data-brand="KOLESTON"] img {
        width: 105px;
    }

    [data-brand="IMEDIA EXCELLENCE CREME"] img{
        width: 105px;
    }

    [data-brand="MAGIC RETOUCH"] img {
        width: 36px;
    }

    [data-brand="SOFT COLOR"] img {
        width: 109px;
    }

    [data-brand="CASTING CREME GLOSS"] img {
        width: 95px;
    }

    [data-brand="NUTRISSE"] img {
        width: 120px;
    }

    [data-brand="COR & TON"] img, [data-brand="COR E TON"] img {
        width: 90px;
    }
    
    div#card {
        zoom: 85%;
    }
</style>

<div style="display: <?php echo $gazeEye; ?>;">
    <div id="visual_eye_tracking" style='position: absolute; width: 100px; height: 100px; border-radius: 50%;border: solid 2px  rgba(255, 255,255, .2); display: none; box-shadow: 0 0 100px 3px rgba(125, 125,125, .5);   pointer-events: none;   z-index: 999999'>
        <div style="padding: 12px;">
            <p id="visual_eye_trackingX">
            <p>
            <p id="visual_eye_trackingY">
            <p>
        </div>
    </div>
</div>
<div id="loading" style="display: block; text-align: center; margin-top: 20%;">
    <img src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/source.gif" style="" />
</div>
<div id="contcont" class="pb-5" style="display: none;">
    <div id="mensagem_Sistema" style="display: flex;flex-direction: row;justify-content: center;align-items: center;padding: 25px 1px 25px 1px;">
        <b></b>
    </div>
    <div id="toast"></div>
    <div id="toast-danger"></div>
    <div id="card" class="card shadow mb-5" style="background-color:#f1f8fa; border: 6px solid;border-radius: 0;outline: 6px solid red; overflow-x: scroll;display: flex;flex-direction: row;">
        <?php for ($c = 1; $c <= count($positions); $c++) {
            $positions[$c] = isset($positions[$c]) ? $positions[$c] : array(); ?>
            <div style="padding:0;min-height: 130px;flex: none;" class="card-body">
                <div class="result" style="display: inline-block;white-space: nowrap;" id="column<?php echo $c ?>">
                    <?php for ($i = 1; $i <= count($positions[$c]); $i++) {
                        $positions[$c][$i] = isset($positions[$c][$i]) ? $positions[$c][$i] : array(); ?>
                        <div style="display: flex;padding:0;min-height: 130px;border-bottom:10px solid #333;align-items: end; height: 230px;" class="card-body">
                            <div class="result" style="display: inline-block;padding-left:3px; white-space: nowrap;" id="result<?php echo $i ?>">
                                <?php if (is_array($positions[$c][$i])) {
                                    foreach ($positions[$c][$i] as $p => $position) {
                                        if (!isset($position['width']) || $position['width'] == 0) $width = 'auto';
                                        else $width = $position['width'];
                                        if (!isset($position['height']) || $position['height'] == 0) $height = 'auto';
                                        else $height = $position['height'];
                                ?>
                                        <div class="scale" data-name="<?php echo $position['product_name'] ?>" data-brand="<?php echo $position['product_brand'] ?>" data-id="<?php echo $position['id'] ?>" style='min-width: 30px;text-align: center;position: relative;display:inline-block;margin: <?php echo $position['margin'] ?>' id='<?php echo $position['shelf'] ?>-<?php echo $p ?>'>
                                            <script>
                                                $(document).ready(function() {
                                                    var qty = 1;
                                                    var product = '<?php echo $position['product_ean'] ?>';
                                                    var sequence = 0;
                                                    $("img[data-bs-toggle='modal']").click(function() {
                                                        sequence++;
                                                    });
                                                    $("img[data-bs-target='#modal-<?php echo $position['shelf'] ?>-<?php echo $p ?>']").click(function() {
                                                        var viewed = $('#pre-cart input[name=viewed]').val();
                                                        var bought = $('#pre-cart input[name=bought]').val();
                                                        if (!viewed.includes(String(product)) && !bought.includes(String(product))) {
                                                            $('#pre-cart input[name=viewed]').val(viewed + ',' + String(product));
                                                            $('#cart form').prepend("<input name='sequence_" + product + "' type='hidden' value='" + sequence + "'>");
                                                        }
                                                    });
                                                    $(document).keypress(function(e) {
                                                        if (e.keyCode === 27) {
                                                            $(".modal").modal('hide');
                                                        }
                                                    });
                                                    $('#modal-<?php echo $position['shelf'] ?>-<?php echo $p ?> button').click(function() {
                                                        console.log("O modal <?php echo $position['shelf'] ?>-<?php echo $p ?> foi clicado")
                                                        var viewed = $('#pre-cart input[name=viewed]').val();
                                                        var bought = $('#pre-cart input[name=bought]').val();
                                                        $('#pre-cart input[name=viewed]').val(viewed.replace(',' + String(product), ''));
                                                        $("form>input[name='sequence_" + product + "'][type='hidden']").remove();
                                                        console.log("O modal <?php echo $position['shelf'] ?>-<?php echo $p ?> foi clicado. O produto é " + product)
                                                        if (bought.includes(String(product))) {
                                                            console.log("O modal <?php echo $position['shelf'] ?>-<?php echo $p ?> foi clicado. O produto é " + product + ". Ele já está no carrinho.")
                                                            qty = $('#cart #' + product + ' input[name=qty_' + product + ']').val();
                                                            qty = parseInt(qty) + 1;
                                                            $('#cart #' + product + ' input[name=qty_' + product + ']').val(qty);
                                                        } else {
                                                            console.log("O modal <?php echo $position['shelf'] ?>-<?php echo $p ?> foi clicado. O produto é " + product + ". Ele ainda não está no carrinho.")
                                                            $('#cart form #btn-fixed').prepend("<div id='" + product + "' class='row m-0'><i id='remove_" + product + "' class='remove fas fa-times'></i><div class='col-3 p-0'><img style='max-height:80px;max-width: 100%;' src='<?php echo base_url($position['product_image']) ?>' /></div><div class='col-9 mt-1'><p><?php echo $position['product_name'] ?></p><p>Preço: R$<?php echo preg_replace("/[\n\r]/", "", $position['product_price']) ?></p><p class='qty'>Quantidade: <input name='qty_" + product + "' readonly type='number' value='" + qty + "'></p></div><input name='removed_" + product + "' type='hidden' value='0'><input name='sequence_" + product + "' type='hidden' value='" + sequence + "'></div>");
                                                            $('#pre-cart input[name=bought]').val(bought + ',' + String(product));
                                                            $("#remove_" + product).click(function() {
                                                                var viewed = $('#pre-cart input[name=viewed]').val();
                                                                var bought = $('#pre-cart input[name=bought]').val();
                                                                qty = $('#cart #' + product + ' input[name=qty_' + product + ']').val();
                                                                var removed = $('#cart #' + product + ' input[name=removed_' + product + ']').val();
                                                                removed = parseInt(removed) + 1;
                                                                $('#cart #' + product + ' input[name=removed_' + product + ']').val(removed);
                                                                if (qty > 1) {
                                                                    qty = parseInt(qty) - 1;
                                                                    $('#cart #' + product + ' input[name=qty_' + product + ']').val(qty);
                                                                } else {
                                                                    qty = parseInt(qty) - 1;
                                                                    $('#cart #' + product + ' input[name=qty_' + product + ']').val(qty);
                                                                    $('#cart #' + product).hide();
                                                                    if (!viewed.includes(String(product))) {
                                                                        $('#pre-cart input[name=viewed]').val(viewed + ',' + String(product));
                                                                    }
                                                                    $('#pre-cart input[name=bought]').val(bought.replace(',' + String(product), ''));
                                                                }
                                                                scenarioClass.showToastDanger(removed + ' produto(s) removido da sacola de compras!');
                                                                qtyTotal--;
                                                                if (qtyTotal > qtd_max) {
                                                                    $('#finalizar').hide();
                                                                    $('#max-atingido').show();
                                                                } else {
                                                                    $('#finalizar').show();
                                                                    $('#max-atingido').hide();
                                                                }
                                                            });
                                                        }
                                                        qtyTotal++;
                                                        if (qtyTotal > qtd_max) {
                                                            $('#finalizar').hide();
                                                            $('#max-atingido').show();
                                                        } else {
                                                            $('#finalizar').show();
                                                            $('#max-atingido').hide();
                                                        }
                                                        scenarioClass.showToast(qty + ' produto(s) adicionado à sacola de compras!');
                                                    });
                                                });
                                            </script>
                                            <div class='images'>
                                                <?php for ($x = 1; $x <= $position['views']; $x++) { ?>
                                                    <img width="<?php echo $width; ?>" height="<?php echo $height; ?>" data-bs-toggle='modal' data-bs-target='#modal-<?php echo $position['shelf'] ?>-<?php echo $p ?>' style='cursor:pointer;' src='<?php echo base_url($position['product_image']) ?>' />
                                                <?php } ?>
                                                <div class="price"><?php echo $position['product_price'] ?></div>
                                                <div id='modal-<?php echo $position['shelf'] ?>-<?php echo $p ?>' class='modal fade' role='dialog'>
                                                    <div class='modal-dialog'>
                                                        <!-- Modal content-->
                                                        <div class='modal-content'>
                                                            <div class='modal-body'>
                                                                <?php if (strpos($position['url'], '.png') !== false || strpos($position['url'], '.jpeg') !== false || strpos($position['url'], '.jpg') !== false) { ?>
                                                                    <div style="padding: 15%;">
                                                                        <img src="<?php echo $position['url'] ?>" style="width: 100%">
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <iframe style="width:100%;min-height:400px;max-height:900px" src="<?php echo $position['url'] ?>"></iframe>
                                                                <?php } ?>
                                                            </div>
                                                            <div class='text-center mt--7'>
                                                                <div class="row m-0 pb-3">
                                                                    <div class="col-lg-12 mb-3">
                                                                        <h1 style="white-space: normal;"><?php echo $position['product_name'] ?> - <span style="font-size: 40px;">R$<?php echo $position['product_price'] ?></span></h1>
                                                                    </div>
                                                                    <div class="col-lg-12 mb-3" id="adicionar_ao_carrinho">
                                                                        <button style="font-size: 20px;width: 100%;" class='btn btn-large bg-success text-white'>Adicionar +1 ao carrinho</button>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <a style="font-size: 20px;width: 100%;" class='btn btn-large bg-danger text-white' data-bs-dismiss='modal'>Fechar</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="bg-indigo" id="side-menu" style="zoom: 150%;">
        <div id="pre-cart" class="p-2 text-white" style="font-size:35px">
            <i class="fas fa-shopping-basket" style="cursor:pointer"></i>
            <div class="p-2" id="cart">
                <form id="formCart" method="post" action="<?php echo base_url('index.php/add_cart') ?>">
                    <div id="btn-fixed">
                        <input type="hidden" value="" name="bought">
                        <input type="hidden" value="" name="viewed">
                        <input type="hidden" value="" name="time">
                        <input type="hidden" value="" name="ip_public" id="ip_public">
                        <input type="hidden" value="" name="ip_private" id="ip_private">
                        <input type="hidden" value="<?php echo $id_scenario ?>" name="id_scenario">
                        <input type="hidden" value="<?php echo $id_company ?>" name="id_company">
                        <hr class="my-2" />
                    </div>
                    <span style="display: none;font-size: 14px;color: red;" id="max-atingido">Você atingiu a quantidade máxima de produtos permitidos. Remova alguns produtos para continuar.</span>
                    <button type="submit" id="finalizar" onclick="scenarioClass.finalizar_compra(event)" style="cursor:pointer;font-size: 20px;width: 100%;" class='btn btn-large bg-orange text-white'>Finalizar Compra</button>
                </form>
            </div>
        </div>
    </div>
</div>
<a id="salvarEyeTracking" target="_blank" href="https://dkmamanagershelf.com/eyetracking/public/viewer/?id=1588501219858" style='display:none'></a>
<div id="myModal" class="modal fade" role="dialog" style="padding: 0!important;">
    <div class="modal-dialog" style="width: 100%;height: 100%;max-width: 98%!important;max-height: 100%;min-height: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ManagerShelf</h4>
            </div>
            <div class="modal-body">
                <div style="position: relative;">
                    <div style="text-align: center;display: block">
                        <h1>Pronto para começar?</h1>
                        <h4>Vamos começar os ajustes.</h4>
                        <br>
                        <a class='btn btn-primary' onclick="$('#videoCalibracao').slideToggle()" >Visualizar tutorial de calibração</a>
                        <button id='startid' class='buttonStartEyeTrack btn btn-success text-black' type="button" onclick="scenarioClass.fullScreen(); GazeCloudAPI.StartEyeTracking();" data-bs-dismiss="modal">Iniciar Calibração</button>
                        <button id='stopid' style='display:none' class='buttonStartEyeTrack' type="button" onclick="scenarioClass.saveEyeData(); GazeCloudAPI.StopEyeTracking();">Parar</button>
                       <div class='data_realtime' style='display:none;background-color: lightblue;'>
                            <p>
                                Real-Time Data:
                            <p id="GazeData"> </p>
                            <p id="HeadPhoseData"> </p>
                            <p id="HeadRotData"> </p>
                            </p>
                        </div>
                        <div id="gaze" style='position: absolute;display:none;width: 100px;height: 100px;border-radius: 50%; pointer-events: none;  z-index: 999999'>
                        </div>
                        <br>
                        <br>
                        <br>
                        <p style="font-size:12px">Você precisa habilitar o acesso a sua câmera.</p>
                        <video id="videoCalibracao" controls style="max-width: 100%; display: none; margin-top: 30px;" src="https://managershelf.com.br/mkt/2022/10/FLUXO-DE-COMPRAS.mp4"></video>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="modalAviso" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width: 100%;height: 100%;max-width: 98%!important;max-height: 100%;min-height: 400px;">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="<?php echo base_url("/assets/uploads/theme/Pop 2.png") ?>" style="max-width: 400px; margin: auto;">
        <h2 class="fs-title">Experiência de compra</h2>
        <p style="font-size: 13px;text-align: left; max-width: 600px; margin: auto;">
            Você será exposto a uma prateleira de produtos para colorir | tingir os cabelos e gostaríamos que você fizesse uma compra, considerando que hoje você está precisando comprar este tipo de produto. Os produtos não serão levados para a casa, mas precisamos que você aja como costuma fazer comprando a quantidade habitual que faz em cada compra.
            Caso você não queira, fique à vontade para não comprar nenhum produto.
            Para visualizar a prateleira por completo recomendamos que utilize a rolagem da página para cima, para baixo, para a esquerda e para a direita.
        </p>
        <button type="button" class="btn btn-success d-block m-auto mt-3" onclick="$('#modalAviso').modal('hide');">Continuar</button>
      </div>
    </div>
  </div>
</div> -->
<!-- EYE TRACKING -->
<script>
    const enviar_imagem = () => {
        send_all_image();
    };
    async function send_all_image() {
        var video = document.querySelector('#showvideoid')
        var linkCanvas = document.querySelector('#link_to_canvas')
        let canvas = document.querySelector("#canvas");
        navigator.mediaDevices.getUserMedia({audio: false, video: {facingMode: 'user'}})
		.then(function(stream) {
			video.srcObject = stream;
		})
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        let image_data_url = canvas.toDataURL('image/jpeg');
        const sendData = () => {
            $.post("<?php echo base_url("index.php/save_user_image"); ?>", {
                media64: image_data_url,
                user: {
                    name: "<?php echo $session->get('name'); ?>",
                    id: "<?php echo $session->get('id'); ?>",
                }
            });
        }
        sendData();
    };
</script>
<script>
    (function(h, o, t, j, a, r) {
        h.hj = h.hj || function() {
            (h.hj.q = h.hj.q || []).push(arguments)
        };
        h._hjSettings = {
            hjid: 1848841,
            hjsv: 6
        };
        a = o.getElementsByTagName('head')[0];
        r = o.createElement('script');
        r.async = 1;
        r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
        a.appendChild(r);
    })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
</script>
<script>
    var scenarioClass = new Scenario({
        logoutUrl: "<?php echo base_url("index.php/User_Authentication/logout") ?>",
        caso_eye_tracking: "<?php echo $eye_tracking ?>",
        save_eye_tracking_url: "<?php echo base_url("index.php/save_eye_tracking") ?>",
        email: "<?php echo $session->get("email") ?>",
        id_scenario: "<?php echo $session->get("id_scenario") ?>"
    });

    $(document).ready(function() {
        scenarioClass.init();

        var s = 1;
        var m = 0;
        var h = 0;
        intervalo = window.setInterval(function() {
            if (s == 60) {
                m++;
                s = 0;
            }
            if (m == 60) {
                h++;
                s = 0;
                m = 0;
            }
            var pad = "00";
            s = "" + s;
            s = pad.substring(0, pad.length - s.length) + s;
            m = "" + m;
            m = pad.substring(0, pad.length - m.length) + m;
            h = "" + h;
            h = pad.substring(0, pad.length - h.length) + h;
            var time = h + ':' + m + ':' + s;
            $('input[name=time]').val(time);
            s++;
        }, 1000);

    });


    $(window).on('load', function() {
        <?php if ($eye_tracking) { ?>
            $('#myModal').modal({backdrop: 'static', keyboard: false});
            $('#myModal').modal('show');
        <?php } ?>
        $('#modalAviso').modal('show');
    });
    var qtyTotal = 0;
    <?php if (array_key_exists('qtd_max', $_GET)) { ?>
        var qtd_max = <?php echo service('request')->getGet('qtd_max') ?>;
    <?php } else { ?>
        var qtd_max = 99999;
    <?php } ?>
</script>
<?php if ($status != 'Em campo') { ?>
    <script>
        document.querySelector("#mensagem_Sistema > b").innerHTML = "Cenário de visualização. Para liberar os outros recursos, libere o cenário para campo."
        document.querySelector("#pre-cart").remove();
    </script>
<?php } ?>
