<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/brand/logo.png') ?>" type="image/x-icon" />
	<title>404 - ManagerShelf</title>
	<style type="text/css">
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		a {
			color: #777;
			border-bottom: 3px dashed #777;
			text-decoration: none;
			font-weight: bold;
		}

		body {
			background: #eee;
			display: flex;
			align-items: center;
			text-align: center;
			justify-content: center;
			min-height: 100vh;
			color: #333;
		}

		h1 {
			width: 100%;
			display: block;
			text-align: center;
			font-family: sans-serif;
			font-weight: bolder;
			font-size: 5em;
		}

		body>div {
			min-width: 25vw;
			color: #f79525;
		}



		h2 {
			font-family: sans-serif;
			font-weight: bolder;
		}

		.notFound {
			fill: #f79525;
			stroke: #f79525;
			stroke-width: 1.5;
			animation: lupa 5s infinite;
		}

		@keyframes lupa {
			from {
				fill: #f77;
				stroke: #f79525;
				stroke-dasharray: 0;
			}

			to {
				fill: #f79525;
				stroke: #f79525;
				stroke-dasharray: 95;
			}
		}

		.notFound rect {
			stroke: none;
		}

		@media screen and (max-width: 600px) {
			body {
				flex-direction: column;
			}
		}


		/* fill button */

		.fill-button {
			position: relative;
			overflow: hidden;
			display: inline-block;
			width: 150px;
			height: 30px;
			border: 1px solid #ffa200;
			text-align: center;
			box-sizing: border-box;
			color: #ffa200;
			text-decoration: none;
			cursor: pointer;
			line-height: 28px;
			font-family: 'arial';
			border-radius: 4px;
		}

		.fill-button>span {
			display: block;
		}

		.fill-button>.fill-button-hover:after,
		.fill-button>.fill-button-hover:before {
			position: absolute;
			top: 0;
			opacity: 0;
			display: block;
			content: "";
			width: 0;
			height: 30px;
		}

		.fill-button>.fill-button-hover:after {
			background-color: #fff;
			transform: skewX(45deg);
			transform-origin: center center;
			transition: all .35s, opacity .4s;
			left: 50%;
		}

		.fill-button .fill-button-hover:before {
			background-color: #ffa200;
			transition: opacity 1s;
		}

		.fill-button .fill-button-text {
			z-index: 1;
			position: relative;
			color: #ffa200;
			transition: color .35s;
		}

		.fill-button:hover .fill-button-text {
			color: #7a4a00;
		}

		.fill-button .fill-button-hover:hover:after {
			opacity: 1;
			left: 2%;
			width: 95%;
			transform: skewX(45deg);
		}

		.fill-button>.fill-button-hover:hover:before {
			opacity: 1;
			left: 0;
			width: 100%;
		}
	</style>
</head>

<body>

	<div id="box">
		<svg class="notFound" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="100px" viewBox="0 0 24 24" width="100px">
			<g>
				<rect fill="none" height="24" width="24" />
			</g>

			<g>
				<g>
					<path d="M15.5,14h-0.79l-0.28-0.27C15.41,12.59,16,11.11,16,9.5C16,5.91,13.09,3,9.5,3C6.08,3,3.28,5.64,3.03,9h2.02 C5.3,6.75,7.18,5,9.5,5C11.99,5,14,7.01,14,9.5S11.99,14,9.5,14c-0.17,0-0.33-0.03-0.5-0.05v2.02C9.17,15.99,9.33,16,9.5,16 c1.61,0,3.09-0.59,4.23-1.57L14,14.71v0.79l5,4.99L20.49,19L15.5,14z" />
					<polygon points="6.47,10.82 4,13.29 1.53,10.82 0.82,11.53 3.29,14 0.82,16.47 1.53,17.18 4,14.71 6.47,17.18 7.18,16.47 4.71,14 7.18,11.53" />
				</g>
			</g>
		</svg>
		<img src="<?php echo base_url('assets/img/brand/logo.png') ?>">
		<h1>404</h1>
		<h2>Pagina nao encontrada.</h2>
		<br>
		<br>
		<b style="font-size: 130%;">Verifique se o endereco está digitado corretamente ou volte para a página anterior!</b> 
		<br>
		<br>



		<a class="fill-button" onclick="goBack()">
			<span class="fill-button-hover">
				<span class="fill-button-text">Voltar Página</span>
			</span>
		</a>
		<br>

	</div>

	<script>
		function goBack() {
			window.history.back()
		}
	</script>
</body>

</html>