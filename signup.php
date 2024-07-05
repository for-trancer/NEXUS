<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS | SignUp</title>
    <script src="script/script.js"></script>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <script>
        let valid = false;
    </script>
    <?php

        include 'sql.php';

        $username = $email = $fullname = $password = $confirm = "";
        $userErr = $nameErr = $passErr = $emailErr = $confirmErr = $sqlErr = "";
        $folderPath = 'uploads/faceid/';

        $isValid = true;

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(empty($_POST['fullname']))
            {
                $isValid = false;
                 $nameErr = "name is required";
            }
            else
            {
                $fullname = Formatter($_POST['fullname']);
                if(!(strlen($fullname)<=30))
                {
                    $isValid = false;
                    $nameErr = "Name should be 30 characters or less.";
                }
            }

            if(empty($_POST['username']))
            {
                $isValid=false;
                $userErr = "username is required";
            }
            else
            {
                $username = Formatter($_POST["username"]);
                $username = strtolower($username);

                if(strlen($username)<=3)
                {
                    $isValid = false;
                    $userErr = "length of username is short";
                }
                else
                {
                    if(!preg_match("/^[a-z\d_]{3,30}$/i",$username))
                    {
                        $isValid = false;
                        $userErr = "*username can contain only letters and numbers!"; 
                    }
                    else
                    {
                        $result = $conn->query("SELECT username FROM users WHERE username='$username';");
                        if($result->num_rows > 0)
                        {
                            $isValid = false;
                            $userErr = "username already exists!";
                        }
                    }
                }
            }

            if(empty($_POST['email']))
            {
                $isValid = false;
                $emailErr = "email is required!";
            }
            else
            {
                $email = Formatter($_POST["email"]);
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $isValid = false;
                    $emailErr = "email format is incorrect!";
                }
                else
                {
                    $result = $conn->query("SELECT email FROM users WHERE email='$email';");
                    if($result->num_rows > 0)
                    {
                        $isValid = false;
                        $emailErr = "email has used by another account";
                    }
                }
            }

            if(empty($_POST["password"]))
            {
                $isValid = false;
                $passErr = "password is required";
            }
            else
            {
                $password = $_POST["password"];
                if(!(strlen($password)>7))
                {
                    $isValid = false;
                    $passErr = "password must have atleast length of 8";
                }
            }

            if(!empty($_POST['password']))
            {
                if(empty($_POST['confirm']))
                {
                    $isValid = false;
                    $confirmErr= "confirm your password";
                }
                else
                {
                    $confirm = $_POST['confirm'];
                    if(!($confirm==$password))
                    {
                        $isValid = false;
                        $confirmErr = "passwords doesn't match";
                    }
                }
            }

            if($conn->connect_error)
            {
                $isValid = false;
                $sqlErr = "sql connection error";
            }

            if($isValid)
            {
                $query = "INSERT INTO users(username,name,email,password) VALUES(?,?,?,?);";

                $securepassword = password_hash($password,PASSWORD_DEFAULT);

                $stmt = $conn->prepare($query);

                $stmt->bind_param("ssss",$username,$fullname,$email,$securepassword);

                $stmt->execute();

                $stmt->close();

                header("Location: faceid.php?username=$username");
            }
        }
        

        function Formatter($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    ?>
    <div id="container">
        <img src="img/nexus-logo.png" class="logo">    
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <label for="name">Full Name</label>
            <br>
            <input type="text" name="fullname" value="<?php echo $fullname;?>"></input>
            <br>
            <div class="error"><?php echo $nameErr;?></div>
            <br>
            <label for="username">Username</label>
            <br>
            <input type="text" name="username" value="<?php echo $username;?>"></input>
            <br>
            <div class="error"><?php echo $userErr;?></div>
            <br>
            <label for="email">Email</label>
            <br>
            <input type="text" name="email" value="<?php echo $email;?>"></input>
            <br>
            <div class="error"><?php echo $emailErr;?></div>
            <br>
            <label for="password">Password</label>
            <br>
            <input type="password" name="password" value="<?php echo $password;?>"></input>
            <br>
            <div class="error"><?php echo $passErr;?></div>
            <br>
            <label for="confirm">Confirm Password</label>
            <br>
            <input type="password" name="confirm" value=""></input>
            <br>
            <div class="error"><?php echo $confirmErr;?></div>
            <br>
            <span class="msg">Already have an account?<a href="login.php" class="hyper-link"> Sign In</a></span>
           
            <br>
            <input type="submit" value="Sign Up" class="submit-btn">
        </form>
    </div>
</body>
</html>
