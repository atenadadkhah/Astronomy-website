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
        /**
 * Keyframes.
 */
        @-webkit-keyframes rotation {
            0% {
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes rotation {
            0% {
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        /**
         * Begin styling.
         */
        body {
            background-color: #000;
        }

        #solar-wrapper {
            height: 100%;
            overflow: hidden;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        #sun {
            background-color: #aa0;
            height: 50px;
            left: calc(50% - 25px);
            position: absolute;
            top: calc(50% - 25px);
            width: 50px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: 0 0 10px 2px #a50;
            -webkit-box-shadow: 0 0 10px 2px #a50;
            box-shadow: 0 0 10px 2px #a50;
        }

        #mercury-orbit {
            -webkit-animation: rotation 8s linear infinite;
            animation: rotation 8s linear infinite;
            height: 10%;
            left: 45%;
            position: absolute;
            top: 45%;
            width: 10%;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        #mercury {
            background: #953;
            height: 10px;
            width: 10px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: inset 0 0 5px 1px #222;
            -webkit-box-shadow: inset 0 0 5px 1px #222;
            box-shadow: inset 0 0 5px 1px #222;
        }

        #venus-orbit {
            -webkit-animation: rotation 16s linear infinite;
            animation: rotation 16s linear infinite;
            height: 14%;
            left: 43%;
            position: absolute;
            top: 43%;
            width: 14%;
        }

        #venus {
            background: #db5;
            height: 15px;
            width: 15px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: 0 0 3px 0 #ff8;
            -webkit-box-shadow: 0 0 3px 0 #ff8;
            box-shadow: 0 0 3px 0 #ff8;
        }

        #earth-orbit {
            -webkit-animation: rotation 24s linear infinite;
            animation: rotation 24s linear infinite;
            height: 20%;
            left: 40%;
            position: absolute;
            top: 40%;
            width: 20%;
        }

        #earth {
            background-color: #0bf;
            height: 20px;
            width: 20px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: 0 0 4px 1px #fff;
            -webkit-box-shadow: 0 0 4px 1px #fff;
            box-shadow: 0 0 4px 1px #fff;
        }

        #moon-orbit {
            -webkit-animation: rotation 6s linear infinite;
            animation: rotation 6s linear infinite;
            height: 30px;
            left: -5px;
            position: absolute;
            top: -5px;
            width: 30px;
        }

        #moon {
            background-color: #aaa;
            height: 5px;
            width: 5px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        #mars-orbit {
            -webkit-animation: rotation 44s linear infinite;
            animation: rotation 44s linear infinite;
            height: 30%;
            left: 35%;
            position: absolute;
            top: 35%;
            width: 30%;
        }

        #mars {
            background-color: #833;
            height: 18px;
            width: 18px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: inset 0 0 5px #200;
            -webkit-box-shadow: inset 0 0 5px #200;
            box-shadow: inset 0 0 5px #200;
        }

        .mars-moon-orbit-1 {
            -webkit-animation: rotation 5s linear infinite;
            animation: rotation 5s linear infinite;
            height: 40px;
            left: -3px;
            position: absolute;
            top: -3px;
            width: 40px;
        }

        .mars-moon-orbit-2 {
            -webkit-animation: rotation 4s linear infinite;
            animation: rotation 4s linear infinite;
            height: 30px;
            left: -10px;
            position: absolute;
            top: -10px;
            width: 30px;
        }

        .mars-moon {
            background-color: #dbc;
            height: 3px;
            width: 3px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        #jupiter-orbit {
            -webkit-animation: rotation 68s linear infinite;
            animation: rotation 68s linear infinite;
            height: 40%;
            left: 30%;
            position: absolute;
            top: 30%;
            width: 40%;
        }

        #jupiter {
            background-color: #ffa500;
            height: 40px;
            width: 40px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: inset 0 0 10px 1px #000;
            -webkit-box-shadow: inset 0 0 10px 1px #000;
            box-shadow: inset 0 0 10px 1px #000;
        }

        #jupiter-spot {
            background-color: rgba(160, 0, 0, 0.5);
            height: 6px;
            left: 20px;
            position: absolute;
            top: 22px;
            width: 12px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: 0 0 5px 0 #900;
            -webkit-box-shadow: 0 0 5px 0 #900;
            box-shadow: 0 0 5px 0 #900;
        }

        #saturn-orbit {
            -webkit-animation: rotation 80s linear infinite;
            animation: rotation 80s linear infinite;
            height: 48%;
            left: 26%;
            position: absolute;
            top: 26%;
            width: 48%;
        }

        #saturn {
            background-color: #ab5;
            height: 36px;
            width: 36px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: inset 0 0 5px #451;
            -webkit-box-shadow: inset 0 0 5px #451;
            box-shadow: inset 0 0 5px #451;
        }

        #ring {
            height: 10px;
            left: -10px;
            position: absolute;
            top: 10px;
            width: 55px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: 0px 2px 0 4px #ab5, 0 7px 2px 1px #000;
            -webkit-box-shadow: 0px 2px 0 4px #ab5, 0 7px 2px 1px #000;
            box-shadow: 0px 2px 0 4px #ab5, 0 7px 2px 1px #000;
        }

        #uranus-orbit {
            -webkit-animation: rotation 100s linear infinite;
            animation: rotation 100s linear infinite;
            height: 60%;
            left: 20%;
            position: absolute;
            top: 20%;
            width: 60%;
        }

        #uranus {
            background-color: #3da;
            height: 30px;
            width: 30px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: inset 0 0 5px #451;
            -webkit-box-shadow: inset 0 0 5px #451;
            box-shadow: inset 0 0 5px #451;
        }

        #neptune-orbit {
            -webkit-animation: rotation 120s linear infinite;
            animation: rotation 120s linear infinite;
            height: 70%;
            left: 15%;
            position: absolute;
            top: 15%;
            width: 70%;
        }

        #neptune {
            background-color: #05a;
            height: 32px;
            width: 32px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            -moz-box-shadow: inset 0 0 10px 1px #000;
            -webkit-box-shadow: inset 0 0 10px 1px #000;
            box-shadow: inset 0 0 10px 1px #000;
        }

    </style>
</head>
<body class="opening hide-UI view-2D zoom-large data-close controls-close">
<div class="hero">
    <nav class="nav  text-light ">
        <a class="navbar-brand brand-name mt-3 ml-4" href="../index.php"> Univard</a>
    </nav>
</div>
<div id="solar-wrapper">
    <div id="sun"></div>
    <div id="mercury-orbit">
        <div id="mercury"></div>
    </div>
    <div id="venus-orbit">
        <div id="venus"></div>
    </div>
    <div id="earth-orbit">
        <div id="earth"></div>
        <div id="moon-orbit">
            <div id="moon"></div>
        </div>
    </div>
    <div id="mars-orbit">
        <div id="mars"></div>
        <div class="mars-moon-orbit-1">
            <div class="mars-moon"></div>
        </div>
        <div class="mars-moon-orbit-2">
            <div class="mars-moon"></div>
        </div>
    </div>
    <div id="jupiter-orbit">
        <div id="jupiter"></div>
        <div id="jupiter-spot"></div>
    </div>
    <div id="saturn-orbit">
        <div id="saturn">
            <div id="ring"></div>
        </div>
    </div>
    <div id="uranus-orbit">
        <div id="uranus"></div>
    </div>
    <div id="neptune-orbit">
        <div id="neptune"></div>
    </div>
</div>

</body>
</html>


