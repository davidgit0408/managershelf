<?php
error_reporting(E_ALL & ~E_NOTICE);
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : null;
if (!$pagina) $pc = 1;
else $pc = $pagina;

$anterior = $pc - 1;
$proximo = $pc + 1;
$proximo_1 = $pc + 2;

$anteriorString = $anterior;
$proximoString = $proximo;
$proximoString_1 = $proximo_1;

$max_pages = ceil($total / $limit);

?>


<!DOCTYPE html>

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex">
		<div class="card flex-fill p-3">


			<div class="row align-items-center col-12 mb-3">
				<div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
					<h3 class="mb-0">Compras - <?php echo $name_company ?></h3>
				</div>
				<div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
					<button id="heatmap" class="btn btn-light float-end ms-1" title="Gerar Heatmap"><i class="fas fa-fire"></i></button>
					<a href="../export_carts?id_company=<?php echo $id_company; ?>" class="btn btn-github text-white float-end" style="white-space:nowrap;">Exportar</a>
				</div>
			</div>
			<!-- MODAL NOVO -->
			<button type="button" style="display: none;" id="open_modal_purchase" class="btn btn-primary" data-toggle="modal" data-target=".modal_relatory_user"></button>
			<div class="modal fade modal_relatory_user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header" style="background-color: #f89e24;">
							<h4 class="modal-title" style="color: white;">Detalhes da compra</h4>
							<a type="button" class="close" data-dismiss="modal" aria-label="Close">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
									<path style="color: #ff0000;" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
								</svg>
							</a>
						</div>
						<div class="modal-body">
							<div class="table-responsive-lg table-bordered">
								<table class="table table-lg">
									<thead>
									</thead>
									<tbody>
										<tr>
											<th scope="row">Cliente</th>
											<td id="client"></td>
										</tr>
										<tr>
											<th scope="row">Data</th>
											<td id="date"></td>
										</tr>
										<tr>
											<th scope="row">Total</th>
											<td id="total"></td>
										</tr>
										<tr>
											<th scope="row">Produtos comprados</th>
											<td id="purchased_products"></td>
										</tr>
										<tr>
											<th scope="row">Produtos interagidos</th>
											<td id="interacted_products"></td>
										</tr>
										<tr>
											<th scope="row">Removidos do carrinho</th>
											<td id="removed_in_cart"></td>
										</tr>
										<tr>
											<th scope="row">Removidos do checkout</th>
											<td id="removed_in_checkout"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- MODAL NOVO -->

			<table id="datatables-dashboard-projects2" class="table table-striped my-0">
				<thead>
					<tr>
						<th scope="col">Comprador</th>
						<th class="d-none d-md-table-cell">Planograma</th>
						<th class="d-none d-md-table-cell">Comprados</th>
						<th class="d-none d-md-table-cell">Tempo de compra</th>
						<th scope="col">Preço total</th>
						<th class="d-none d-md-table-cell">Data</th>
						<th class="d-none d-md-table-cell">Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php if (is_array($order_data)) {
						foreach ($order_data as $order_data) { ?>
							<tr>
								<td><?php echo $orders_list['client'][$order_data['id']] ?></td>
								<td class="d-none d-md-table-cell"><?php echo $orders_list['scenario'][$order_data['id']] ?></td>
								<td class="d-none d-md-table-cell"><?php echo $orders_list['order_detail'][$order_data['id']] ?></td>
								<td class="d-none d-md-table-cell"><?php echo $orders_list['time'][$order_data['id']] ?></td>
								<td><?php echo $orders_list['total'][$order_data['id']] ?></td>
								<td class="d-none d-md-table-cell"><?php echo date('d/m/Y', strtotime($order_data['data'])); ?></td>
								<td><button style="background-color: #f89e24;border-radius: 5px;"type="button" class="btn btn-light" onclick="get_relatory('<?php echo $order_data['id'] ?>')">detalhes</button></td>
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
<!--Modal Heatmap-->
<div class="modal fade" style="display:none" id="modal_heatmap" style="display:none;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Gerar Heatmap</h5>
				<button type="button" class="btn-close" id="close_modal" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modalcontain">
				<form role="form" method="post" id="myForm" target="_blank" action="">
					<div class="modal-body m-3">
						<div class="row">
							<div class="alert alert-danger alert-dismissible" style="display:none" role="alert">
								<div class="alert-message">Você precisa selecionar ao menos um usuário para gerar o heatmap.</div>
								<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<div class="checks row" style="max-height: 140px; overflow-y: auto;">
								<label class="form-control-label mb-2 p-0" for="name">Selecione um cenario para gerar o heatmap.</label><br>
								<?php
								foreach ($scenarios as $key => $scenario) {
								?>
									<div class="col-4 form-check form-switch">
										<input type="radio" name="scenario[]" value="<?php echo $scenario['id']; ?>" class="form-check-input">
										<span class="mt-4 form-check-label"><?php echo $scenario['name']; ?></span><br>
									</div>
								<?php
								}
								?>
							</div>
						</div>
					</div>
					<input type="hidden" name="img_val" id="img_val" value="" />
					<div class="modal-footer">
						<a id="submit" class="text-white btn bg-orange">Gerar</a>
						<button type="submit" id="submitButton" style="display:none">Gerar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script src="<?php echo base_url('assets/js/html2canvas.js') ?>"></script>
<?php if (isset($positions)) { ?>
	<?php foreach ($positions as $scenario_id => $positions) { ?>
		<div id="print_gondula" style="display:none" class="gondola gondola<?php echo $scenario_id; ?>">
			<?php for ($c = 1; $c <= count($positions); $c++) {
				$positions[$c] = isset($positions[$c]) ? $positions[$c] : array(); ?>
				<div style="display: inline-block;padding:0;min-height: 130px;flex: none;" class="card-body">
					<div class="result" style="display: inline-block;white-space: nowrap;border: 6px solid;border-radius: 0;outline: 6px solid red;border-bottom-style: none;" id="column<?php echo $c ?>">
						<?php for ($i = 1; $i <= count($positions[$c]); $i++) {
							$positions[$c][$i] = isset($positions[$c][$i]) ? $positions[$c][$i] : array(); ?>
							<div style="padding:0;min-height: 115px;border-bottom:10px solid #333;" class="card-body">
								<div class="result" style="display: inline-block;padding-left:3px; white-space: nowrap;" id="result<?php echo $i ?>">
									<?php if (is_array($positions[$c][$i])) {
										foreach ($positions[$c][$i] as $p => $position) {
											if (empty($position['id_product'])) continue;
											if (!isset($position['width']) || $position['width'] == 0) $width = 'auto';
											else $width = $position['width'];
											if (!isset($position['height']) || $position['height'] == 0) $height = 'auto';
											else $height = $position['height'];
									?>
											<div class="scale" data-name="<?php echo $position['product_name'] ?>" data-id="<?php echo $position['id_product'] ?>" style='vertical-align: bottom;min-width: 50px;text-align: center;position: relative;display:inline-block;' id='<?php echo $position['shelf'] ?>-<?php echo $p ?>'>
												<div class='images'>
													<?php for ($x = 1; $x <= $position['views']; $x++) { ?>
														<img width="<?php echo $width; ?>" height="<?php echo $height; ?>" data-bs-toggle='modal' data-bs-target='#modal-<?php echo $position['shelf'] ?>-<?php echo $p ?>' style='cursor:pointer;' src='<?php echo $position['product_image'] ?>' />
													<?php } ?>
													<div class="price" style="position: absolute;bottom: 0;width: 100%;background: #ccccccc2;"><?php echo $position['product_price'] ?></div>
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
		<input type="hidden" id="urlPrint<?php echo $scenario["id"] ?>">
<?php }
} ?>


<script>
	function tirarPrint(id_scenario) {
		window.scrollTo(0, 0);
		$('body').toggleClass('disablescroll');
		setTimeout(async function() {
			if ($('#sidebar').hasClass('toggled')) {
				$(".sidebar-toggle").trigger("click");
			}
			$('.gondola' + id_scenario).css('display', 'inline-block');
			let print_heatmap = document.querySelector(".gondola" + id_scenario);
			html2canvas(print_heatmap).then(canvas => {
				let cenario = "";
				cenario = cenario.replace(/[ÀÁÂÃÄÅ]/g, "A");
				cenario = cenario.replace(/[àáâãäå]/g, "a");
				cenario = cenario.replace(/[ÈÉÊË]/g, "E");
				cenario = cenario.replace(/[ç]/gi, 'c');
				cenario = cenario.replace(/[^a-z0-9]/gi, '_');
				var a = document.createElement('a');
				document.getElementById("wrapper").appendChild(a);
				a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
				$("#img_val").val(a.href);
				$("#myForm").attr("action", "<?php echo base_url('index.php/gera_heatmap') ?>" + "/" + id_scenario)
				$("#myForm").submit();
				$('#modal_heatmap').modal('hide');
				$("#submit").toggleClass('disabled').text('Gerar Heatmap');
			});
			$('.gondola' + id_scenario).css('display', 'none');
			$('body').toggleClass('disablescroll');
		}, 500);
	}
	$('#heatmap').click(function() {
		$('#modal_heatmap').modal('show');
	});

	$("#submit").click(function(e) {
		e.preventDefault();
		$("#submit").toggleClass('disabled').text('');
		$("#submit").append('<div class="spinner-border text-white" style="width:1rem;height:1rem;" role="status"></div><span> Aguarde...</span>');
		let check = $("input[type='radio']:checked").length;
		let idCenario = $("input[type='radio']:checked").val();
		tirarPrint(idCenario)
		if (check <= 0) {
			$(".alert").css('display', '');
		} else {
			$(".alert").css('display', 'none');
		}
	});
</script>

<style>
	.page-link {
		cursor: pointer;
	}

	#div_pag {
		display: flex;
		justify-content: flex-end;
	}
</style>

<script>
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
	function get_relatory(id) {
		$.ajax({
			url: `<?php echo base_url('/index.php/get_purchase/'); ?>/${id}`,
			type: 'GET',
			success: function(data) {
				data = JSON.parse(data);
				document.getElementById('client').innerHTML = data.client;
				document.getElementById('date').innerHTML = ((data.data.length > 0) ? data.data : 'Nenhum.');
				document.getElementById('total').innerHTML = ((data.order_total.length > 0) ? data.order_total : 'Nenhum.');
				document.getElementById('purchased_products').innerHTML = ((data.order_detail.length > 0) ? data.order_detail : 'Nenhum.');
				document.getElementById('interacted_products').innerHTML = ((data.sequence.length > 0) ? data.sequence : 'Nenhum.');
				document.getElementById('removed_in_cart').innerHTML = ((data.removed_cart.length > 0) ? data.removed_cart : 'Nenhum.');
				document.getElementById('removed_in_checkout').innerHTML = ((data.removed_checkout.length > 0) ? data.removed_checkout : 'Nenhum.');
				document.getElementById('open_modal_purchase').click();
			},
		})
	}
</script>
