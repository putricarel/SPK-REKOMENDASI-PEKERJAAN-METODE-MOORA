<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("Location: ../index.php?pesan=logindahulu");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #003366; /* Dark blue background */
            background-image: url(../img/galeri/c17.png);
            background-size: cover;
        }

        .navbar {
            background-color: #002244; /* Darker blue for navbar */
        }

        .navbar-brand img {
            border-radius: 50%;
        }

        .navbar-nav .nav-link {
            color: #FFFFFF; /* White text */
        }

        .navbar-nav .nav-link.active {
            color: #FFFF !important;
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover {
            color: #FFFFFF !important;
        }

        .container {
            background-color: #FFFFFF; /* White container */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container {
            min-height: calc(100vh - 211px - -60px);
        }

        .carousel {
            max-width: 90%;
            margin: 0 auto;
        }

        .carousel-item {
            height: 480px;
        }

        .carousel-item img {
            height: 100%;
            object-fit: cover;
        }

        .bg-primary {
            background-color: #002244 !important;
            padding: 8px;
            color: #FFFFFF !important;
        }

        .alert-brown {
            background-color: #003366; /* Dark blue alert */
            color: #FFFFFF;
            font-weight: bold;
            border: none;
        }

        .copyright h6 {
            margin: 10px 0;
            font-weight: bold;
            text-align: center;
            color: #FFFFFF; /* White text */
        }

        .large-image {
            width: 100%; /* Full width for better visibility */
            max-width: 1000px; /* Maximum width for large images */
            height: auto; /* Maintain aspect ratio */
        }
    </style>
    <title>Home</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav" style="margin: 10px;">
                <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index.php">
                    <font size="4"><b>Home</b></font>
                </a>
                <a class="nav-link <?= $current_page == 'it.php' ? 'active' : '' ?>" href="it.php">
                    <font size="4"><b>Data Bidang Keahlian IT</b></font>
                </a>
                <a class="nav-link <?= $current_page == 'data_cafe.php' ? 'active' : '' ?>" href="data_cafe.php">
                    <font size="4"><b>Perhitungan</b></font>
                </a>
            </div>
            <div class="navbar-nav ms-auto" style="margin: 10px;">
                <a class="log nav-link" href="../logout.php">
                    <font size="4"><b>Logout</b></font>
                    <img src="../img/logout.png" width="30">
                </a>
            </div>
        </div>
    </nav>

    <br>
    <div class="container bg-brown shadow p-3 mb-5">
        <div class="alert alert-brown text-light">
            <center><b>SELAMAT DATANG USER</b></center>
        </div>
        <br>
        <center>
            <font size="5" class="judul"><b>"SISTEM PENDUKUNG KEPUTUSAN REKOMENDASI PEKERJAAN PRODI TEKNIK INFORMATIKA MENGGUNAKAN METODE MOORA”</b></font>
        </center>

        <br>
        <div class="text-center">
            <img src="../img/galeri/c15.png" class="large-image mb-2" alt="Image 1">
        </div>

        <br><br>

        <center>
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../img/galeri/c1.png" width="300" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/galeri/c2.png" width="300" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/galeri/c5.png" width="300" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item ">
                        <img src="../img/galeri/c7.png" width="300" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/galeri/c9.png" width="300" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </center>

        <br>
    </div>

    <div class="col-md-12 bg-primary">
        <div class="copyright">
            <h6>Copyright&copy; Putri Carellilas Fony</h6>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>