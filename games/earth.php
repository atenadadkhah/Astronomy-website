<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UniVard | سرگرمی ها</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="../css/game/baseMain.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/100/three.min.js"></script>
    <script src="https://klevron.github.io/codepen/three.js/OrbitControls.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.0.3/chroma.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplex-noise/2.4.0/simplex-noise.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js"></script>
    <?php require_once "../connection/connection.php" ?>
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../common/common.php"?>
    <?php require_once "../common/commonFunctions.php";?>
    <style>
        body { background-color: #111; }
        #earth {
            background-image: url('https://encrypted-tbn3.google.com/images?q=tbn:ANd9GcTQqoB4xBnIkmUg5pxDyYW2h0Q1pRTsbQBOfhg-E-y4iLFicsyi');
            width: 70px;
            height: 70px;
            /* a black shadow from left and white from right */
            box-shadow: inset 16px 0 40px 3px rgba(0,0,0,0.9),
            inset -3px 0 5px 2px rgba(255,255,255,0.16);
            background-size: 190px;
            margin: 80px auto;
            border-radius: 50%;
            position: relative;
            animation-name: move,scale,rotate;
            animation-duration: 4s,4s,4s;
            animation-iteration-count: infinite,infinite,infinite;
            animation-timing-function: ease-in-out,linear,linear;
        }

        @keyframes move {
            0%   { left: 200px;  }
            70%  { left: -200px; }
            100% { left: 200px;  }
        }

        @keyframes scale {
            0%  { transform: scale(1);   }
            32% { transform: scale(0.4); animation-timing-function:  ease-in; }
            70% { transform: scale(1); animation-timing-function:  ease-in;  }
            75% { transform: scale(1.2);  animation-timing-function:  ease-in-out; }
            86% { transform: scale(2);  }
            98% { transform: scale(1.2); }
            100%{ transform: scale(1); }
        }

        @keyframes rotate {
            0% { background-position: 0px; }
            100% { background-position: 190px; }
        }
    </style>
</head>
<body class="opening hide-UI view-2D zoom-large data-close controls-close">
<div class="hero">
    <nav class="nav  text-light ">
        <a class="navbar-brand brand-name mt-3 ml-4" href="../index.php"> Univard</a>
    </nav>
</div>
<div id="earth"></div>
</body>
</html>



