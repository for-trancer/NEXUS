<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        body {
            background: url(../img/bg.jpg);
            background-size:cover;
            background-position:center;
            height:100vh;
            overflow:hidden;
            animation: body .2s infinite ease-in;
  }
  
  
        * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }

        .container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        background: var(--primary-color);
        height: 100vh;
        width: 100vw;
        animation-delay: 1.6s;
        animation-fill-mode: forwards;
        }

        .title {
        font-size: 81px;
        color: #fff;
        text-align: center;
        font-family: "Poppins", sans-serif;
        animation: wave 0.4s, jump 1s;
        position: relative;
        top: 0;
        padding: 4px;
        transform: translate3d(0, 16%, 0);
        opacity: 0;
        z-index: 3;
        animation-fill-mode: forwards;
        }

        .logo {
        animation: wave 0.4s, jump 1s ease-in alternate 0.05s;
        transition: transform 0.4s ease-out;
        }

        @keyframes wave {
        0% {
            top: 0%;
        }
        100% {
            top: 100%;
        }
        }

        @keyframes jump {
        0% {
            transform: translate3d(0, 0, 0);
            opacity: 0;
        }
        90% {
            transform: translate3d(0, -16%, 0);
            opacity: 1;
        }
        100% {
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
        }

        @keyframes appear {
        0% {
            visibility: hidden;
        }
        100% {
            visibility: visible;
        }
        }

    </style>
</head>
<body>
    <script>
        setTimeout(() => {
            window.location.href = "signup.php";
        }, 1700);
    </script>
    <div class="container">
        <img src="img/nexus-logo.png" class="logo">
    </div>
</body>
</html>
