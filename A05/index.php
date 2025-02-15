<?php
include('connect.php');
include('classes.php');

$result = executeQuery("SELECT * FROM islandsOfPersonality");
$islands = [];

while ($row = $result->fetch_assoc()) {
    $islands[] = new Island(
        $row['islandOfPersonalityID'],
        $row['name'],
        $row['color'],
        $row['image'],
        $row['longDescription'],
        $row['shortDescription']
    );
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Core Memories</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../img/tab icon.svg" type="img/icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Questrial&display=swap"
        rel="stylesheet">
    <style>
        body,
        h1,
        h2 {
            font-family: "Poppins", sans-serif
        }

        body,
        html {
            scroll-behavior: smooth;
            height: 100%
        }

        p {
            line-height: 2
        }

        .bgimg,
        .bgimg2 {
            min-height: 100%;
            background-position: center;
            background-size: cover;
        }

        .bgimg {
            background-image: url("img/background.webp")
        }

        .bgimg2 {
            background-image: url("img/cats.jpg")
        }

        .btn:hover {
            opacity: 1;
        }

        .header-title {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .nav-link.active {
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #333;
        }

        .nav-link:hover {
            color: black;
        }

        .content img {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        .island-img {
            max-height: 450px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <header class="position-relative wide bgimg" id="home">
        <div class="position-absolute text-white text-center header-title" style="margin-top: -7.5rem">
            <h1 style="font-size: 4rem;">Core Memories</h1>
            <h2>by Ronald John R. Villar</h2>
            <h2><b>21.12.2024</b></h2>
        </div>
    </header>

    <div class="position-fixed bottom-0 w-100 d-none d-sm-block">
        <div class="nav bg-white text-center p-3">
            <?php foreach ($islands as $island) { ?>
                <a href="#<?php echo strtolower(str_replace(' ', '_', $island->name)); ?>" style="width:25%" class="nav-link"><?php echo $island->name; ?></a>
            <?php } ?>
        </div>
    </div>

    <?php foreach ($islands as $island) {
        $contentResult = $conn->query("SELECT * FROM islandContents WHERE islandOfPersonalityID = {$island->id}");
        echo $island->generateIslandSection($contentResult);
    } ?>

    <footer class="text-center text-white bg-dark py-3">
        <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank"
                class="w3-hover-text-green">Bootstrap</a></p>
    </footer>

    <div class="d-none d-sm-block" style="margin-bottom:32px"> </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        const navLinks = document.querySelectorAll('.nav-link');
        window.addEventListener('scroll', () => {
            let fromTop = window.scrollY;

            navLinks.forEach(link => {
                let section = document.querySelector(link.getAttribute("href"));
                if (section.offsetTop <= fromTop && section.offsetTop + section.offsetHeight > fromTop) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>