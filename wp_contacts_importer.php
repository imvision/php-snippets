<?php
set_time_limit(0);
require( dirname(__FILE__) . '/wp-load.php' );
$file = "csv/" . $_REQUEST['file'] . ".csv";

$missed    = "csv/eq_contact_errors.txt";
$remaining = file($missed, FILE_IGNORE_NEW_LINES);
$errors    = array();

if(file_exists($file)) {

	$source  = fopen($file, 'r') or die("Problem open file");
	$head    = fgetcsv($source, 1000, ",");
	$created = 0;

	while (($data = fgetcsv($source, 1000, ",")) !== FALSE) {

		if(! in_array($data[0], $remaining)) {
			continue;
		}

		$password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

		// $parts = explode("@", $data[0]);
		// $username = $parts[0];
		
		$username = $data[1] . "14";
		

		$email = $data[0];
		$fname = $data[1];
		$lname = $data[2];

		$user_data = array(
			"user_login"  => esc_sql($username),
			"user_pass"   => esc_sql($password),
			"user_email"  => esc_sql($email),
			"first_name"  => esc_sql($fname),
			"last_name"   => esc_sql($lname)
		);
		
		$user_id = wp_insert_user($user_data);

		if(gettype($user_id)=="integer") {

			++$created;
			for ($j=3; $j<count($head); $j++) {
				$meta_key = str_replace(" ", "_", $head[$j]);
				add_user_meta( $user_id, $meta_key, $data[$j] );
			}

			$msg  = "Hi {$data[1]} {$data[2]},";
			$msg .= "<br><br>Your account has been created at <a href='http://eq.org/'>EQ.ORG</a>. Here are the account details:<br>";
			$msg .= "<br>Username: {$username}";
			$msg .= "<br>Password: {$password}";
			wp_mail( $email, "You account has been created at EQ.ORG", $msg);
		} else {

			foreach($user_id->errors as $error) {
				$errors[] = array("Error"=>$error[0], "Email"=>$email);
			}
		}
    }
} else {
	echo $file . " not found!";
	exit();
}
?>
<html>
<body>
	<p><?php echo $created ;?> users created!</p>
	<p><?php var_dump($errors);?></p>
</body>
</html>
