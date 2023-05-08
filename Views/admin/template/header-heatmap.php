<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = \Config\Services::session();
    $name = $session->get('name');
    $role = $session->get('role');
	if($notifications) $notifications = count($notifications);
	else $notifications = 0;
?>

<div class="wrapper" id="wrapper">
<main class="content mt-3">
    <div class="container-fluid">
        <div class="header">
    		<h1 class="header-title">Heatmap - Cenário <?php echo $cenario[0]['name']; ?>.</h1>
    	    <p class="header-subtitle d-none">Você tem notificações!</p>    		
    	</div>
    </div>