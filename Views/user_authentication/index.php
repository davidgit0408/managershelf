<!-- Display login button / Facebook profile information -->
<?php if(!empty($authURL)){ ?>
	<h2>CodeIgniter Facebook Login</h2>
    <a href="<?php echo $authURL; ?>"><button class="btn mb-1 btn-facebook"><i class="align-middle fab fa-facebook"></i> Facebook</button></a>
<?php }else{ ?>
    <h2>Facebook Profile Details</h2>
    <div class="ac-data">
        <img src="<?php echo $userData['picture']; ?>"/>
        <p><b>Facebook ID:</b> <?php echo $userData['oauth_uid']; ?></p>
        <p><b>Name:</b> <?php echo $userData['first_name'].' '.$userData['last_name']; ?></p>
        <p><b>Email:</b> <?php echo $userData['email']; ?></p>
        <p><b>Gender:</b> <?php echo $userData['gender']; ?></p>
        <p><b>Logged in with:</b> Facebook</p>
        <p><b>Profile Link:</b> <a href="<?php echo $userData['link']; ?>" target="_blank">Click to visit Facebook page</a></p>
        <p><b>Logout from <a href="<?php echo $logoutURL; ?>">Facebook</a></p>
    </div>
<?php } ?>