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
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <?php require_once "../connection/connection.php" ?>
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../common/common.php"?>
    <?php require_once "../common/commonFunctions.php";?>
    <style>
        html{
            overflow-x: hidden;
        }
        article, .sun {
            bottom: 0;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: 0;
        }

        html {
            background: black;
            font-size: 10px;
        }


        article {
            border-radius: 50%;
            border: 0.2em solid rgba(255, 255, 255, 0.2);
            height: 30em;
            position: absolute;
            transition: 1s transform ease-in-out;
            width: 30em;
        }
        section:hover article:before {
            background: black;
            color: #ccc;
            position: absolute;
            left: 50%;
            margin-left: -2rem;
            text-align: center;
            top: -0.6rem;
            width: 4rem;
        }
        article div {
            border-radius: 50%;
            height: 100%;
            position: relative;
            width: 100%;
        }
        article div:after {
            border-radius: 50%;
            background: blue;
            box-shadow: inset 0 0.4rem 0.8rem rgba(0, 0, 0, 0.2), inset 0 -0.4rem 0.4rem rgba(255, 255, 255, 0.2);
            content: '';
            left: 50%;
            height: 3em;
            margin-left: -1.5em;
            position: absolute;
            top: -1.5em;
            width: 3em;
        }

        .saturn div:after {
            box-shadow: 0 0 0 0.1em #000, 0 0 0.1em 0.5em #8f6200, inset 0 0.4rem 0.8rem rgba(0, 0, 0, 0.2), inset 0 -0.4rem 0.4rem rgba(255, 255, 255, 0.2);
        }

        .mercury {
            height: 6rem;
            width: 6rem;
        }
        section:hover .mercury:before {
            content: "mercury";
        }
        .mercury div {
            animation: orbit 0.88s linear infinite;
        }
        .mercury div:after {
            background: #a1a1a1;
            height: 0.4878rem;
            margin-left: -0.2439rem;
            top: -0.2439rem;
            width: 0.4878rem;
        }

        .venus {
            height: 12rem;
            width: 12rem;
        }
        section:hover .venus:before {
            content: "venus";
        }
        .venus div {
            animation: orbit 2.25s linear infinite;
        }
        .venus div:after {
            background: #f5cc96;
            height: 1.2104rem;
            margin-left: -0.6052rem;
            top: -0.6052rem;
            width: 1.2104rem;
        }

        .earth {
            height: 18rem;
            width: 18rem;
        }
        section:hover .earth:before {
            content: "earth";
        }
        .earth div {
            animation: orbit 3.6525s linear infinite;
        }
        .earth div:after {
            background: #495391;
            height: 1.276rem;
            margin-left: -0.638rem;
            top: -0.638rem;
            width: 1.276rem;
        }

        .mars {
            height: 24rem;
            width: 24rem;
        }
        section:hover .mars:before {
            content: "mars";
        }
        .mars div {
            animation: orbit 6.87s linear infinite;
        }
        .mars div:after {
            background: #b95730;
            height: 0.6787rem;
            margin-left: -0.33935rem;
            top: -0.33935rem;
            width: 0.6787rem;
        }

        .jupiter {
            height: 30rem;
            width: 30rem;
        }
        section:hover .jupiter:before {
            content: "jupiter";
        }
        .jupiter div {
            animation: orbit 43.46475s linear infinite;
        }
        .jupiter div:after {
            background: #d5ba8e;
            height: 3.84rem;
            margin-left: -1.92rem;
            top: -1.92rem;
            width: 3.84rem;
        }

        .saturn {
            height: 36rem;
            width: 36rem;
        }
        section:hover .saturn:before {
            content: "saturn";
        }
        .saturn div {
            animation: orbit 107.74875s linear infinite;
        }
        .saturn div:after {
            background: #dab37a;
            height: 1.95rem;
            margin-left: -0.975rem;
            top: -0.975rem;
            width: 1.95rem;
        }

        .uranus {
            height: 42rem;
            width: 42rem;
        }
        section:hover .uranus:before {
            content: "uranus";
        }
        .uranus div {
            animation: orbit 306.81s linear infinite;
        }
        .uranus div:after {
            background: #c4eaed;
            height: 1.812rem;
            margin-left: -0.906rem;
            top: -0.906rem;
            width: 1.812rem;
        }

        .neptune {
            height: 48rem;
            width: 48rem;
        }
        section:hover .neptune:before {
            content: "neptune";
        }
        .neptune div {
            animation: orbit 602.6625s linear infinite;
        }
        .neptune div:after {
            background: #6393e5;
            height: 1.753rem;
            margin-left: -0.8765rem;
            top: -0.8765rem;
            width: 1.753rem;
        }

        .sun {
            background: yellow;
            border-radius: 50%;
            height: 3em;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8);
            transition: 1s transform ease-in-out;
            width: 3em;
        }

        .slider {
            margin-top: 100px;
            transform: rotate(90deg);
        }



        @keyframes orbit {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

    </style>
</head>
<body class="opening hide-UI view-2D zoom-large data-close controls-close">
<div class="hero">
    <nav class="nav  text-light ">
        <a class="navbar-brand brand-name mt-3 ml-4" href="../index.php"> Univard</a>
    </nav>
</div>

<section>
    <div class="sun"></div>

    <article class="mercury">
        <div></div>
    </article>
    <article class="venus">
        <div></div>
    </article>
    <article class="earth">
        <div></div>
    </article>
    <article class="mars">
        <div></div>
    </article>
    <article class="jupiter">
        <div></div>
    </article>
    <article class="saturn">
        <div></div>
    </article>
    <article class="uranus">
        <div></div>
    </article>
    <article class="neptune">
        <div></div>
    </article>

</section>


</body>

</html>
