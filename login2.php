<?php

	//loon andmebaasi ühendused
	require_once("../config.php");
	$database = "if15_kadri";
	$mysqli = new mysqli($servername, $username, $password, $database);
	
	

	//Muutujad errorite jaoks
	$email_error = "";
	$email1_error = "";
	$password_error = "";
	$password1_error = "";
	$password1_repeat_error = "";
	
	
	//Muutujad andmebaasi väärtuste jaoks
	$email = "";
	$email1 = "";
	$password = "";
	$password1 = "";
	$password1_repeat = "";
	$name1 = "";
	$name2 = "";
	
	
	//kontrollin et input nuppu vajutati
	
	if($_SERVER["REQUEST_METHOD"]== "POST") {
		
		if(isset($_POST["login"])) {
			
			if(empty($_POST["email"])) {
				$email_error = "See väli on kohustuslik";
			}else{
				$email = cleanInput($_POST["email"]);
			}
		//Kontrollin, et parool ei ole tühi
			if(empty($_POST["password"])) {
				$password_error = "See väli on kohustuslik";
			}	else {
				$password = cleanInput($_POST["password"]);
			}
			
			if($password_error == "" && $email_error == ""){
				echo "Võib sisse logida. Kasutaja on ".$email." ja parool on ".$password;
				
				$hash = hash("sha512", $password);
				
				$stmt = $mysqli->prepare("SELECT id, email FROM login2 WHERE email=? AND password=?");
				$stmt->bind_param("ss", $email, $hash);
				
				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
				
				if($stmt->fetch()){
					echo "Email ja parool on õiged, kasutaja id=".$id_from_db;
					
				}else{
					echo "Wrong credentials!";
				}
				
				$stmt->close();
				
			}
		}
		
		if(isset($_POST["register"])) {
			
			
			if(empty($_POST["email"]))  {
				$email_error = "Email on kohustuslik!";
			} else {
				$email = cleanInput($_POST["email"]);
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
			if(	$email1_error == "" && $password1_error == ""){
				
				//Räsi paroolist, mis salvestub andmebaasi
				$hash = hash("sha512", $password1);
				
				
				echo "Võib kasutajat luua. Kasutajanimi on ".$email1."
				ja parool on ".$password1."ja räsi on ".$hash;
				
				$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
				
				// asendame ?-märgiud ss-s on string email, s on string passwd
				
				$stmt->bind_param("ss", $email1, $hash);
				$stmt->execute();
				$stmt->close();
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
	$mysqli->close();
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
				Eesnimi:<br>
				<input name="name1" type="name" placeholder="Eesnimi"> <br>
				Perekonnanimi:<br>
				<input name="name2" type="name" placeholder="Perekonnanimi"> <br>
				E-post:<br>
				<input name="email" type="email" placeholder="E-post" value="<?php echo $email1; ?>"> <?php echo $email1_error; ?><br>
				Parool:<br>
				<input name="password" type="password" placeholder="Parool"> <?php echo $password1_error; ?><br>
				Parool uuesti:<br>
				<input name="password_repeat" type="password" placeholder="Parool uuesti"> <?php echo $password1_error; ?><br><br>
				<input name="register" type="submit" value="Registreeri"><br><br>
				<?php echo $password1_repeat_error; ?>
			</form>
</body>
</html>