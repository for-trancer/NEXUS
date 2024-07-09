<?php
    session_start();

    include 'sql.php';

    if(isset($_GET['q'])){
        $query = $_GET['q'];
        $sql = "SELECT * FROM users WHERE username LIKE '%$query%' OR name LIKE '%$query%';";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()){
                if($row['username']==$_SESSION['user'])
                {
                // do nothings
                }
                else
                {
                    $users = $row['username'];
                    echo "<a href='#' data-username='".$users."' id='show-user-name' onClick='messageSectionOpener(this)'>";
                    echo "<div class='message-beginner'>";
                    if($row['hasCustomAvatar']==0)
                    {
                        echo "<div class='picture-container'>";
                        echo "<img src='img/avatar.png' class='displayPicture'>";
                        echo "</div>";
                    }
                    else
                    {
                        echo "<div class='picture-container'>";
                        echo "<img src='uploads/dp/$users.jpg' class='displayPicture'>";
                        echo "</div>";
                    }
                    echo "<label for='message-username'>".$row['username']."</label>";
                    echo "</div>";
                    echo "</a>";
                }
            }
        }
    }

    if(isset($_GET['getname']))
    {
        $name = $_GET['getname'];
        $query = "SELECT name,hasCustomAvatar FROM users WHERE username = '$name'";
        $result = $conn->query($query);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            if($row['hasCustomAvatar']==0)
            { 
                echo "<div class='display-profile-picture-container'>";   
                echo "<img src='img/avatar.png' class='display-profile-picture'>";
                echo "</div>";
            }
            else
            {
                echo "<div class='display-profile-picture-container'>"; 
                echo "<img src='uploads/dp/$name.jpg' class='display-profile-picture'>";
                echo "</div>";
            }
            echo "<div class='receiver-name-div'>";
            echo $row['name'];
            echo "</div>";
        }
    }

    if(isset($_GET['msg']))
    {
        $msg = $_GET['msg'];
        $sender = $_SESSION['user'];
        $receiver = $_GET['user'];

        $msg = htmlspecialchars($msg);

        $query = "INSERT INTO messages(sender,receiver,message,time) VALUES ('$sender','$receiver','$msg',now());";
        $conn -> query($query);
    }

    if(isset($_GET['usr']))
    {
        $receiver = $_GET['usr'];
        $user = $_SESSION['user'];
        $query = "SELECT * from messages WHERE (sender = '$user' AND receiver = '$receiver') OR (sender = '$receiver' AND receiver = '$user') ORDER BY id";
        $result = $conn->query($query);

        while($row = $result->fetch_assoc())
        {
            if($row['sender'] == $_SESSION['user'])
            {
                echo "<div id='message-holder'>";
                echo "<label id='users-main-holder' class='hidden'>You</label>";
                echo "<br>";
                if($row['isaudio'] == 1)
                {
                    echo "<div id='audio-container'>";
                    echo "<audio src=".$row['audio']." controls>";
                    echo "</div>";
                }
                else if($row['isfile'] == 1)
                {
                    echo "<a href=".$row['filepath']." class='a-formatter' download>";
                    echo "<div class='file-container'>";
                    echo "<img src='img/icons/attachment.png' class='file-container-icon'/>";
                    echo "<label for='file-container-name'>".$row['filename']."</label>";
                    echo "</div>";
                    echo "</a>";
                }
                else if($row['isimagefile'] == 1)
                {
                    echo "<div class='image-file-container'>";
                    echo "<img src='".$row['filepath']."' class='image-file'>";
                    echo "</div>";
                }
                else
                {
                echo "<label for='message-content' id='message-content'>".$row['message']."</label>";
                echo "<br>";
                }
                echo "<br>";
                $time_obj = new DateTime($row['time']);
                $formatted_time = $time_obj->format("h:iA");
                echo "<label for='msg-time' id='msg-time'>".$formatted_time."</label>";
                echo "<label id='msg-time-org' class='hidden'>".$row['time']."</label>";
                echo "</div>";
            }
            else
            {
                echo "<div id='message-holder-user'>";
                echo "<label for='user-main-holder' id='receiver-main-holder'>".$row['sender']."</label>";
                echo "<br>";
                if($row['isaudio']==1)
                {
                    echo "<div id='audio-container'>";
                    echo "<audio src=".$row['audio']." controls>";
                    echo "</div>";
                }
                else if($row['isfile'] == 1)
                {
                    echo "<a href=".$row['filepath']." class='a-formatter' download>";
                    echo "<div class='file-container'>";
                    echo "<img src='img/icons/attachment.png' class='file-container-icon'/>";
                    echo "<label for='file-container-name'>".$row['filename']."</label>";
                    echo "</div>";
                    echo "</a>";
                }
                else if($row['isimagefile'] == 1)
                {
                    echo "<div class='image-file-container'>";
                    echo "<img src='".$row['filepath']."' class='image-file'>";
                    echo "</div>";
                }
                else
                {
                echo "<label for='message-content' id='message-content'>".$row['message']."</label>";
                echo "<br>";
                }
                echo "<br>";
                $time_obj = new DateTime($row['time']);
                $formatted_time = $time_obj->format("h:iA");
                echo "<label for='msg-time' id='msg-time'>".$formatted_time."</label>";
                echo "<label id='msg-time-org' class='hidden'>".$row['time']."</label>";
                echo "</div>";
            }
        }
    }

        if(isset($_GET['action'])){
            $user = $_SESSION['user'];
            $sql = "WITH RankedUsers AS (
                SELECT 
                    username,
                    MAX(time) AS latest_message_time,
                    ROW_NUMBER() OVER (PARTITION BY username ORDER BY MAX(time) DESC) AS rn
                FROM (
                    SELECT sender AS username, MAX(time) AS time 
                    FROM messages WHERE receiver='$user'
                    GROUP BY sender
                    UNION ALL
                    SELECT receiver AS username, MAX(time) AS time 
                    FROM messages WHERE sender='$user'
                    GROUP BY receiver
                ) AS subquery
                GROUP BY username
            )
            SELECT RankedUsers.username, RankedUsers.latest_message_time, messages.message
            FROM RankedUsers
            LEFT JOIN messages ON (messages.time = RankedUsers.latest_message_time AND (messages.sender = RankedUsers.username OR messages.receiver = RankedUsers.username))
            WHERE RankedUsers.rn = 1
            ORDER BY RankedUsers.latest_message_time DESC";

            $result = $conn->query($sql);
    
            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    if($row['username']==$_SESSION['user'])
                    {
                        // do nothing
                    }
                    else
                    {
                        $users = $row['username'];
                        $que = "SELECT hasCustomAvatar FROM users WHERE username = '$users'";
                        $rr = $conn->query($que);

                        $hasAv = $rr->fetch_assoc();

                        $hasdp = $hasAv['hasCustomAvatar'];

                        $avatar = $row['username'];
                        echo "<a href='#' id='show-user-name' data-username='".$avatar."' onClick='messageSectionOpener(this)'>";
                        echo "<div class='message-beginner-user'>";
                        if($hasdp==0)
                        {
                            echo "<div class='picture-container'>";
                            echo "<img src='img/avatar.png' class='displayPicture'>";
                            echo "</div>";
                            
                        }
                        else
                        {
                            echo "<div class='picture-container'>";
                            echo "<img src='uploads/dp/$users.jpg' class='displayPicture'>";
                            echo "</div>";
                        }
                        echo "<label for='message-username'>".$avatar."</label>";
                        $q = "SELECT message,isaudio,isfile,filename,isimagefile FROM messages WHERE (sender='$avatar' AND receiver='$user') OR (sender='$user' AND receiver='$avatar') ORDER BY id DESC LIMIT 1";
                        $r = $conn->query($q);
                        if($r && $r->num_rows>0)
                        {
                            $ro = $r->fetch_assoc();
                            if($ro['isaudio']==1)
                            {
                                echo "<img src='img/icons/voice.png' class='chatbar-message-icon' />";
                                echo "<label for='chatbar-message'>voice message</label>";
                            }
                            else if($ro['isfile']==1)
                            {
                                echo "<img src='img/icons/document.png' class='chatbar-message-icon' />";
                                echo "<label for='chatbar-message'>".$ro['filename']."</label>";
                            }
                            else if($ro['isimagefile']==1)
                            {
                                echo "<img src='img/icons/image.png' class='chatbar-message-icon' />";
                                echo "<label for='chatbar-message'>".$ro['filename']."</label>";
                            }
                            echo "<label for='chatbar-text-message' id='last-msg'>".$ro['message']."</label>";
                            echo "</div></a><br>";
                        }
                        else
                        {
                            echo "";
                        }
                    }
                }
            }
            else
            {
                echo "";
            }
        }

        if(isset($_FILES['audio']['tmp_name']) && !empty($_FILES['audio']['tmp_name']))
        {
            $uploadDir = 'uploads/audio/';

            $receiver = $_POST['receiver'];

            $senderName = $_SESSION['user'];

            $fileName = $senderName.'-'.uniqid().'.webm';

            $filePath = $uploadDir.$fileName;

            if(move_uploaded_file($_FILES['audio']['tmp_name'],$filePath))
            {
                $query = "INSERT INTO messages(sender,receiver,time,isaudio,audio) VALUES('$senderName','$receiver',now(),1,'$filePath')";
                $conn -> query($query);
            }
        }

        if(isset($_POST['delete_option']))
        {
            $user = $_POST['userData'];
            $time = $_POST['time'];

            if($user == "You")
            {
                $user = $_SESSION['user'];
            }

            $query = "SELECT isaudio,audio,isfile,filepath FROM messages WHERE (sender='$user' or receiver='$user') AND time = '$time'";

            $result = $conn->query($query);

            $row = $result -> fetch_assoc();

            if($row['isaudio']==1)
            {
                $fileLoc = $row['audio'];
                unlink($fileLoc);

                $sql = "DELETE FROM messages WHERE (sender='$user' or receiver='$user') AND time = '$time'";
                $conn -> query($sql); 
            }
            else if($row['isfile']==1)
            {
                $fileL = $row['filepath'];
                unlink($fileL);

                $sql = "DELETE FROM messages WHERE (sender='$user' or receiver='$user') AND time = '$time'";
                $conn -> query($sql); 
            }
            else
            {
               $q = "DELETE FROM messages WHERE (sender='$user' or receiver='$user') AND time = '$time'";
                $conn -> query($q);
            }
        }

        if(isset($_POST['translate_option']))
        {
            $user = $_POST['userData'];
            if($user == "You")
            {
                $user = $_SESSION["user"];
            }
            $time = $_POST['time'];
            $msg = $_POST['translateMsg'];
            $api_key = ''; # Obtain Your Google API From https://cloud.google.com/translate
            $target = 'en';
            $url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&q=' . rawurlencode($msg) . '&target=' . $target;
            
            $response = file_get_contents($url);

            $data = json_decode($response, true);

            if (isset($data['data']['translations'][0]['translatedText'])) {

            $translatedText = $data['data']['translations'][0]['translatedText'];

            $query = "UPDATE messages SET message = '$translatedText' WHERE (sender = '$user' OR receiver='$user') AND time = '$time'";

            $conn -> query($query);
            
            }
        }

        if(isset($_GET['displayprofile']))
        {
            $user  = $_SESSION['user'];

            $query = "SELECT name,hasCustomAvatar FROM users WHERE username = '$user';";

            $result = $conn->query($query);

            $row = $result -> fetch_assoc();

            if($row['hasCustomAvatar']==0)
            {
                echo "<div class='dp-container'>";
                echo "<img src='img/avatar.png' class='display-picture' id='dp-picture'>";
                echo "</div>";
                echo "<br>";
                echo "<label for='display-username'>".$user."</label>";
                echo "<br>";
                echo "<label for='display-name'>".$row['name']."</label>";
                echo "<br>";
            }
            else
            {
                echo "<div class='dp-container'>";
                echo "<img src='uploads/dp/$user.jpg' class='display-picture' id='dp-picture'>";
                echo "</div>";
                echo "<br>";
                echo "<label for='display-username'>".$user."</label>";
                echo "<br>";
                echo "<label for='display-name'>".$row['name']."</label>";
                echo "<br>";
            }
        }

        if(isset($_POST['imageData'])) {

            $imageData = $_POST['imageData'];
            $user = $_SESSION['user'];

            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $decodedImage = base64_decode($imageData);
        
            $uploadDirectory = 'uploads/dp/';
        
            $filename = $user . '.jpg';
        
            $filePath = $uploadDirectory . $filename;
            file_put_contents($filePath, $decodedImage);

            $query = "UPDATE users SET hasCustomAvatar=1 WHERE username='$user'";
            $conn -> query($query);
        } 

        if(isset($_GET['removeOption']))
        {
            $user = $_SESSION['user'];

            $fileLocation = "uploads/dp/".$user.".jpg";

            $query = "UPDATE users SET hasCustomAvatar=0 WHERE username = '$user'";

            $conn -> query($query);

            unlink($fileLocation);
        }

        if (isset($_POST['file-upload']) && $_POST['file-upload'] === 'true') {
            if (isset($_FILES['attachment'])) {
                $user = $_SESSION['user'];
                $receiver = $_POST['receiver'];
        
                $file = $_FILES['attachment'];
                $filename = $file['name'];
                $filename = str_replace(' ', '_', $filename); 
                $currentTimestamp = date('YmdHis');
                $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
                $FullFileName = $currentTimestamp . '_' . $receiver . '.' . $fileExtension;
                        
                $uploadDirectory = 'uploads/files/';
                $newFilePath = $uploadDirectory . $FullFileName;
                move_uploaded_file($file['tmp_name'], $newFilePath);
        
                $query = "INSERT INTO messages(sender, receiver, time, isfile, filename, filepath) VALUES ('$user', '$receiver', now(), 1, '$filename', '$newFilePath')";
                $conn->query($query);
            }
        }

        if (isset($_POST['image-upload']) && $_POST['image-upload'] === 'true') {
            if (isset($_FILES['imagefile'])) {
                $user = $_SESSION['user'];
                $receiver = $_POST['receiver'];
        
                $file = $_FILES['imagefile'];
                $filename = $file['name'];
                $filename = str_replace(' ', '_', $filename); 
                $currentTimestamp = date('YmdHis');
                $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
                $FullFileName = $currentTimestamp . '_' . $receiver . '.' . $fileExtension;
                        
                $uploadDirectory = 'uploads/files/';
                $newFilePath = $uploadDirectory . $FullFileName;
                move_uploaded_file($file['tmp_name'], $newFilePath);
        
                $query = "INSERT INTO messages(sender, receiver, time, isimagefile, filename, filepath) VALUES ('$user', '$receiver', now(), 1, '$filename', '$newFilePath')";
                $conn->query($query);
            }
        }

        if(isset($_GET['checkfaceid']))
        {
            $user = $_GET['username'];

            $query = "SELECT username FROM users WHERE username='$user'";
            $result = $conn->query($query);
            if($result->num_rows>0)
            {
                $r = exec("C:\\Users\\mrfor\\AppData\\Local\\Programs\\Python\\Python38\\python.exe facemodel.py C:\\Apache24\\htdocs\\uploads\\faceid\\test.png C:\\Apache24\\htdocs\\uploads\\faceid\\$user.png"); // add your python path
                if($r == "false")   
                {
                    echo "false";
                }
                else if($r == "true")
                {
                    $_SESSION['user']=$user;
                    echo "true"; 
                }
                else
                {
                    echo $r;
                }
                $temp_file = "uploads/faceid/test.png";
                if(file_exists($temp_file)) {
                    unlink($temp_file);
                }
            }
            else
            {
                echo "username not found";
            }
            
        }

        if(isset($_GET['changefid']))
        {
            $user = $_SESSION['user'];
            echo "faceid.php?username=".$user;
        }

        if (isset($_GET['profile-navbar'])) {
            $user = $_SESSION['user'];
            $query = "SELECT hasCustomAvatar FROM users WHERE username = ?";
            
            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                if ($row['hasCustomAvatar'] == 1) {
                    echo "<img src='uploads/dp/{$user}.jpg' class='profile-container-navbar-image' />";
                } else {
                    echo "<img src='img/avatar.png' class='profile-container-navbar-image' />";
                }
            } else {
                // Handle the case where the user doesn't exist
            }
        }


        if(isset($_GET['admin-action']))
        {
            if(isset($_GET['admin-action']) && $_GET['admin-action'] === 'username')
            {
                $username = $_GET['username'];
                $newusername = $_GET['newusername'];

                $query = "UPDATE users SET username = '$newusername' WHERE username='$username'";
                $conn->query($query);

                $query = "UPDATE messages SET sender = '$newusername' WHERE sender='$username'";
                $conn->query($query);

                $query = "UPDATE messages SET receiver = '$newusername' WHERE receiver='$username'";
                $conn->query($query);

                $profilePictureDir = 'uploads/dp/';
                $faceCamImageDir = 'uploads/faceid/';

                $oldProfilePicturePath = $profilePictureDir . $username . '.jpg';
                $newProfilePicturePath = $profilePictureDir . $newusername . '.jpg';
                $oldFaceCamImagePath = $faceCamImageDir . $username . '.png';
                $newFaceCamImagePath = $faceCamImageDir . $newusername . '.png';

                if (file_exists($oldProfilePicturePath)) {
                    rename($oldProfilePicturePath, $newProfilePicturePath);
                } 
                if (file_exists($oldFaceCamImagePath)) {
                    rename($oldFaceCamImagePath, $newFaceCamImagePath);
                }
            }
            else if(isset($_GET['admin-action']) && $_GET['admin-action'] === 'name')
            {
                $username = $_GET['username'];
                $name = $_GET['name'];

                $query = "UPDATE users SET name = '$name' WHERE username = '$username'";
                $conn->query($query);
            }
            else if(isset($_GET['admin-action']) && $_GET['admin-action'] === 'password')
            {
                $username = $_GET['username'];
                $password = $_GET['password'];

                $secure_pass =  password_hash($password,PASSWORD_DEFAULT);

                $query = "UPDATE users SET password='$secure_pass'";
                $conn->query($query);
            }
            else if(isset($_GET['admin-action']) && $_GET['admin-action'] === 'delete')
            {
                $username = $_GET['username'];

                $query = "DELETE FROM messages WHERE sender='$username' OR receiver='$username'";
                $conn->query($query);
                $query = "DELETE FROM users WHERE username='$username'";
                $conn->query($query);
            }
        }

        
        
        


    

    
