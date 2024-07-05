<?php
	session_start();
?>
<!DOCTYPE html>
<head>
	<title>NEXUS | SignIn</title>
	<link rel="stylesheet" href="style/login.css">
	<meta encodeing="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<script src="script/validate.js"></script>
</head>
<body>
	<?php

	include 'sql.php';

	$username = $password = "";
	$usernameErr = $passwordErr = $sqlErr = "";
	$isValid = true;

	if($_SERVER["REQUEST_METHOD"]=="POST")
	{
		if($conn->connect_error)
		{
			$isValid = false;
			$sqlErr = "connection error!";
		}

		if(empty($_POST["username"]))
		{
			$isValid = false;
			$usernameErr = "enter the username!";
		}
		else
		{
			$username = Formatter($_POST["username"]);
			$username = strtolower($username);

			$result = $conn->query("SELECT username FROM users WHERE username='$username';");

			if(!($result->num_rows>0))
			{
				$isValid = false;
				$usernameErr = "username doesnt exist!";
				$username = "";
			}
		}

		if(empty($_POST["password"]))
		{
			$isValid = false;
			$passwordErr = "password required!";
		}
		else
		{
			if($isValid)
			{
				$password = $_POST["password"];

				$result = $conn->query("SELECT password FROM users WHERE username='$username';");
				$row = $result->fetch_assoc();
				if($result->num_rows>0)
				{
					$hashed_password = $row["password"];
					$check = password_verify($password,$hashed_password);
					if(!$check)
					{
						$isValid = false;
						if(!empty($username))
						{
							$passwordErr = "Password Incorrect!";
						}
					}
				}
				else
				{
					$isValid = false;
					$passwordErr = "Password Incorrect!";
				}
			}
		}
		if($isValid)
		{
			$query = "SELECT isadmin FROM users WHERE username='$username'";
			$res = $conn->query($query);
			$r = $res->fetch_assoc();

			if($r['isadmin']==1)
			{
				$_SESSION['admin'] = $username;
				header("Location: admin.php");
				exit;
			}
			else
			{
				$_SESSION['user'] = $username;
				header("Location: main.php");
				exit;
			}
		}
	}


	function Formatter($data)
	{
		$data = trim($data);
		$data = stripcslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	?>
	<div id="container">
		<img src="img/nexus-logo.png" class="logo"> 
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
				<label for="username">Username</label>
				<br>
				<input type="text" name="username" value="<?php echo $username;?>" class="text-box" id="user">
				<br>
				<div class="error"><?php echo $usernameErr;?></div>
				<br>
				<label for="password">Password</label>
				<br>
				<input type="password" name="password" class="text-box">
				<br>
				<div class="error"><?php echo $passwordErr;?></div>
				<br>
				<div class="error"><?php echo $sqlErr;?></div>
				<br>
				<span class="facemsg">Sign In With Face Id?<a href="faceidcheck.php" class="hyper-link" id="face-id"> Face Id</a></span>
				<br>
				<span class="msg">Doesn't have an account?<a href="signup.php" class="hyper-link"> Sign Up!</span>
				<br>
				<input type="submit" value="Sign In" class="submit-btn">
				
		</form>
	</div>
</body>
</html>