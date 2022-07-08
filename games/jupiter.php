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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r120/three.min.js"></script>
    <?php require_once "../connection/connection.php" ?>
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../common/common.php"?>
    <?php require_once "../common/commonFunctions.php";?>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            position: relative;
            width: 100vw;
            height: 100vh;
            background-image: url("https://user-images.githubusercontent.com/26748614/96337412-581f2e80-1087-11eb-90f3-f033a8a1b41e.jpg");
            background-size: cover;
        }

        canvas {
            display: block;
        }

        #canvas_container {
            width: 100%;
            height: 100vh;
        }

        button {
            position: absolute;
            bottom: 5%;
            left: 50%;
            transform: translateX(-50%);
            border: 1px solid white;
            border-radius: 5px;
            font-size: 0.9rem;
            padding: 0.5rem 0.9em;
            background: #000000;
            color: white;
            -webkit-font-smoothing: antialiased;
            font-weight: bold;
            cursor: pointer;
            transition: all .5s;
        }

        button:hover {
            background: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body class="opening hide-UI view-2D zoom-large data-close controls-close">
<div class="hero">
    <nav class="nav  text-light ">
        <a class="navbar-brand brand-name mt-3 ml-4" href="../index.php"> Univard</a>
    </nav>
</div>
<div id="canvas_container"></div>
<button id="fullscr">تمام صفحه</button>
</body>
<script>
    let renderer,
        camera,
        planet,
        moon,
        sphereBg,
        terrainGeometry,
        container = document.getElementById("canvas_container"),
        timeout_Debounce,
        frame = 0,
        cameraDx = 0.05,
        count = 0,
        t = 0;

    /*   Lines values  */
    let lineTotal = 1000;
    let linesGeometry = new THREE.BufferGeometry();
    linesGeometry.setAttribute("position", new THREE.BufferAttribute(new Float32Array(6 * lineTotal), 3));
    linesGeometry.setAttribute("velocity", new THREE.BufferAttribute(new Float32Array(2 * lineTotal), 1));
    let l_positionAttr = linesGeometry.getAttribute("position");
    let l_vertex_Array = linesGeometry.getAttribute("position").array;
    let l_velocity_Array = linesGeometry.getAttribute("velocity").array;


    init();
    animate();


    function init() {
        scene = new THREE.Scene();
        scene.background = new THREE.Color("#000000");
        scene.fog = new THREE.Fog("#3c1e02", 0.5, 50);

        camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.01, 1000)
        camera.position.set(0, 1, 32)

        pointLight1 = new THREE.PointLight("#ffffff", 1, 0);
        pointLight1.position.set(0, 30, 30);
        scene.add(pointLight1);

        renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        const loader = new THREE.TextureLoader();

        // Planet
        const texturePlanet = loader.load('https://i.ibb.co/h94JBXy/saturn3-ljge5g.jpg');
        texturePlanet.anisotropy = 16;
        const planetGeometry = new THREE.SphereBufferGeometry(10, 50, 50);
        const planetMaterial = new THREE.MeshLambertMaterial({
            map: texturePlanet,
            fog: false
        });
        planet = new THREE.Mesh(planetGeometry, planetMaterial);
        planet.position.set(0, 8, -30);
        scene.add(planet);


        //Moon
        const textureMoon = loader.load('https://i.ibb.co/64zn361/moon-ndengb.jpg');
        textureMoon.anisotropy = 16;
        let moonGeometry = new THREE.SphereBufferGeometry(2, 32, 32);
        let moonMaterial = new THREE.MeshPhongMaterial({
            map: textureMoon,
            fog: false
        });
        moon = new THREE.Mesh(moonGeometry, moonMaterial);
        moon.position.set(0, 8, 0);
        scene.add(moon);


        // Sphere Background
        const textureSphereBg = loader.load('https://i.ibb.co/JCsHJpp/stars2-qx9prz.jpg');
        textureSphereBg.anisotropy = 16;
        const geometrySphereBg = new THREE.SphereBufferGeometry(150, 32, 32);
        const materialSphereBg = new THREE.MeshBasicMaterial({
            side: THREE.BackSide,
            map: textureSphereBg,
            fog: false
        });
        sphereBg = new THREE.Mesh(geometrySphereBg, materialSphereBg);
        sphereBg.position.set(0, 50, 0);
        scene.add(sphereBg);


        // Terrain
        const textureTerrain = loader.load();
        textureTerrain.rotation = THREE.MathUtils.degToRad(5);
        terrainGeometry = new THREE.PlaneBufferGeometry(70, 70, 20, 20);
        const terrainMaterial = new THREE.MeshBasicMaterial({
            map: textureTerrain,
            fog: true
        });
        terrain = new THREE.Mesh(terrainGeometry, terrainMaterial);
        terrain.rotation.x = -0.47 * Math.PI;
        terrain.rotation.z = THREE.Math.degToRad(90);
        scene.add(terrain);

        let t_vertex_Array = terrainGeometry.getAttribute("position").array;
        terrainGeometry.getAttribute("position").setUsage(THREE.DynamicDrawUsage);

        terrainGeometry.setAttribute("myZ", new THREE.BufferAttribute(new Float32Array(t_vertex_Array.length / 3), 1));
        let t_myZ_Array = terrainGeometry.getAttribute("myZ").array;

        for (let i = 0; i < t_vertex_Array.length; i++) {
            t_myZ_Array[i] = THREE.MathUtils.randInt(0, 5);
        }

        //Terain Lines
        const terrain_line = new THREE.LineSegments(
            terrainGeometry,
            new THREE.LineBasicMaterial({
                color: "#fff",
                fog: false
            })
        );
        terrain_line.rotation.x = -0.47 * Math.PI;
        terrain_line.rotation.z = THREE.Math.degToRad(90);
        scene.add(terrain_line);



        //  Stars
        for (let i = 0; i < lineTotal; i++) {
            let x = THREE.MathUtils.randInt(-100, 100);
            let y = THREE.MathUtils.randInt(10, 40);
            if (x < 7 && x > -7 && y < 20) x += 14;
            let z = THREE.MathUtils.randInt(0, -300);

            l_vertex_Array[6 * i + 0] = l_vertex_Array[6 * i + 3] = x;
            l_vertex_Array[6 * i + 1] = l_vertex_Array[6 * i + 4] = y;
            l_vertex_Array[6 * i + 2] = l_vertex_Array[6 * i + 5] = z;

            l_velocity_Array[2 * i] = l_velocity_Array[2 * i + 1] = 0;
        }
        let starsMaterial = new THREE.LineBasicMaterial({
            color: "#ffffff",
            transparent: true,
            opacity: 0.5,
            fog: false
        });
        let lines = new THREE.LineSegments(linesGeometry, starsMaterial);
        linesGeometry.getAttribute("position").setUsage(THREE.DynamicDrawUsage);
        scene.add(lines);
    }




    function animate() {
        planet.rotation.y += 0.002;
        sphereBg.rotation.x += 0.002;
        sphereBg.rotation.y += 0.002;
        sphereBg.rotation.z += 0.002;


        // Moon Animation
        moon.rotation.y -= 0.007;
        moon.rotation.x -= 0.007;
        moon.position.x = 15 * Math.cos(t) + 0;
        moon.position.z = 20 * Math.sin(t) - 35;
        t += 0.015;


        // Terrain Animation
        let t_vertex_Array = terrainGeometry.getAttribute("position").array;
        let t_myZ_Array = terrainGeometry.getAttribute("myZ").array;

        for (let i = 0; i < t_vertex_Array.length; i++) {
            if (i >= 210 && i <= 250) t_vertex_Array[i * 3 + 2] = 0;
            else {
                t_vertex_Array[i * 3 + 2] = Math.sin((i + count * 0.0003)) * (t_myZ_Array[i] - (t_myZ_Array[i] * 0.5));
                count += 0.1;
            }
        }

        //   Stars Animation
        for (let i = 0; i < lineTotal; i++) {
            l_velocity_Array[2 * i] += 0.0049;
            l_velocity_Array[2 * i + 1] += 0.005;

            l_vertex_Array[6 * i + 2] += l_velocity_Array[2 * i];
            l_vertex_Array[6 * i + 5] += l_velocity_Array[2 * i + 1];

            if (l_vertex_Array[6 * i + 2] > 50) {
                l_vertex_Array[6 * i + 2] = l_vertex_Array[6 * i + 5] = THREE.MathUtils.randInt(-200, 10);
                l_velocity_Array[2 * i] = 0;
                l_velocity_Array[2 * i + 1] = 0;
            }
        }


        //Camera Movement
        camera.position.x += cameraDx;
        camera.position.y = -1.2 * (1 - Math.abs(frame / 2000 - 0.5) / 0.5);
        camera.lookAt(0, 0, 0);
        frame += 8;
        if (frame > 2000) frame = 0;
        if (camera.position.x > 18) cameraDx = -cameraDx;
        if (camera.position.x < -18) cameraDx = Math.abs(cameraDx);


        l_positionAttr.needsUpdate = true;
        terrainGeometry.attributes.position.needsUpdate = true;
        renderer.render(scene, camera);
        requestAnimationFrame(animate);
    }




    /*     Resize     */
    window.addEventListener("resize", () => {
        clearTimeout(timeout_Debounce);
        timeout_Debounce = setTimeout(onWindowResize, 80);
    });
    function onWindowResize() {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    }



    /*     Fullscreen btn     */
    let fullscreen;
    let fsEnter = document.getElementById('fullscr');
    fsEnter.addEventListener('click', function (e) {
        e.preventDefault();
        if (!fullscreen) {
            fullscreen = true;
            document.documentElement.requestFullscreen();
            fsEnter.innerHTML = "خروج از تمام صفحه";
        }
        else {
            fullscreen = false;
            document.exitFullscreen();
            fsEnter.innerHTML = "تمام صفحه";
        }
    });
</script>
</html>

