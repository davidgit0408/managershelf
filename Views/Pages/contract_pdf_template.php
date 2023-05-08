<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">
	p.footer {
		text-align: center;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#borda {
		border: 1px solid #333333;
		padding:0 10px;
	}
	p.center,b.center{text-align:center;}
	b{text-decoration:underline;}
	</style>
</head>
<?php 
    $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);
?>
<body>

<div id="container">
	<p><?php echo $contract[0]['text'] ?></p>
</div>

</body>
</html>