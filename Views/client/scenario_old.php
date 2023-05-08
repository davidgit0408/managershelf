<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $scenario = $scenario[0];
?>
<!DOCTYPE html>


<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.4.1.min.js")?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js")?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/script.js")?>"></script>




<meta charset="UTF-8">


<?php if($_GET['id_scenario'] == 58 or $_GET['id_scenario'] == 60){ ?>
    <style>
        html {
            background-repeat: no-repeat;
            background-position: top;
            background-size: 1450px;
        }
        .bg-secondary {
            background-color: transparent !important;
        }
        body {
            background-color: transparent !important;
        }
        body {
            zoom: 100% !Important;
            margin-top:430px;
            padding-right: 0 !important;
            margin-right: 135px;
        }
        .shadow.mb-5 {
            box-shadow: 0 0 0 0 rgba(136, 152, 170, 0.15) !important;
        }
        #contcont {
            width: 2200px !important;
            zoom: 50%;
        }
        .card.bg-secondary.shadow.mb-5>div {
            margin-top: 0 !Important;
        }
        .card {
            border: 0 !Important;
            text-align: center;
            border-radius: 0;
            outline: 0 !Important;
        }
        .modal-dialog {
            zoom: 215% !important;
        }
        .bg-indigo {
            zoom: 150% !important;
    }
    </style>
<?php } ?>
<style>

body {
    zoom: 80%;
}
.modal-dialog {
    zoom: 120%;
}
.bg-indigo {
            zoom: 150%;
    }
iframe body {
    text-align: center !Important;
}
.modal-content .modal-body iframe {
    padding-top: 10%;
}
@media (min-width: 576px){
.modal-dialog {
    max-width: 465px !important;
}
}
.card.bg-secondary.shadow.mb-5>div {
    margin-top: 25px;
}
.fas.edit{
    position: absolute;
    left: 10px;
    background: #333;
    padding: 7px;
    border-radius: 30px;
    bottom: 2px;
    cursor: pointer;
    font-size: 10px;
}
.fas.close{
    position: absolute;
    left: 10px;
    background: red;
    padding: 5px 7px;
    border-radius: 30px;
    top: 2px;
    cursor: pointer;
    font-size: 10px;
}
#side-menu{
    position: fixed;
    top: 10%;
    right: 0;
    background: #33333380!important;
}
#btn-fixed{
    max-height: 300px;
    overflow: scroll !important;
    overflow-x: hidden !important;
}
#cart{
    position: absolute;
    background: #fff;
    right: 0;
    display:none;
    width: 300px;
}
#cart *{
    font-size:16px;
    vertical-align:top;
    color:#333;
    margin:0;
}
input[type=number]{
    max-width: 3em;
    line-height: 1;
}
#cart .remove{
    position: absolute;
    right: 15px;
    color: #fff;
    background: red;
    padding: 2px 5px;
    border-radius: 10px;
    cursor: pointer;
    z-index: 99999;
}
.price{
    text-align: center;
    position: absolute;
    bottom: 0;
    width: 100%;
    background: #33333380;
    color: #fff;
}
#toast{
    position:fixed;
    top:0;
    left:50%;
    transform:translate(-50%);
    background-color:#38C000;
    color:#fff;
    padding:16px;
    border-radius:4px;
    text-align:center;
    z-index:1800;
    box-shadow:0 0 20px rgba(0,0,0,0.3);
    visibility:hidden;
    opacity:0;
    font-family: Montserrat-Regular;
    text-transform: uppercase;
}

#toast.show{
  visibility:visible;
  animation:fadeInOut 3s;
  opacity:1;
}
#toast-danger{
    position:fixed;
    top:0;
    left:50%;
    transform:translate(-50%);
    background-color:red;
    color:#fff;
    padding:16px;
    border-radius:4px;
    text-align:center;
    z-index:1800;
    box-shadow:0 0 20px rgba(0,0,0,0.3);
    visibility:hidden;
    opacity:0;
    font-family: Montserrat-Regular;
    text-transform: uppercase;
}


#toast-danger.show{
  visibility:visible;
  animation:fadeInOut 3s;
  opacity:1;
}

@keyframes fadeInOut{
  5%,95%{opacity:1;top:50px}
  15%,85%{opacity:1;top:30px}
}
.row{position:relative;}

</style>
<?php if($_GET['id_scenario'] == 58 or $_GET['id_scenario'] == 60){ ?>
<script>
                $(document).ready(function(){
                    $('html').css(
                        'background-image', 'url(http://dkmamanagershelf.com/remake/assets/uploads/produtos/destaque/balcao2.png)'
                    );
                });
</script>
<?php } ?>
<script>
                $(document).ready(function(){
                    document.getElementById("loading").style.display = "none";
                    document.getElementById("contcont").style.display = "block";
                    
                    $('#pre-cart i').click(function(){
                        $('#cart').toggle('slide');
                    });
                    $('button.bg-success').click(function(){
                        $('#cart').show('slide');
                    });
                    $('a.bg-danger').click(function(){
                        $('#cart').hide('slide');
                    });
                    var s = 1;
                    var m = 0;
                    var h = 0;
                    intervalo = window.setInterval(function() {
                        if (s == 60) { m++; s = 0; }
                        if (m == 60) { h++; s = 0; m = 0; }
                        var pad = "00";
                        s = ""+s;
                        s = pad.substring(0, pad.length - s.length) + s;
                        m = ""+m;
                        m = pad.substring(0, pad.length - m.length) + m;
                        h = ""+h;
                        h = pad.substring(0, pad.length - h.length) + h;
                        var time = h +':'+ m +':'+ s;
                        $('input[name=time]').val(time);
                        s++;
                    },1000);
                });
</script>
        
        <div id="loading" style="display: block; text-align: center; margin-top: 20%;">
            <img src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/source.gif" style="" />
        </div>
        
      <div id="contcont" class="container-fluid mt-5 pb-5" style="width: 3100px; display: none;">
        <div id="toast"></div>
        <div id="toast-danger"></div>
        <div class="card bg-secondary shadow mb-5" style="border: 6px solid;border-radius: 0;outline: 6px solid red;">
          <?php 
                
                for($i = 1; $i<=$scenario['shelves']; $i++){
                    $y=0;
            ?>
                    <div style="padding:0;min-height: 100px;" class="card-body">
                        <div style="border-bottom:2px solid #ccc;display: inline-block;padding-left:3px;" id="result<?php echo $i ?>">
                            <?php if(is_array($positions[$i])){ 
                                    foreach($positions[$i] as $position){ 
                                        $y++;
                                        foreach($products as $product){
                                            if($position['id_product'] === $product['id']){
                                                $position['product_image'] = $product['image'];
                                                $position['url'] = $product['url'];
                                                $position['product_name'] = $product['name'];
                                                $position['product_price'] = $product['price'];
                                                $position['product_ean'] = $product['ean'];
                                            }
                                        }
                            ?>
                                <div style='position: relative;display:inline-block;' id='<?php echo $position['shelf'] ?>-<?php echo $position['position'] ?>'>
                                    <script>
                                    $(document).ready(function(){
                                        var qty = 1;
                                        var product = '<?php echo $position['product_ean'] ?>';
                                        var sequence = 0;
                                        $("img[data-toggle='modal']").click(function(){
                                            sequence++;
                                        });
                                        $("img[data-target='#modal-<?php echo $position['shelf'] ?>-<?php echo $position['position'] ?>']").click(function(){
                                            var viewed = $('#pre-cart input[name=viewed]').val();
                                            var bought = $('#pre-cart input[name=bought]').val();
                                            if(!viewed.includes(String(product)) && !bought.includes(String(product))){
                                                $('#pre-cart input[name=viewed]').val(viewed + ','+String(product));
                                                $('#cart form').prepend("<input name='sequence_"+product+"' type='hidden' value='"+sequence+"'>");
                                            }
                                        });
                                        $('#modal-<?php echo $position['shelf'] ?>-<?php echo $position['position'] ?> button').click(function(){
                                            var viewed = $('#pre-cart input[name=viewed]').val();
                                            var bought = $('#pre-cart input[name=bought]').val();
                                            $('#pre-cart input[name=viewed]').val(viewed.replace(','+String(product), ''));
                                            $("form>input[name='sequence_"+product+"'][type='hidden']").remove();
                                            if(bought.includes(String(product))){
                                                qty = $('#cart #'+product+' input[name=qty_'+product+']').val();
                                                qty = parseInt(qty) + 1;
                                                $('#cart #'+product+' input[name=qty_'+product+']').val(qty);
                                            }else{
                                                $('#cart form #btn-fixed').prepend("<div id='"+product+"' class='row m-0'><i id='remove_"+product+"' class='remove fa fa-times'></i><div class='col-3 p-0'><img style='max-height:80px;max-width: 100%;' src='<?php echo base_url($position['product_image']) ?>' /></div><div class='col-9'><p><?php echo $position['product_name'] ?></p><p>Preço: R$<?php echo $position['product_price'] ?></p><p class='qty'>Quantidade: <input name='qty_"+product+"' type='number' value='"+qty+"'></p></div><input name='removed_"+product+"' type='hidden' value='0'><input name='sequence_"+product+"' type='hidden' value='"+sequence+"'></div>");
                                                $('#pre-cart input[name=bought]').val(bought + ','+String(product));
                                                $("#remove_"+product).click(function(){
                                                    var viewed = $('#pre-cart input[name=viewed]').val();
                                                    var bought = $('#pre-cart input[name=bought]').val();
                                                    qty = $('#cart #'+product+' input[name=qty_'+product+']').val();
                                                    var removed = $('#cart #'+product+' input[name=removed_'+product+']').val();
                                                    removed = parseInt(removed) + 1;
                                                    $('#cart #'+product+' input[name=removed_'+product+']').val(removed);
                                                    if(qty > 1){
                                                        qty = parseInt(qty) - 1;
                                                        $('#cart #'+product+' input[name=qty_'+product+']').val(qty);
                                                    }else{
                                                        qty = parseInt(qty) - 1;
                                                        $('#cart #'+product+' input[name=qty_'+product+']').val(qty);
                                                        $('#cart #'+product).hide();
                                                        if(!viewed.includes(String(product))){
                                                            $('#pre-cart input[name=viewed]').val(viewed + ','+String(product));
                                                        }
                                                        $('#pre-cart input[name=bought]').val(bought.replace(','+String(product), ''));
                                                    }
                                                    showToastDanger(removed+' produto(s) removido da sacola de compras!');
                                                });
                                            }
                                            showToast(qty+' produto(s) adicionado à sacola de compras!');
                                        });
                                    });
                                    </script>
                                    <div class='images'>
                                        <?php for($x=1;$x<=$position['views'];$x++){ ?>     
                                        <img data-toggle='modal' data-target='#modal-<?php echo $position['shelf'] ?>-<?php echo $position['position'] ?>' style='cursor:pointer;' src='<?php echo base_url($position['product_image']) ?>' />
                                        <?php } ?>
                                        <div class="price"><?php echo $position['product_price'] ?></div>
                                        <div id='modal-<?php echo $position['shelf'] ?>-<?php echo $position['position'] ?>' class='modal fade' role='dialog'>
                                            <div class='modal-dialog'>
                                                <!-- Modal content-->
                                                <div class='modal-content'>
                                                    <div class='modal-body'>    
                                                        <iframe style="width:100%;min-height:600px;max-height:900px" src="<?php echo $position['url'] ?>"></iframe>
                                                    </div>                             
                                                    <div class='text-center mt--7'>     
                                                        <div class="row m-0 pb-3">
                                                            <div class="col-lg-12">
                                                                <h1><?php echo $position['product_name'] ?> - <span style="font-size: 40px;">R$<?php echo $position['product_price'] ?></span></h1>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <a style="font-size: 20px;width: 100%;" class='btn btn-large bg-danger text-white' data-dismiss='modal'>Fechar</a> 
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <button style="font-size: 20px;width: 100%;" class='btn btn-large bg-success text-white'>Comprar</button> 
                                                            </div>
                                                        </div> 
                                                    </div>                 
                                                </div>        
                                            </div>              
                                        </div>
                                    </div>
                                </div>
                            <?php }} ?>
                        </div>
                    </div>
          <?php
                }
          ?>
        </div>
        <div class="bg-indigo" id="side-menu">
            <div id="pre-cart" class="p-4 text-white" style="font-size:40px">
                <i class="fas fa-shopping-basket" style="cursor:pointer"></i>
                <div class="p-3" id="cart">
                    <form method="post" action="<?php echo base_url('index.php/client/add_cart') ?>">
                        <div id="btn-fixed">
                        <input type="hidden" value="" name="bought">
                        <input type="hidden" value="" name="viewed">
                        <input type="hidden" value="" name="time">
                        <hr class="my-2" />
                        </div>
                        <button style="font-size: 20px;width: 100%;" class='btn btn-large bg-gray text-white'>Finalizar Compra</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
     


</body>


</html>


     
      <script type="text/javascript">
        function showToast(text){
            var x=document.getElementById("toast");
            x.classList.add("show");
            x.innerHTML=text;
                setTimeout(function(){
                    x.classList.remove("show");
                },3000);
        }
        function showToastDanger(text){
            var x=document.getElementById("toast-danger");
            x.classList.add("show");
            x.innerHTML=text;
                setTimeout(function(){
                    x.classList.remove("show");
                },3000);
        }
      </script>
