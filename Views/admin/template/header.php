<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
$name = $session->get('name');
$role = $session->get('role');
if ($notifications) $notifications = count($notifications);
else $notifications = 0;

?>

<main class="content mt-3">
	<div class="container-fluid" id="cf">
		<div class="header">
			<h1 class="header-title">
				Bem vindo(a), <?php echo $name ?>!
			</h1>
			<?php if ($role == 'admin') { ?>
				<p id="number_notify" class="header-subtitle"></p>
			<?php } ?>
			
			<?php
			$success_msg = $session->getFlashdata('success_msg');
			$error_msg = $session->getFlashdata('error_msg');

			if ($error_msg) { ?>
				<div class="mt-3 alert alert-danger alert-dismissible" role="alert">
					<div class="alert-message">
						<?php echo $error_msg; ?>
					</div>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php
			}
			if ($success_msg) { ?>
				<div class="mt-3 alert alert-success alert-dismissible" role="alert">
					<div class="alert-message">
						<?php echo $success_msg; 
						?>
					</div>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php
			}
			?>
		</div>