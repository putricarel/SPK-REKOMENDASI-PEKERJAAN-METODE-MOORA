<?php
session_start();
// JIKA TIDAK DITEMUKAN $_SESSION['status'] (USER/ADMIN TIDAK MELIWATI TAHAP LOGIN) MAKA LEMBAR ADMIN/USER KEHALAMAN LOGIN 
if (!isset($_SESSION['status'])) {
    header("Location: ../index.php?pesan=logindahulu");
    exit;
}

require '../functions.php';

$current_page = basename($_SERVER['PHP_SELF']);

$data_kriteria = tampilkriteria("SELECT * FROM kriteria");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <style>
        body {
            background-color: #FFFFFF; /* Putih */
            background-image: url(../img/galeri/potoo.jpg);
            background-size: cover;
        }

        .container {
            min-height: calc(100vh - 211px - -60px);
            background-color: #FFFFFF; /* Putih */
            padding: 20px; /* Optional: Add some padding */
            border-radius: 10px; /* Optional: Add rounded corners */
        }

        .col-md-12.bg-primary {
            padding: 8px;
            background-color: #003B5C !important; /* Biru Tua */
        }

        .copyright {
            text-align: center;
            color: #fff;
        }

        .alert-info {
            background-color: #B3C7E6; /* Biru Muda */
            color: #003B5C; /* Biru Tua */
        }

        .navbar {
            background-color: #003B5C; /* Biru Tua */
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: #FFFFFF; /* Putih */
        }

        tr:hover {
            transform: scale(1.03);
            font-weight: bold;
        }

        .navbar-nav .active {
            color: #ffff !important;
        }

        .table-striped {
            background-color: #E6F0FF; /* Biru Muda */
        }

        .table th,
        .table td {
            color: #003B5C; /* Biru Tua */
        }

        .table th {
            background-color: #003B5C; /* Biru Tua */
            color: white;
        }
    </style>
    <title>DATA KRITERIA</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav" style="margin: 10px;">
                <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index.php">
                    <font size="4"><b>Home</b></font>
                </a>
                <a class="nav-link <?= $current_page == 'data_kriteria.php' ? 'active' : '' ?>" href="data_kriteria.php">
                    <font size="4"><b>Data Kriteria</b></font>
                </a>
                <a class="nav-link <?= $current_page == 'it.php' ? 'active' : '' ?>" href="it.php">
                    <font size="4"><b>Data Bidang Keahlian IT</b></font>
                </a>
                <a class="nav-link <?= $current_page == 'data_cafe.php' ? 'active' : '' ?>" href="data_cafe.php">
                    <font size="4"><b>Perhitungan</b></font>
                </a>
                <a class="nav-link <?= $current_page == 'laporan.php' ? 'active' : '' ?>" href="laporan.php">
                    <font size="4"><b>Laporan</b></font>
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
    <div class="container bg-light shadow p-3 mb-5">
        <div class="alert alert-info">
            <center><b>DATA KRITERIA</b></center>
        </div>

        <div class="table-responsive p-4">
            <table class="table table-striped shadow">
                <tr class="bg-info">
                    <th>No</th>
                    <th>Kriteria</th>
                    <th>Bobot</th>
                    <th>Type</th>
                    <th>Aksi</th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach ($data_kriteria as $data) { ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $data['kriteria']; ?></td>
                        <td><?= $data['bobot']; ?></td>
                        <td><?= $data['type']; ?></td>
                        <td><a href="edit_kriteria.php?id_kriteria=<?= $data['id_kriteria']; ?>" class="btn btn-warning">Edit</a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div class="col-md-12 bg-primary">
        <div class="copyright">
            <h6>Copyright&copy; Putri Carellilas Fony</h6>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
</body>
</html>