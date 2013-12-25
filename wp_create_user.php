<?php
$err_msg = "";
if($_SERVER['REQUEST_METHOD']=="POST") {
	if($_POST['username']!="" && $_POST['password']!="" && $_POST['confirm_password']!="" && $_POST['password']!="") {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)===FALSE) {
			$err_msg = "Please enter a valid email address!";
		} elseif($_POST['password']!=$_POST['confirm_password']) {
			$err_msg = "Passwords do not match!";
		} else {
			$user_data = array(
							"user_login"  => esc_sql($_POST['username']),
							"user_pass" => esc_sql($_POST['password']),
							"user_email" => esc_sql($_POST['email'])
						);
			$user_id = wp_insert_user($user_data);
			if(gettype($user_id)=="integer") {
				wp_set_current_user( $user_id );
        		wp_set_auth_cookie( $user_id );
        		$current_user = wp_get_current_user();
		        do_action( 'wp_login', $current_user->user_login );
		        header("Location: " . site_url('register'));
			} else {
				foreach($user_id->errors as $error) {
					$err_msg .= $error[0] . "<br>";
				}
			}
		}
	}
}
?>
