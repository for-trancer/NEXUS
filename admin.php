<?php 
                    session_start();
                    include 'sql.php';
                    if(!isset($_SESSION['admin']))
                    {
                        header("Location: login.php");
                    }
                    else
                    {
                        $user = $_SESSION['admin'];
                    }
                    $query = "SELECT * FROM users WHERE username='$user'";
                    $res = $conn->query($query);
                    $r = $res->fetch_assoc();
?>
<!DOCTYPE html>
    <head>
        <meta encodeing="UTF-8">
        <title>NEXUS | <?php $user=$_SESSION['admin'];echo $user?></title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="style/style.css" id="css-link">
        <link rel="stylesheet" id="font-link">
        <script src="script/admin.js" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body style="background:#d5d5d5;">
        <div id="admin-header">
            <img src="img/icons/nexus-logo.png" class="admin-panel-logo" id='header-icon'/>
            <label for="header-title">NEXUS</label>
            <?php 
            $username = $r['username'];
            if($r['hasCustomAvatar']==1)
            {
                echo "<img src='uploads/dp/$username.jpg' class='header-logo-admin'>";
                echo "<label for='header-username' id='admin-name'>$username</label>";
            }
            else
            {
                echo "<img src='img/avatar.png' class='header-logo-admin'>";
                echo "<label for='header-username' id='admin-name'>$username</label>";
            }
            ?>
            <a href="logout.php" class="logout-btn">LOGOUT</a>
            <div class="header-foot">
            </div>
        </div>
        <div class="management-panel">
            <label for="manage-head">User Management</label><br>
            <label for="manage-subtitle">NEXUS < PERMISSIONS AND ACCOUNTS < USER MANAGEMENT</label>
            <a href="signup.php" class="add-btn">ADD USER</a>
            <table>
                <thead>
                    <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>User Role</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q = "SELECT * FROM users";
                        $result = $conn->query($q);
                        while($row=$result->fetch_assoc())
                        {
                            $id = $row['id'];
                            $username = $row['username'];

                            if($row['isadmin']==0)
                            {
                                echo "<tr id='row-selector'>";
                                echo "<td>".$id."</td>";
                                echo "<td>".$username."</td>";
                                echo "<td><button class='admin-btn'>User</button></td>";
                                echo "<td><button class='admin-btn' data-username='$username' id='change-username'>Change Username</button><button class='admin-btn' data-username='$username' id='change-name'>Change Name</button><button class='admin-btn' data-username='$username' id='change-password'>Update Password</button><button class='admin-btn' data-username='$username' id='delete-account'>Delete Account</button></td>";
                                echo "</tr>";
                            }
                            else
                            {
                                echo "<tr id='row-selector'>";
                                echo "<td>".$id."</td>";
                                echo "<td>".$username."</td>";
                                echo "<td><button class='admin-btn' style='background:rgb(0, 79, 0);'>Admin</button></td>";
                                echo "<td><button class='admin-btn' data-username='$username' id='change-username'>Change Username</button><button class='admin-btn' data-username='$username' id='change-name'>Change Name</button><button class='admin-btn' data-username='$username' id='change-password'>Update Password</button><button class='admin-btn' data-username='$username' id='delete-account'>Delete Account</button></td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>

