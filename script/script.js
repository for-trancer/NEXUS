
    document.addEventListener("DOMContentLoaded", function() {

        const searchbar = document.getElementById('searchbar');
        const usersection = document.getElementById('users-section');
        const messageBox = document.getElementById('message-box');
        const messagebtn = document.getElementById('message-button-send');
        const deleteBtn = document.getElementById('delete-option');
        const translateBtn = document.getElementById('translate-option');
        const voiceRecordBtn = document.getElementById('voice-record');
        const voiceStopBtn = document.getElementById('voice-stop');
        const profileOpener = document.getElementById('profile-opener');
        const uploadBtn = document.getElementById('upload-option');
        const removeBtn = document.getElementById('remove-option');
        const attachBtn = document.getElementById('attachment-btn');
        const imageBtn = document.getElementById('image-btn');
        const themeBtn = document.getElementById('change-theme');
        const themeSelect = document.getElementById('theme-select');
        const fontSelect = document.getElementById('font-select');
        
        
        let mediaRecorder;
        let audioChunks = [];
        let timeLabel;
        let userData;
        let msgContent;
        let isStart = false;

        displayMessages();

        messagebtn.style.display = "none";
        document.getElementById('voice-stop').style.display = "none";

        $(document).ready(function(){
            $('#attachment').on('change', function(){
                const user = document.getElementById('receiver-username').textContent;
                const receiver = encodeURIComponent(user);
                var formData = new FormData();
                formData.append('attachment', $('#attachment')[0].files[0]);
                formData.append('file-upload', 'true');
                formData.append('receiver',receiver);
        
                $.ajax({
                    url: 'process_data.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        $('#attachment').val('');
                    }
                });
            });
            $('#image-uploader').on('change', function(){
                const user = document.getElementById('receiver-username').textContent;
                const receiver = encodeURIComponent(user);
                var formData = new FormData();
                formData.append('imagefile', $('#image-uploader')[0].files[0]);
                formData.append('image-upload', 'true');
                formData.append('receiver',receiver);
        
                $.ajax({
                    url: 'process_data.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        $('#image-uploader').val('');
                    }
                });
            });
        });
        
        window.onload = function() {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById("profile-container-navbar").innerHTML = this.responseText;
                    } else {
                    }
                }
            };
            xhr.open("GET","process_data.php?profile-navbar=true",true);
            xhr.send();
        };

        searchbar.addEventListener("focus", function(){
            this.value = "";
        });

        messageBox.addEventListener("keypress",function(event){
            if(event.keyCode === 13)
            {
                event.preventDefault();
                var msg = messageBox.value; 
                var user = document.getElementById('receiver-username').textContent;
                if(user==""){
                // do nothing
                }
                else{
                    if(msg == "" || msg =="Message" || msg=="Type a message"){
                        messageBox.value = "Type a message";
                    }
                    else
                    {
                        xhr = new XMLHttpRequest();
                        xhr.open("GET","process_data.php?msg="+encodeURIComponent(msg)+"&user="+encodeURIComponent(user),true);
                        xhr.send(); 
                        if (document.getElementById('message-screen').innerHTML.trim() === '') {
                            fetchMessages(user);
                            updateMessages();
                        }
                        fetchMessages(user);
                        updateMessages();
                        document.getElementById('voice-record').style.display = "block";
                        messagebtn.style.display = "none";
                        messageBox.value = "";
                    }
                }
            }
        });

        messageBox.addEventListener("focus",function(){
            this.value = "";
        });

        messageBox.addEventListener("input",function(){
            if(messageBox.value.trim()==""||messageBox.value.trim()=="Message"||messageBox.value.trim()=="Typeamessage")
            {
                document.getElementById('voice-record').style.display = "block";
                messagebtn.style.display = "none";
            }
            else
            {
                document.getElementById('voice-record').style.display = "none";
                messagebtn.style.display = "block";
            }
        });

        voiceRecordBtn.addEventListener("click", function(){
            navigator.mediaDevices.getUserMedia({ audio:true })
            .then(stream => {
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.addEventListener("dataavailable", event => {
                audioChunks.push(event.data);
            });

            mediaRecorder.addEventListener("stop", () => {
                const audioBlob = new Blob(audioChunks, {type: 'audio/webm'});
                saveAudio(audioBlob);
            });

            mediaRecorder.start();
            document.getElementById('voice-stop').style.display = "block";
            document.getElementById('voice-record').style.display = "none";
            })
            .catch(error => {
            console.error('Error accessing media devices:', error);
            });
        });

        voiceStopBtn.addEventListener("click", function(){
                mediaRecorder.stop();
                document.getElementById('voice-stop').style.display = "none";
                    document.getElementById('voice-record').style.display = "block";
        });

        searchbar.addEventListener("input",function() {
            const xhr = new XMLHttpRequest();
            const query = this.value.trim();
            if(query !== '')
            {
                xhr.onreadystatechange = function(){
                if(xhr.readyState == 4 && xhr.status == 200)
                {
                    usersection.innerHTML = this.responseText;
                }
                };
                xhr.open("GET","process_data.php?q="+encodeURIComponent(query),true);
                xhr.send();
            }
            else
            {
                displayMessages();
            }
        });

        document.getElementById('changefid').addEventListener("click", function() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "process_data.php?changefid=true", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        window.location.href = xhr.responseText;
                        console.log(xhr.responseText)
                    } else {
                        console.error("Error occurred while processing data:", xhr.status);
                    }
                }
            };
            xhr.send();
        });

        document.getElementById("logout").addEventListener("click",function(){
            window.location.href="logout.php";
        });
        

        messagebtn.addEventListener('click',function() {
            var msg = messageBox.value; 
            var user = document.getElementById('receiver-username').textContent;
            if(user==""){
                // do nothing
            }
            else{
                if(msg == "" || msg =="Message" || msg=="Type a message"){
                    messageBox.value = "Type a message";
                }
                else
                {
                    xhr = new XMLHttpRequest();
                    xhr.open("GET","process_data.php?msg="+encodeURIComponent(msg)+"&user="+encodeURIComponent(user),true);
                    xhr.send(); 
                    if (document.getElementById('message-screen').innerHTML.trim() === '') {
                        fetchMessages(user);
                        updateMessages();
                    }
                    fetchMessages(user);
                    updateMessages();
                    document.getElementById('voice-record').style.display = "block";
                    messagebtn.style.display = "none";
                    messageBox.value = "";
                }
            }
        });

        themeBtn.addEventListener('click',function(){
            event.stopPropagation();
            document.getElementById('theme-section').classList.remove("hidden");
        });

        themeSelect.addEventListener('change',function(){
            var Selected = this.value;
            document.getElementById('css-link').setAttribute('href',Selected);
        });

        fontSelect.addEventListener('change',function(){
            var Selected = this.value;
            document.getElementById('font-link').setAttribute('href',Selected);
        });

        document.addEventListener('contextmenu', function(event) {
            event.preventDefault(); 
            const clickedDiv = event.target.closest('#message-holder,#message-holder-user');
            const isDPClick = event.target.closest('#dp-picture');
            const isAudioClick = event.target.closest('#message-holder #audio-container,#message-holder-user #audio-container');
            const isImageClick = event.target.closest('#message-holder .image-file-container,#message-holder-user .image-file-container');
            const isFileClick = event.target.closest('#message-holder .file-container,#message-holder-user .file-container');
            const isAudioClickUser = event.target.closest('#message-holder-user #audio-container');
            const isImageClickUser = event.target.closest('#message-holder-user .image-file-container');
            const isFileClickUser = event.target.closest('#message-holder-user .file-container');
            if (clickedDiv || isAudioClick) { 
                const contentMenu = document.getElementById('content-menu');
                timeLabel = clickedDiv.querySelector('#msg-time-org').textContent;
                const messageContent = clickedDiv.querySelector('#message-content');
                msgContent = messageContent && messageContent.textContent;
                if (clickedDiv.querySelector('#users-main-holder')) {
                    userData = "You";
                } else {
                    userData = clickedDiv.querySelector('#receiver-main-holder').textContent;
                }
                const contentMenuWidth = contentMenu.offsetWidth;
                const contentMenuHeight = contentMenu.offsetHeight;
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;
                let menuLeft = event.pageX;
                let menuTop = event.pageY;
                // Adjust menu position if it goes beyond viewport bounds
                if (menuLeft + contentMenuWidth > viewportWidth) {
                    menuLeft = viewportWidth - contentMenuWidth;
                }
                if (menuTop + contentMenuHeight > viewportHeight) {
                    menuTop = viewportHeight - contentMenuHeight;
                }
                contentMenu.style.top = menuTop + 'px';
                contentMenu.style.left = menuLeft + 'px';
                if(clickedDiv) {
                    document.getElementById("content-menu").classList.remove("hidden");
                    if(userData == "You") {
                        document.getElementById('translate-option').style.display = "none";
                        document.getElementById('delete-option').style.display = "block";
                        if(isAudioClick || isImageClick || isFileClick) {
                            document.getElementById('translate-option').style.display = "none";
                        }
                    } else {
                        document.getElementById('delete-option').style.display = "none";
                        document.getElementById('translate-option').style.display = "block";
                        if(isAudioClickUser || isImageClickUser || isFileClickUser) {
                            document.getElementById("content-menu").classList.add("hidden");
                            document.getElementById('translate-option').style.display = "none";
                        }
                    }
                }
            }
        
            if (isDPClick) {
                attachDpContent();
            }
        });
        


        document.addEventListener('click', function(event) {
            const contentMenu = document.getElementById('content-menu');
            const dpContent = document.getElementById('dp-content');
            const isClickInsideDPMenu = event.target.closest('#dp-content');
            const isClickInsideContentMenu = event.target.closest('#content-menu');
            const profileSection = document.getElementById('profile-section');
            const isClickInsideProfileSection = event.target.closest('#profile-section');
            const themeSection = document.getElementById("theme-section");
            const isClickInsideThemeSection = event.target.closest('#theme-section');

            if (!isClickInsideProfileSection) {
                dpContent.classList.add("hidden");
                profileSection.classList.add('hidden');
            }

            if (!isClickInsideThemeSection) {
                themeSection.classList.add("hidden");
            }

            if (!isClickInsideContentMenu) {
                contentMenu.classList.add('hidden');
            }

            if(!isClickInsideDPMenu)
            {
                dpContent.classList.add("hidden");
            }
            var user = document.getElementById('receiver-username').textContent;
            fetchMessages(user);
            updateMessages();
        });

        deleteBtn.addEventListener('click',function(){
            var user = document.getElementById('receiver-username').textContent;
            const contentMenu = document.getElementById('content-menu');
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    fetchMessages(user);
                    updateMessages();
                    contentMenu.classList.add('hidden');
                } else {
                    console.error("Failed to delete the message");
                }
            }
            };
            xhr.open("POST", "process_data.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("delete_option=yes&time=" + encodeURIComponent(timeLabel) + "&userData=" + encodeURIComponent(userData));
        });

        translateBtn.addEventListener('click',function(){
            var user = document.getElementById('receiver-username').textContent;
            const contentMenu = document.getElementById('content-menu');
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status ==200) {
                    fetchMessages(user);
                    updateMessages();
                    contentMenu.classList.add('hidden');
                }
            };
            xhr.open("POST", "process_data.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("translate_option=yes&time=" + encodeURIComponent(timeLabel) + "&userData=" + encodeURIComponent(userData) + "&translateMsg="+encodeURIComponent(msgContent));
        });

        profileOpener.addEventListener("click",function(event){
            event.stopPropagation();
            document.getElementById('profile-section').classList.remove("hidden");
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 || xhr.status == 200)
            {
                document.getElementById('profile-display').innerHTML = this.responseText;
            }
            }
            xhr.open("GET","process_data.php?displayprofile=yes",true);
            xhr.send();
        });

        uploadBtn.addEventListener('click',function(){
            document.getElementById('profile-picture').click();
        });

        removeBtn.addEventListener('click',function(){
            const xhr = new XMLHttpRequest();
            var user = document.getElementById('receiver-username').textContent;
            xhr.onreadystatechange = function(){
                if(xhr.readyState == 4 && xhr.status == 200 )
                {
                    fetchMessages(user);
                    updateMessages();
                }
            };
            xhr.open("GET","process_data.php?removeOption=true",true);
            xhr.send();
        });

        document.getElementById('attachment-btn').addEventListener('click', function() {
            document.getElementById('attachment').click();
        });

        imageBtn.addEventListener('click',function(){
            document.getElementById('image-uploader').click();
        });

        setInterval(function() {
            var user = document.getElementById('receiver-username').textContent;
            if(user!="")
            {
                fetchMessages(user);
                updateMessages();
            }
        }, 10000); 

    });

    function attachDpContent()
    {
        const dp = document.getElementById('dp-picture');
        const dpContent = document.getElementById('dp-content');
        dpContent.classList.remove("hidden");
        dpContent.style.top = event.pageY + 'px';
        dpContent.style.left = event.pageX + 'px';
    }

    function messageSectionOpener(element)
    {
        const startUp = document.getElementById('start-up-cover');
        var user = element.getAttribute('data-username');
        var name;
        receiver = user;
        document.getElementById('receiver-username').innerHTML = user;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if(xhr.readyState == 4 || xhr.status == 200)
            {
                name = this.responseText;
                document.getElementById('receiver-name').innerHTML = name;
                triggerMessageOptions();
                fetchMessages(user);
                updateMessages();
            }
        }
        xhr.open("GET","process_data.php?getname="+encodeURIComponent(user),true);
        xhr.send();
        startUp.style.display = "none";
    }

    function triggerMessageOptions()
    {
        document.getElementById('message-footer').style.display = "block";
    }

    function fetchMessages(user)
    {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if(xhr.readyState == 4 || xhr.status == 200)
            {
                document.getElementById('message-screen').innerHTML = this.responseText;
            }
        };
        xhr.open("GET","process_data.php?usr=" + encodeURIComponent(user),true);
        xhr.setRequestHeader('Cache-Control', 'no-cache');
        xhr.send();
    }

    function displayMessages()
    {
        document.getElementById('message-footer').style.display = "none";
        const req = new XMLHttpRequest();
        req.onreadystatechange = function(){
            if(req.readyState == 4 || req.status == 200)
            {
                document.getElementById('users-section').innerHTML = this.responseText;
            }
        }
        req.open("GET","process_data.php?action=getmsg",true);
        req.send();
    }

    function updateMessages()
    {
        const req = new XMLHttpRequest();
        req.onreadystatechange = function(){
            if(req.readyState == 4 || req.status == 200)
            {
                document.getElementById('users-section').innerHTML = this.responseText;
            }
        }
        req.open("GET","process_data.php?action=getmsg",true);
        req.send();
    }

    function saveAudio(blob) {
        const formData = new FormData();
        formData.append('audio', blob, 'audio.webm');
        const receiverName = document.getElementById('receiver-username').textContent;
        formData.append('receiver', receiverName);
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    fetchMessages(receiverName);
                    updateMessages();
                } else {
                    console.error("Failed to upload audio");
                }
            }
        };

    xhr.open("POST", "process_data.php", true);
    xhr.send(formData);
    }

    function handleFileUpload(files) {
        const file = files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    const maxSize = 1000; 
                    const aspectRatio = 1; 

                    let width = this.width;
                    let height = this.height;
                    let offsetX = 0;
                    let offsetY = 0;

                    if (width > height) {
                        offsetX = (width - height) / 2; 
                        width = height;
                    } else {
                        offsetY = (height - width) / 2;
                        height = width; 
                    }

                    canvas.width = aspectRatio * maxSize;
                    canvas.height = maxSize;
                    ctx.drawImage(this, offsetX, offsetY, width, height, 0, 0, canvas.width, canvas.height);

                    const dataURL = canvas.toDataURL("image/jpeg");
                    uploadProfilePicture(dataURL);
                };
            };
            reader.readAsDataURL(file);
        }
    }



    function uploadProfilePicture(dataURL) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // update display and all
                } else {
                    // else
                }
            }
        };

        xhr.open('POST', 'process_data.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('imageData=' + encodeURIComponent(dataURL));
    }

    function scrollToBottom() {
        var messageScreen = document.getElementById('message-screen');
        messageScreen.scrollTop = messageScreen.scrollHeight;
    }


