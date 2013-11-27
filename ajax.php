<?php
require "bootstrap.php";
if($_SERVER['REQUEST_METHOD']=="POST") {
	if(isset($_POST['service']) && isset($_POST['method']))  {
		$data_array = array();
		$svc_file = "functions/{$_POST['service']}.php";
		if(file_exists($svc_file)) {
			require $svc_file;
			$fn = $_POST['method'];
			if(is_callable($fn)) {
				$params = array();
				if(isset($_POST['page'])) $params[]  = dbSafe($_POST['page']);
				if(isset($_POST['query'])) $params[] = dbSafe($_POST['query']);
				if(isset($_POST['type'])) $params[]  = dbSafe($_POST['type']);
				if(isset($_POST['data'])) $params[]  = $_POST['data'];
				$data_array = call_user_func_array($fn, $params);
			}
		}
		echo json_encode($data_array);
	}
}
