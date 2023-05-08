<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = \Config\Services::session();
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
</style>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.4.1.min.js")?>"></script>
    <div class="container-fluid mb-5 mt-5">
      <!-- Table -->
      <div class="row">
        <div class="col-12 pb-5">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Dados</h3>
            </div>
            <div class="row m-0 p-4">
              <div class="col-3">
                  <h2>Nome:</h2>
                  <p><?php echo $session->get('name') ?></p>
              </div>
              <div class="col-3">
                  <h2>Email:</h2>
                  <p><?php echo $session->get('email') ?></p>
              </div>
              <div class="col-3">
                  <h2>Tempo de compra:</h2>
                  <p><?php echo $cart[0]['time'] ?></p>
              </div>
              <div class="col-3">
                  <h2>Total da compra:</h2>
                  <p><?php echo $order[0]['total'] ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 pb-5">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Produtos Comprados</h3>
            </div>
            <div class="table-responsive">
              <table id="cart" class="table align-items-center table-flush table-striped table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(is_array($bought)){ 
                      
                      foreach($bought as $bought){ 
                          $valor_unid = preg_replace('/\D/', '', $bought['price']);
                          if($bought['bought'] != 0){
                          $valor = $valor_unid * $bought['bought'];
                          $total = $total + $valor;
                          
                  ?>
                    <tr>
                        <th scope="row">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <span class="mb-0 text-sm"><?php echo $bought["name"]; ?></span>
                            </div>
                          </div>
                        </th>
                        <td>
                          <?php echo 'R$ '.$bought['price']; ?>
                        </td>
                        <td id="bought-<?php echo $bought['product_ean'] ?>">
                          <?php echo $bought['bought']; ?>
                        </td>
                        <td id="total-<?php echo $bought['product_ean'] ?>">
                          <?php echo "R$ ".substr_replace($valor, ',', -2, 0);  ?>
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
        <div class="col-12 pb-5">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Ordem de Interação</h3>
            </div>
            <div class="table-responsive">
              <table id="cart" class="table align-items-center table-flush table-striped table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Ordem</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(is_array($cart)){ 
                      foreach($cart as $cart){ 
                          $valor_unid = preg_replace('/\D/', '', $cart['price']);
                          $valor = $valor_unid * $cart['bought'];
                          $total = $total + $valor;
                  ?>
                    <tr>
                        <th scope="row">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <span class="mb-0 text-sm"><?php echo $cart["name"] ?></span>
                            </div>
                          </div>
                        </th>
                        <td>
                          <?php echo $cart['sequence'] ?>
                        </td>
                    </tr>
                  <?php }} ?>
                </tbody>
              </table>
            </div>
            
          </div>
        </div>
        <div class="col-12 pb-5">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Visualizados mas não comprados</h3>
            </div>
            <div class="table-responsive">
              <table id="cart" class="table align-items-center table-flush table-striped table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Sequencia</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(is_array($viewed)){ 
                      foreach($viewed as $viewed){ 
                          $valor_unid = preg_replace('/\D/', '', $viewed['price']);
                          $valor = $valor_unid * $viewed['bought'];
                          $total = $total + $valor;
                  ?>
                    <tr>
                        <th scope="row">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <span class="mb-0 text-sm"><?php echo $viewed["name"] ?></span>
                            </div>
                          </div>
                        </th>
                        <td>
                          <?php echo 'R$ '.$viewed['price'] ?>
                        </td>
                        <td>
                          <?php echo $viewed['sequence'] ?>
                        </td>
                    </tr>
                  <?php }} ?>
                </tbody>
              </table>
            </div>
            
          </div>
        </div>
        <div class="col-12 pb-5">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Produtos Removidos no Carrinho</h3>
            </div>
            <div class="table-responsive">
              <table id="cart" class="table align-items-center table-flush table-striped table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Quantidade de remoção</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(is_array($removed_cart)){ 
                      foreach($removed_cart as $removed_cart){ 
                          $valor_unid = preg_replace('/\D/', '', $removed_cart['price']);
                          $valor = $valor_unid * $removed_cart['bought'];
                          $total = $total + $valor;
                  ?>
                    <tr>
                        <th scope="row">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <span class="mb-0 text-sm"><?php echo $removed_cart["name"] ?></span>
                            </div>
                          </div>
                        </th>
                        <td>
                          <?php echo 'R$ '.$removed_cart['price'] ?>
                        </td>
                        <td>
                          <?php echo $removed_cart['removed_cart'] ?>
                        </td>
                    </tr>
                  <?php }} ?>
                </tbody>
              </table>
            </div>
            
          </div>
        </div>
        <div class="col-12 pb-5">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Produtos Removidos no Checkout</h3>
            </div>
            <div class="table-responsive">
              <table id="cart" class="table align-items-center table-flush table-striped table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Quantidade de remoção</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(is_array($removed_checkout)){ 
                      foreach($removed_checkout as $removed_checkout){ 
                          $valor_unid = preg_replace('/\D/', '', $removed_checkout['price']);
                          $valor = $valor_unid * $removed_checkout['bought'];
                          $total = $total + $valor;
                  ?>
                    <tr>
                        <th scope="row">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <span class="mb-0 text-sm"><?php echo $removed_checkout["name"] ?></span>
                            </div>
                          </div>
                        </th>
                        <td>
                          <?php echo 'R$ '.$removed_checkout['price'] ?>
                        </td>
                        <td>
                          <?php echo $removed_checkout['removed_checkout'] ?>
                        </td>
                    </tr>
                  <?php }} ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="text-center" style="margin-top:40px;">
                <a class="btn bg-success text-white" href="<?php echo base_url('index.php/client/logout') ?>">Sair</a>
            </div>
        </div>
      </div>
    </div>
