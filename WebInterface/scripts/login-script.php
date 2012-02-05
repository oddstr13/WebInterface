<?php

	session_start();
	require 'config.php';
	
	if (isset($_COOKIE["oswebs"])) {
		$osweb = new mysqli("localhost", "mcweb", "YruUJyTXtK5pNRyu5fand2ku", "mcweb");

		$osweb_query = sprintf("SELECT * FROM `osweb_sessions` WHERE `id`='%1s';", $osweb->real_escape_string($_COOKIE["oswebs"]));

		$result = $osweb->query($osweb_query);
		$websession = $result->fetch_array();
		$result->close();

		if ($websession) {
			$osweb_query = sprintf("SELECT * FROM `osweb_profiles` WHERE `userid`='%1s';", $osweb->real_escape_string($websession["userid"]));

			$result = $osweb->query($osweb_query);
			$webprofile = $result->fetch_array();
			$result->close();

			if (!$webprofile["ign"]) {
				header("Location: /verifyign.py");
				exit();
			}
		} else {
			header("Location: /login.py");
			exit();
		}
	} else {
		header("Location: /login.py");
		exit();
	}
//	exit(); /* just for DEBUGing */
	$Username = $webprofile["ign"];
	$result = mysql_query("SELECT * FROM WA_Players WHERE name='$Username'");

	$count = mysql_num_rows($result);
	$playerRow = mysql_fetch_assoc($result);
	//echo "<pre>";
	//print_r($playerRow);
	//echo "</pre>";
	if ($count==1){
		$hour = time() + 3600;
		$_SESSION['User'] = $playerRow['name'];		
		if ($playerRow['isAdmin'] == 1){$_SESSION['Admin'] = true;}else{$_SESSION['Admin'] = false;}
		if ($playerRow['canBuy'] == 1){$_SESSION['canBuy'] = true;}else{$_SESSION['canBuy'] = false;}
		if ($playerRow['canSell'] == 1){$_SESSION['canSell'] = true;}else{$_SESSION['canSell'] = false;}
		header("Location: ../index.php");
	}else{
		$past = time() - 100;
		unset($_SESSION['User']);
		unset($_SESSION['canBuy']);
		unset($_SESSION['canSell']);
		unset($_SESSION['Admin']);
		header("Location: /login.py");
	}
	
?>
