<?php
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<head>
	<meta encodeing="UTF-8">
    <title>NEXUS | <?php $user=$_SESSION['user'];echo $user?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css" id="css-link">
    <link rel="stylesheet" id="font-link">
    <script src="script/script.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="nav-bar">
        <img src="img/icons/nexus-logo.png" class="logo" />
        <div class="icon-container icon-container-message">
            <img src="img/icons/chatting.png" class="nav-bar-icons message-icon" />
        </div>
        <div class="icon-container icon-container-theme" id="change-theme">
            <img src="img/icons/theme.png" class="nav-bar-icons message-icon" />
        </div>
        <div class="icon-container icon-container-fid" id="changefid">
            <img src="img/icons/faceid.png" class="nav-bar-icons message-icon" />
        </div>
        <div class="icon-container icon-container-logout">
            <img src="img/icons/logout.png" class="nav-bar-icons message-icon" id="logout"/>
        </div>
        <div class="icon-container icon-container-profile" id="profile-opener">
            <div id="profile-container-navbar">
            </div>
        </div>
    </div>
    <label for="app-name">NEXUS</label>
    <div id="chat-bar">
        <div class="chat-sidebar">
            <label for="chat">Chats</label>
            <div class="search-bar">
                <img src="img/icons/search.png" class="search-icon"/>
                <input type="text" id="searchbar" value="Search or start a new chat">
            </div>
            <div id="users-section">
            </div>
        </div>
        <div id="message-space">
            <div id="message-header">
                <div class="receiver-details-container">
                    <label for="receiver-name" id="receiver-name"></label>
                    <label for="receiver" id="receiver-username"></label>
                </div>
            </div>  
            <div id="message-screen">
            </div>
            <div id="message-footer">
                <div class="message-footer-icon-container message-footer-icon-container-attachment" id="attachment-btn">
                    <img src="img/icons/attachment.png" class="message-footer-icon">
                </div>
                <div class="message-footer-icon-container message-footer-icon-container-image" id="image-btn">
                    <img src="img/icons/image.png" class="message-footer-icon">
                </div>
                <input type="text" id="message-box" value="Message">
                <div class="message-footer-icon-container message-footer-icon-container-send" id="message-button-send">
                    <img src="img/icons/send.png" class="message-footer-icon">
                </div>
                <div class="message-footer-icon-container message-footer-icon-container-voice" id="voice-record">
                    <img src="img/icons/voice.png" class="message-footer-icon">
                </div>
                <div class="message-footer-icon-container message-footer-icon-container-voicestop" id="voice-stop">
                    <img src="img/icons/voice-stop.png" class="message-footer-icon">
                </div>
                <div class="hidden">
                    <form id="file-upload" enctype="multipart/form-data">
                        <input type="file" id="attachment" name="attachment" accept="*">
                        <input type="file" id="image-uploader" name="image-uploader" accept="/image">
                    </form>
                </div> 
            </div>
        </div>
        <div id="content-menu" class="hidden">
            <div class="content-option" id="delete-option">Delete</div>
            <div class="content-option" id="translate-option">Translate</div>
        </div>
        <div id="profile-section" class="hidden">
            <div id="profile-display">
            </div>
        </div>
        <div id="theme-section" class="hidden">
            <label for="theme-title">Customization</label>
            <div class="select-option">
                <label for="select-title">Select Your Theme</label>
                <select id='theme-select'>
                    <option value="style/style.css">Default</option>
                    <option value="style/themes/radiance.css">Radiance-Theme</option>
                    <option value="style/themes/citrus.css">Citrus-Theme</option>
                </select>
            </div>
            <div class="select-option">
                <label for="select-title">Select Your Font</label>
                <select id='font-select'>
                    <option value="style/fonts/poppins.css">Default</option>
                    <option value="style/fonts/montserrat.css">Montserrat</option>
                    <option value="style/fonts/lora.css">Lora</option>
                </select>
            </div>
        </div>
        <div id="dp-content" class="hidden">
                <div class="content-option" id="upload-option">Upload</div>
                <div class="content-option" id="remove-option">Remove</div>
        </div>
        <input type="file" accept="image/*" id="profile-picture" onchange="handleFileUpload(this.files)" class="hidden">
        <div id="start-up-cover">
            <img src="img/nexus-logo.png" class="startup-logo">
            <label for="startup-title">NEXUS</label>
        </div>
    </div>
</body>
</html>