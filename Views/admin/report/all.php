<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<style>
*{font-family: 'Roboto', sans-serif;} 
.add{text-transform:uppercase; text-decoration: underline; font-size:20pt; color:#1d1d1d;} 
.add:hover{color:#f89e24 } 
.seemore{text-transform:uppercase; color:#656565; font-size:8pt; float:right; margin-right: 10px;margin-bottom: 10px; font-weight: 600;} 
.seemore:hover{text-decoration: none; color:#f89e24 } 
.card-body{text-align:center;}
.new_company:hover{text-decoration:none;}
.card-text{font-size: 15pt; }
.number{color:#f89e24;font-weight:1000;}
.card-body {padding: 0; }
.chart{min-height:0px;}
.bar-chart {position: relative; width: 100%; margin-top: 15px; padding-bottom: 1px; cursor:pointer;}
.bar-chart > .chart {position: relative; width: 100%;}
.bar-chart > .chart > .item {position: relative; width: 100%; height: 40px;color: #232323;}
.bar-chart > .chart > .item > .bar { position: absolute; width: 100%; height: 75%; background-color: #d2d2d2; z-index: 5;}
.bar-chart > .chart > .item > .bar > .progress > .persen {color: #8c8c8c; display: block; position: absolute; top: 0;right: 0;height: 40px;margin-top: -6px; line-height: 40px; padding-right: 12px; z-index: -1;font-size: 10pt;font-weight: 800;}
.bar-chart > .chart > .item > .bar > .progress { top: 0;left: 0; height: 100%;  background-color: #f89e24;z-index: 10;max-width: 100%;}
.bar-chart > .chart > .item > .bar > .progress > .title { display: block; position: absolute; height: 40px;margin-top: 6px; padding-left: 12px; font-size: 10pt;font-weight: 800; z-index: 10; white-space: nowrap;}
</style>
<div class="row"> 
<?php foreach($estudos as $estudo){ ?>
	<div class="col-12 col-md-6 col-sm-6 col-lg-6 col-xl-4 col-xxl-3">
		<div class="card">
			<div class="card-header">
				<h3 class="mb-0 text-center text-uppercase"><?php echo $estudo['name'];?></h3>
			</div>
			<div class="card-body">
				<p class="card-text"><b class="number"><?php echo $estudo["planograms"][0]; ?></b> planogramas</p>
				<p class="card-text"><b class="number"><?php echo $estudo['qtd_pesquisa'];?></b> casos contratados</p>
				<p class="card-text"><b class="number"><?php echo $estudo['qtd_eyetracking'];?></b> casos de Eye Tracking</p>
				<div class="bar-chart">
                    <div class="chart clearfix">
                        <div class="item">
                            <div class="bar">
                                <div class="progress" data-persen="<?php echo $estudo["amostra"][0]; ?>">
                                    <span class="title"> <?php echo $estudo["amostra"][0]; ?>% do estudo concluido</span>
									<span class="persen">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
				<a href="order/<?php echo $estudo["id"]; ?>" class="seemore">Ver estudo completo <i class="fas fa-angle-right"></i></a>
			</div>
		</div>
	</div>
<?php }?>
<div class="col-12 col-md-12 col-lg-12">
	<a href="new_company" class="new_company">
		<div class="card" style="border: 5px solid #f89e24">
			<div class="card-body">
				<b class="add">Contratar mais estudos</b>
				<h4 class="mt-1 text-uppercase">Clique aqui</h4>			
			</div>
		</div>
	</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

$(document).ready(function(){
    barChart();
    $(window).resize(function(){
        barChart();
    });
    function barChart(){
        $('.bar-chart').find('.progress').each(function(){
            var itemProgress = $(this),
            itemProgressWidth = $(this).parent().width() * ($(this).data('persen') / 100);
			itemProgress.css('width', 0); 
			setTimeout(function(){ 
				itemProgress.css('transition', 'width 1s ease-in-out'); 
				itemProgress.css('width', itemProgressWidth); 
            }, 500);
           
        });
    }
});
</script>