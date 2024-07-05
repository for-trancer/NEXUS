<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS | SignUp</title>
    <link rel="stylesheet" href="style/face.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.24/webcam.js"></script>
</head>
<body>
    <?php
        include 'sql.php';

        $username = urlencode($_GET['username']);
        $fileLocation = "uploads/faceid/" . $username . ".png";
        $query = "UPDATE users set faceid='$fileLocation' WHERE username='$username';";
        $result = mysqli_query($conn , $query);
    ?>
    <div class="container">
        <img src="img/nexus-logo.png" class="logo"> 
        <label for="faceid">Add Face ID</label>
        <div id="webcapture">
        </div>
        <input type="hidden" name="captured_image_data" id="captured_image_data">
        <input type="button" value="Capture" id="capturebtn" class="capture-btn">
        <br>
        <div id="results">
        </div>
        <br>
        <input type="button" value="Set Face Id" id="savebtn" style="display: none" class="submit-btn"> 
        </div>
    <script language="JavaScript">
        var username;

        Webcam.set({
            width:1280,
            height:720,
            image_format:'png',
            jpeg_quality:90
        }
        );
        
        Webcam.attach('#webcapture');
        function captureFrame(){
            Webcam.snap(function(data_uri){
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
                $('#captured_image_data').val(data_uri);
                $('#savebtn').show();
            });
        }
        
        function saveFrame() {
            var base64data = $("#captured_image_data").val();
            var username = "<?php echo $_GET['username'];?>";
            
            $.ajax({
                type: "POST",
                dataType: "text",
                url: "saveimage.php",
                data: { image: base64data, username: username },
                success: function(response) {
                    if (response.trim() === "true") {
                        alert("Face Id Saved Sucessfully!");
                        window.location.href="login.php";
                    } else {
                        alert(response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error: " + error);
                }
            });
        }

    
        document.getElementById('capturebtn').addEventListener("click",function(){
            captureFrame();
        });
        document.getElementById('savebtn').addEventListener("click",function(){
            saveFrame();
        });
    </script>
</body>
</html>