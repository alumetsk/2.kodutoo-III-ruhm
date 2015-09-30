<?php
	//LOGIN.PHP
	//echo $_POST["email"];
	$email_error = "";
	$email1_error = "";
	$password_error = "";
	$password1_error = "";
	$password_repeat_error = "";
	
	//Muutujad andmebaasi väärtuste jaoks
	$email = "";
	$email1 = "";
	$password = "";
	$password1 = "";
	$password_repeat = "";
	
	
	//kontrollin et input nuppu vajutati
	
	if($_SERVER["REQUEST_METHOD"]== "POST") {
		//echo "Keegi vajutas nupppu";
		
		if(empty($_POST["email"])) {
			$email_error = "See väli on kohustuslik";
		}
		//Kontrollin, et parool ei ole tühi
		if(empty($_POST["password"])) {
			$password_error = "See väli on kohustuslik";
		}	else {
			//kui siia jõudnud siis pole tühi
			//Kontrollin parooli pikkust
			if(strlen($_POST["password"]) <8) {
				$password_error = "Peab olema vähemalt 8 tähemärki pikk!";
			}
		}
	}
	// kontrollime, et keegi vajutas registreerimise	nuppu
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		//echo "keegi vajutas nuppu";
		
		//vajutas register nuppu
		
		if(isset($_POST["register"])) {
			echo "vajutas register nuppu";
			
			if(empty($_POST["email"]))  {
				$email_error = "Email on kohustuslik!";
			} else {
				$email = test_input($_POST["email"]);
			}
			
			if(empty($_POST["password"])) {
				$password_error = "Parool on kohustuslik!";
			} else {
				//kui oleme siia jõudnud siis parool ei ole tühi
				//kontrollin et olek vähemalt 8 sümbolit pikk
				if(strlen($_POST["password"]) < 8) {
					$password_error = "Parool peab olema vähemalt 8 tähemärki pikk!";
				}
				if($_POST["password"] != $_POST["password_repeat"]) {
					$password_repeat_error = "VEATEADE: Paroolid peavad kattuma!";
				}
			}
			
			
		}
		
		
		
	}


	function test_input($data) {
		// võtab ära tühikud, enterid, tabid
		$data = trim($data);
		// tagurpidi kaldkriipsud
		$data = stripslashes($data);
		// teeb htmli tekstiks
		$data = htmlspecialchars($data);
		return $data;
	}
	
?>	

<html>
<head>
	<title>Login page></title>
</head>
<body>
	<h2>Log in</h2>
	<form action="login.php" method="post" >
		<input name="email" type="email" placeholder="E-post"> <?php echo $email_error; ?> <br><br>
		<input name="password" type="password" placeholder="Parool"> <?php echo $password_error; ?> <br><br>
		<input type ="submit" value="Logi sisse">
	</form>
	<h2>Create user</h2>
	<form action="login.php" method="post" >
		E-post:<br>
				<input name="email" type="email" placeholder="E-post" value="<?php echo $email; ?>"> <?php echo $email_error; ?><br>
				Parool:<br>
				<input name="password" type="password" placeholder="Parool"> <?php echo $password_error; ?><br>
				Parool uuesti:<br>
				<input name="password_repeat" type="password" placeholder="Parool uuesti"> <?php echo $password_error; ?><br><br>
				<input name="register" type="submit" value="Registreeri"><br><br>
				<?php echo $password_repeat_error; ?>
			</form>
</body>
</html>