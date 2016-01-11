<?php

	//loon andmebaasi ühendused
	require_once("../config.php");
	$database = "if15_kadri";
	$mysqli = new mysqli($servername, $username, $password, $database);
	
	
	$email_error = "";
	$password_error = "";

	$email = "";
	$password = "";


	$name1_error = "";
	$name2_error = "";
	$username_error = "";
	$email1_error = "";
	$email2_error = "";
	$password1_error = "";
	$password2_error = "";
	$password1_repeat_error  = "";
	$email1_repeat_error  = "";

	$name1 = "";
	$name2 = "";
	$username = "";
	$email1 = "";
	$email2 = "";
	$password1 = "";
	$password2 = "";


	if($_SERVER["REQUEST_METHOD"] == "POST") {		
		if (isset($_POST["login"])) {

			if (empty($_POST["email"]) )  {
				$email_error = "see väli on kohustuslik";
			}else{

				$email = test_input($_POST["email"]);

			}	
			//Kontrollin, et parool ei ole tühi
			if (empty($_POST["password"]) )  {
				$password_error = "see väli on kohustuslik";
			}else{
				$password = test_input($_POST["password"]);
			}	
				if($email_error == "" && $password_error ==""){
			
				$hash = hash("sha512", $password);
				
				$stmt = $mysqli->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
				$stmt->bind_param("ss", $email, $hash);
				

				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
				

				if($stmt->fetch()){
					echo "Loggiti sisse !! Email ".$email." ja password õiged, kasutaja id=".$id_from_db;
				}else{
					echo "Wrong credentials!";
				}
				
				$stmt->close();
				
				
			}
					
		}
	}
	function test_input($data) {
		// võtab ära tühikud, enterid, tabid
		$data = trim($data);
		// tagurpidi kaldkriipsud \ 
		$data = stripslashes($data);
		// teeb html'i tekstiks < läheb &lt;
		$data = htmlspecialchars($data);
		return $data;
	}
		


	if($_SERVER["REQUEST_METHOD"] == "POST") {

		if (isset($_POST["registreeru"])) {

			if (empty($_POST["name1"]) )  {
				$name1_error = "see väli on kohustuslik";
			}else{
				$name1 = test_input($_POST["name1"]);
			}
			if (empty($_POST["name2"]) )  {
				$name2_error = "see väli on kohustuslik";
			}else{
				$name2 = test_input($_POST["name2"]);
			}
			if (empty($_POST["email1"]) )  {
				$email1_error = "see väli on kohustuslik";
			}else{
				$email1 = test_input($_POST["email1"]);
			}
			if (empty($_POST["email2"]) )  {
				$email2_error = "see väli on kohustuslik";
			}
			if ($_POST['email1']!= $_POST['email2'])
			 {
			     $email1_repeat_error = "Emailid ei ühtinud!!!!   ";
			}else{
				$email2 = test_input($_POST["email2"]);
			}
			if ($_POST['password1']!= $_POST['password2'])
			 {
			     $password1_repeat_error = "passwordid ei ühtinud!!!!! ";
			 }
			if (empty($_POST["password1"]) )  {
				$password1_error = "see väli on kohustuslik";
			}else{
				$passowrd1 = test_input($_POST["password1"]);
			}	
			if (empty($_POST["password2"]) )  {
				$password2_error = "see väli on kohustuslik";
			} else {
				//kui oleme siia jõudnud siis parool ei ole tühi
				//kontrollin et olek vähemalt 8 sümbolit pikk
				if(strlen($_POST["password2"]) < 8)	{
					$password2_error = "Peab olema vähemalt 8 tähemärki pikk!";
				}
				if(strlen($_POST["password1"]) < 8)	{
					$password1_error = "Peab olema vähemalt 8 tähemärki pikk!";


				}
			}
			if(	$email1_error == "" && $email2_error == "" && $password1_error == "" && $password2_error == "" && $name1_error == "" && $name2_error == "" && $password1_repeat_error == ""&& $email1_repeat_error == ""){
			
			//Räsi paroolist, mis salvestub andmebaasi
			$hash = hash("sha512", $password1);

			
			
			echo "Registreerisid kasutaja! username on ".$username." ja password on Õige  ja räsi on ".$hash;
			

			$stmt = $mysqli->prepare("INSERT INTO user_sample (firstname, lastname, email, password) VALUES (?,?,?,?)");

			
			
			// asendame ?-märgiud ss-s on string email, s on string passwd
			$stmt->bind_param("ssss", $name1, $name2, $email1, $hash);
			$stmt->execute();
			$stmt->close();
		}	
		}	
	}		
?>








<head>
	<title>Login page></title>
</head>


	<h2>Log in</h2>
		
		<form action="loggedin.php" method="post">
		<input name="email"e type="email" placeholder = "email"> <?php echo $email_error;  ?><br><br>
		<input name="password" type="password" placeholder = "parool"> <?php echo $password_error;  ?><br><br>
		<input type="submit" value="login" name="login">
		</form>

	<h2>Create user</h2>

		<form action="loggedin.php" method="POST"><br><br>
		<input name="name1" type="text" placeholder="eesnimi" /><?php echo $name1_error;  ?><br><br>
		<input name="name2" type="text" placeholder="perekonna nimi" /><?php echo $name2_error;  ?><br><br>
		<input name="email1" type="email" placeholder="Email" /><?php echo $email1_repeat_error;  ?><?php echo $email1_error;  ?><br><br>
		<input name="email2" type="email" placeholder="uuesti Email" /><?php echo $email2_error;  ?><br><br>
		<input name="password1" type="password" placeholder="parool" /><?php echo $password1_repeat_error;  ?><?php echo $password1_error;  ?><br><br>
		<input name="password2" type="password" placeholder="uuesti parool" /><?php echo $password2_error;  ?><br><br>
		<input type="submit" value="Registreeri" name="registreeru" /><br><br>
		</form>
</body>
</html>