<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $total = 0;
    
    foreach($cart as $cart1){ 
      $valor_unid = preg_replace('/\D/', '', $cart1['price']);
      $valor = $valor_unid * $cart1['bought'];
      $total = $total + $valor;
    }
?>
<!DOCTYPE html>
<style>
#cart .remove{
    position: relative;
    right: 15px;
    color: #fff;
    background: red;
    padding: 3px 5px;
    border-radius: 10px;
    cursor: pointer;
    z-index: 99999;
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
</style>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.4.1.min.js")?>"></script>
<div class="container-fluid mb-5 mt-5">
<div id="toast-danger"></div>
<div class="row">
    <div class="card col-9">
      <div class="row flex-fill p-3">
        <div class="card-header border-0">
          <h3 class="mb-0">Carrinho</h3>
        </div>
        <div class="table-ressponsive">
          <table id="cart" class="table table-striped my-0 ">
            <thead class="thead-light">
              <tr>
                <th scope="col">Produto</th>
                <th scope="col">Preço</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Total</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($cart)){ 
                  foreach($cart as $cart){ 
                      if($cart['bought'] > 0){
                          $valor_unid = preg_replace('/\D/', '', $cart['price']);
                          $valor = $valor_unid * $cart['bought'];
                  ?>
                    <script>
                    $(document).ready(function(){
                        function mascaraValor(valor) {
                            valor = valor.toString().replace(/\D/g,"");
                            valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
                            valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
                            valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
                            return valor                    
                        }
                        var total = <?php echo $total ?>;
                        function remove_checkout_<?php echo $cart['product_ean'] ?>(id_cart,product_ean,bought,removed_checkout){
                            $.ajax({
                                url:'<?php echo base_url() ?>index.php/client/remove_checkout',
                                method:'POST',
                                data:{id_cart:id_cart,product_ean:product_ean,bought:bought,removed_checkout:removed_checkout},
                                success:function(data){
                                    $('#bought-<?php echo $cart['product_ean'] ?>').text(bought);
                                    var total_<?php echo $cart['product_ean'] ?> = <?php echo $valor_unid ?> * bought;
                                    $('#total-<?php echo $cart['product_ean'] ?>').text('R$ '+ mascaraValor(total_<?php echo $cart['product_ean'] ?>));
                                    total = total - <?php echo $valor_unid ?>;
                                    $('#total').text('Total: R$ '+mascaraValor(total));
                                    $('input[name=total]').val('R$ '+mascaraValor(total));
                                    showToastDanger(removed_checkout+' produto(s) removido da sacola de compras!');
                                },
                                error: function(req, err){ console.log('my message' + err); }
                            })
                        }
                        bought<?php echo $cart['product_ean'] ?> = <?php echo $cart['bought'] ?>;
                        removed_checkout<?php echo $cart['product_ean'] ?> = <?php echo $cart['removed_checkout'] ?>;
                        id_cart = '<?php echo $_GET['id_cart'] ?>';
                        product_ean<?php echo $cart['product_ean'] ?> = '<?php echo $cart['product_ean'] ?>';
                        $('i#remove-<?php echo $cart['product_ean'] ?>').click(function(){
                            if(bought<?php echo $cart['product_ean'] ?> > 1){
                                bought<?php echo $cart['product_ean'] ?> = bought<?php echo $cart['product_ean'] ?> - 1;
                                removed_checkout<?php echo $cart['product_ean'] ?>++;
                                remove_checkout_<?php echo $cart['product_ean'] ?>(id_cart,product_ean<?php echo $cart['product_ean'] ?>,bought<?php echo $cart['product_ean'] ?>,removed_checkout<?php echo $cart['product_ean'] ?>);
                            }else{
                                bought<?php echo $cart['product_ean'] ?> = bought<?php echo $cart['product_ean'] ?> - 1;
                                removed_checkout<?php echo $cart['product_ean'] ?>++;
                                remove_checkout_<?php echo $cart['product_ean'] ?>(id_cart,product_ean<?php echo $cart['product_ean'] ?>,bought<?php echo $cart['product_ean'] ?>,removed_checkout<?php echo $cart['product_ean'] ?>);
                                document.getElementById('<?php echo $cart['product_ean'] ?>').remove()
                            }
                        });
                    });
                    </script>
                    <tr id="<?php echo $cart['product_ean'] ?>">
                        <th scope="row">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <span class="mb-0 text-sm"><?php echo $cart["name"] ?></span>
                            </div>
                          </div>
                        </th>
                        <td>
                          <?php echo 'R$ '.$cart['price'] ?>
                        </td>
                        <td id="bought-<?php echo $cart['product_ean'] ?>">
                          <?php echo $cart['bought'] ?>
                        </td>
                        <td id="total-<?php echo $cart['product_ean'] ?>">
                          <?php echo "R$ ".substr_replace($valor, ',', -2, 0);  ?>
                        </td>
                        <td class="text-right">
                          <i id="remove-<?php echo $cart['product_ean'] ?>" class="fas fa-times remove"></i>
                        </td>
                    </tr>
                      <?php }}} ?>
            </tbody>
            <tfoot class="tfoot-light">
              <tr>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th id="total" scope="col">Total: <?php echo "R$ ".substr_replace($total, ',', -2, 0); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
        
      </div>
    </div>
    <div id="pagament_method" class="col-3">
      <form method="post" action="<?php echo base_url('index.php/add_order') ?>">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Forma de Pagamento</h3>
            </div>
            <div class="row m-0">
                <div class="col-4" style=" display: flex;align-items: center;">
                    <p class="m-auto text-center">Dinheiro</p>
                </div>
                <div class="col-4" style=" display: flex;align-items: center;">
                    <p class="m-auto text-center">Cartão de Crédito</p>
                </div>
                <div class="col-4" style=" display: flex;align-items: center;">
                    <p class="m-auto text-center">Cartão de Débito</p>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-4" style="display: flex;align-items: center;justify-content: center;margin: 0;">
                    <input style="display: none;" value="dinheiro" type="radio" name="payment_method"><img onclick="$('input[value=dinheiro]').click();$('#pagament_method img').css('opacity', '0.6');$(this).css('opacity', '1');$('.bg-danger').hide();$('#button').show();" style="max-width: 100px;opacity:0.6;cursor:pointer;max-height: 70px;" src="<?php echo base_url("assets/img/theme/dollar.png") ?>">
                </div>
                <div class="col-4" style="display: flex;align-items: center;justify-content: center;margin: 0;">
                    <input style="display: none;" value="credito" type="radio" name="payment_method"><img onclick="$('input[value=credito]').click();$('#pagament_method img').css('opacity', '0.6');$(this).css('opacity', '1');$('.bg-danger').hide();$('#button').show();" style="max-width: 100px;opacity:0.6;cursor:pointer;max-height: 70px;" src="<?php echo base_url("assets/img/theme/credit-card.png") ?>">
                </div>
                <div class="col-4" style="display: flex;align-items: center;justify-content: center;margin: 0;">
                    <input style="display: none;" value="debito" type="radio" name="payment_method"><img onclick="$('input[value=debito]').click();$('#pagament_method img').css('opacity', '0.6');$(this).css('opacity', '1');$('.bg-danger').hide();$('#button').show();" style="max-width: 100px;opacity:0.6;cursor:pointer;max-height: 70px;" src="<?php echo base_url("assets/img/theme/credit.png") ?>">
                </div>
            </div>
            <input type="hidden" name="id_cart" value="<?php echo $cart['id_cart'] ?>">
            <input type="hidden" name="id_scenario" value="<?php echo $cart['id_scenario'] ?>">
            <input type="hidden" name="id_company" value="<?php echo $cart['id_company'] ?>">
            <input type="hidden" name="total" value="<?php echo "R$ ".substr_replace($total, ',', -2, 0); ?>">
            <div class="row m-0 pb-3">
                <div class="col-12">
                    <div class="text-center"><a class="btn bg-danger text-white">Selecione um método de pagamento</a></div>
                    <div style="display:none" id="button" class="text-center"><button type="submit" class="btn bg-success text-white">Finalizar Compra</button></div>
                </div>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
      
<script type="text/javascript">
function showToastDanger(text){
  var x=document.getElementById("toast-danger");
  x.classList.add("show");
  x.innerHTML=text;
      setTimeout(function(){
          x.classList.remove("show");
      },3000);
}
</script>
