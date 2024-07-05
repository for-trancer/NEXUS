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
    ?>
    <div class="container">
        <img src="img/nexus-logo.png" class="logo"> 
        <label for="faceid">Authorize Face</label>
        <div class="web-container">
            <div id="webcapture">
            </div>
        </div>
        <input type="hidden" name="captured_image_data" id="captured_image_data">
        <br>
        <div id="results" style="display:none">
        </div>
        <br>
        <input type="text" id="username-field" value="Enter Username">
        <br>
        <input type="button" value="Capture" id="capturebtn"  class="capture-btn">
        <input type="button" value="Retake" id="retakebtn" style="display: none" class="retake-btn" >
        <input type="button" value="Authorize" id="savebtn" style="display: none" class="submit-btn"> 
    </div>
    <script language="JavaScript">
        var username;
        const user = document.getElementById('username-field');
        const rbtn = document.getElementById('retakebtn');
        const result = document.getElementById('results');
        user.style.display =" none";

        document.addEventListener('DOMContentLoaded',function(){
            const capturebtn =document.getElementById('capturebtn');
            capturebtn.addEventListener('click',function(){
                document.getElementById('webcapture').style.display = "none";
                user.style.display=" block";
                rbtn.style.display=" block";
                capturebtn.style.display=" none";
            });
            rbtn.addEventListener('click',function(){
                window.location.href="faceidcheck.php";
            });
            user.addEventListener('focus',function(){
                this.value="";
            });
        });

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
                result.style.display=" block";
            });
        }
        
        function saveFrame(){
            
            var base64data = $("#captured_image_data").val();
            var username = "test";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "saveimage.php",
                data: {
                        image: base64data,
                        username: username,
                        saveTestImage: "true"
                    },
                });
        }
    
        document.getElementById('capturebtn').addEventListener("click",function(){
            captureFrame();
        });
        document.getElementById('savebtn').addEventListener("click",function(){
            saveFrame();
            username = user.value;
            xhr = new XMLHttpRequest();
            xhr.open("GET","process_data.php?checkfaceid=true&&username="+encodeURIComponent(username),false);
            xhr.send();
            if(xhr.status === 200)
            {
                if(xhr.responseText === "false")
                {
                    window.location.href=("faceidcheck.php");
                    alert("Authorization Failed");
                }
                else if(xhr.responseText === "true")
                {
                    window.location.href=("main.php");
                }
                else
                {
                    alert(xhr.responseText);
                    window.location.href=("faceidcheck.php");
                }
            }
        });
    </script>
</body>
</html>