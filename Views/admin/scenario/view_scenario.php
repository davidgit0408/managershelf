<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $cenario = $cenario[0];
    $estudo = $company[0];
    $scenario_file = !empty($scenario_file) ? $scenario_file[0]['id'] : null;
?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/view_scenario.css') ?>" rel="stylesheet" />
<style>
  .disablescroll {overflow-y: hidden;}
  .gondola {
        display: flex;
        flex-direction: row;
        border-left: solid 5px;
    }

    .prateleira {
        display: flex;
        flex-direction: row;
        border-bottom: solid 5px;
    }

    .produto {
        display: flex;
        align-items: end;
        position: relative
    }

    .tempo-de-fixacao {
        overflow-x: auto;
    }

    .coluna {
        border: solid;
        border-width: 5px 5px 0 0;
    }
</style>
<div class="row">
    <div class="col-md-12 container mt-3">
    	<div class="card">
    		<div class="card-header" style="background-color: #fc9700;">
    			<h3 class="mt-1 text-white"><?php echo $cenario['name'] ?></h3>
                <button id="export" class="btn btn-github float-end m-1" title="Exportar planograma"><i class="fas fa-download"></i></button>
                <button id="login" class="btn btn-light float-end m-1" title="Logar como cliente" data-bs-toggle="modal" data-bs-target="#loga_client"><i class="fas fa-user"></i></button>
    		</div>
    		<div class="card-body">
                <h6 class="mt-2 card-subtitle text-muted">Versão para campo do planograma: <span style="color: #333;"><?php echo $planograms[0]['name'] ?></span></h6>
                <h6 class="mt-2 card-subtitle text-muted">Vinculado ao estudo: <span style="color: #333;"><?php echo $estudo['name'];?></span></h6>
        		<hr class="mt-2">
                <div class="gondola" style="display:none">
                    <?php for ($c = 1; $c <= $columns_qtd; $c++) {
                        $shelves = isset($columns[$c]) ? $columns[$c] : array(); ?>
                        <div class="column column-<?php echo $c ?>" data-column="<?php echo $c ?>">
                            <?php for ($i = 1; $i <= $shelves_qtd; $i++) { $positions = isset($shelves[$i]) ? $shelves[$i] : array(); $y = 1; ?>
                                <div class="products" data-shelf="<?php echo $i ?>">                                
                                    <ol id="result<?php echo $i ?>" data-shelf="<?php echo $i ?>">
                                        <?php if(is_array($positions)){ 
                                            $data_position = 0;
                                            foreach($positions as $position){
                                                if(empty($position['product_image'])) continue;
                                                if (!$position['width']) $width = 'auto';
                                                else $width = $position['width'];
                                                if (!$position['height']) $height = 'auto';
                                                else $height = $position['height'];
                                                $data_position = $data_position + 1; ?>
                                                    <li class='produto_sortido' data-id='<?php echo $position['id']; ?>'  data-id_product='<?php echo $position['id_product'] ?>'  data-id_scenario='<?php echo $position['id_scenario']; ?>' data-shelf='<?php echo $position['shelf'] ?>' data-id_position='<?php echo $data_position ?>' data-views='<?php echo $position['views']?>'>
                                                        <div class='images' style='overflow-y:hidden;'>
                                                            <?php for($x=1;$x<=$position['views'];$x++){ ?>     
                                                                <img height="<?php echo $height ?>" width="<?php echo $width ?>" src='<?php echo base_url(''). '/' .$position['product_image'] ?>'/>
                                                            <?php } ?>
                                                            <div class="price"><?php echo $position['product_price'] ?></div>
                                                        </div>
                                                    </li>
                                                <?php $y = strval(floatval($position['position']) + 1); 
                                            }
                                        } ?>
                                    </ol>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div id="tempo-de-fixacao"></div>
    		</div>
    	</div>
    </div>
</div>	

<!--Modal Cliente-->
<div class="modal fade" id="loga_client" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Logar como cliente</h5>
                <button type="button" class="btn-close" id="close_modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>                               
            <form role="form" method="post" action="<?php echo base_url('index.php/admin/client_scenario') ?>">
                <div class="modalcontain">       
                    <div class="modal-body m-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">Quantidade Máxima de compras</label>
                                    <input type="text" name="qtd_max" class="mt-1 form-control" placeholder="Insira a quantidade máxima de compras" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">Eye tracking</label><br>
                                    <select name="eye_tracking"  class="mt-1 form-control" required>
                                        <option disabled>Selecione </option>
                                        <option name="eye_tracking" value="true">Sim</option>
                                        <option name="eye_tracking" value="false">Não</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input name="id_cenario" value="<?php echo $cenario["id"]?>" type="hidden"> 
                    <input name="id_company" value="<?php echo $cenario["id_company"]?>" type="hidden"> 
                    
                    <div class="modal-footer">
                        <button type="submit" class="text-white btn bg-orange">Logar</button>
                    </div>
                </div> 
            </form>
        </div>
    </div>
</div>

<!-- Layout para exportar gondula -->
<div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 card shadow" id="print_gondula" style="display:none;"> 
     <div class="header_data">
        <h4 class="text-center"><?php echo $formatter->format($hoje).' | '.date('G:i');?></h4> 
     </div>
     <div class="card-group mt-3"> 
        <div class="dkma"> 
		    <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width:180px;height:auto;">
        </div>  
        <div class="logo_marca"> 
		    <img class="logo" src="" alt="logo">            
            <h3 class="logo_text"><?php echo $planograms[0]['name'] ?></h3>
            <h5 class="logo_text"><?php echo $estudo['name']; ?></h5>
        </div>               
     </div>  
    <div id="tempo-de-fixacao">
    </div>
    <div class="footer_export">
        <h5>DKMA MANAGER SHELF LTDA</h5> 
        <h5>Av. Nova Cantareira, 2014 - Cj 121/122/123</h5>
        <h5>00 000 000/0001-00</h5>
     </div>
</div>  


<script src="<?php echo base_url('assets/js/html2canvas.js') ?>"></script>
<script src="../../assets/js/pages/admin/scenario/gondola.js"></script>

<script>
let positions = <?php echo json_encode($all_positions)  ?>;
let htmlTempoFixacao = new Gondola().montaGondola(positions);
console.log(htmlTempoFixacao);
$("#tempo-de-fixacao").append(htmlTempoFixacao);
$("#print_gondula #tempo-de-fixacao").append(htmlTempoFixacao);
//Função para exportar cenário em png
$('#export').click(function(){
    window.scrollTo(0,0);
    $('body').toggleClass('disablescroll');
      setTimeout(function(){
        $('#print_gondula').css('display','');
        if($('#sidebar').hasClass('toggled')){
            $( ".sidebar-toggle" ).trigger( "click" );
        }       
        let gondola = document.querySelector("#print_gondula");
        html2canvas(gondola).then(canvas => {
            // document.body.appendChild(canvas);
            let cenario = "<?php echo $cenario['name'] ?>";
            cenario = cenario.replace(/[ÀÁÂÃÄÅ]/g,"A");
            cenario = cenario.replace(/[àáâãäå]/g,"a");
            cenario = cenario.replace(/[ÈÉÊË]/g,"E");
            cenario = cenario.replace(/[ç]/gi,'c');
            cenario = cenario.replace(/[^a-z0-9]/gi,'_');
           
            var a = document.createElement('a');
            document.getElementById("wrapper").appendChild(a);
            a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
            a.download = cenario+'.png';
            a.click();         
        });
        $('#print_gondula').css('display','none');
        $('body').toggleClass('disablescroll');
    }, 500);
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('index.php/admin/export_scenario'); ?>",
        success: function(data){}
    });
});
</script>