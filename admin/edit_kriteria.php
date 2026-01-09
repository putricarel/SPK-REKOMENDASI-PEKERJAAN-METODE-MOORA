<?php

session_start();
//JIKA TIDAK DITEMUKAN $_SESSION['status'] (USER/ADMIN TIDAK MELIWATI TAHAP LOGIN) MAKA LEMBAR ADMIN/USER KEHALAMAN LOGIN 
if (!isset($_SESSION['status'])) {
  header("Location: ../index.php?pesan=logindahulu");
  exit;
}

require '../functions.php';

$id_kriteria = $_GET['id_kriteria'];

$data_kriteria = tampilkriteria("SELECT * FROM kriteria WHERE id_kriteria = '$id_kriteria' ")[0];

if (isset($_POST['edit'])) {
  if (edit_kriteria($_POST) > 0) {
    echo "<script>
    alert ('Data Berhasil Di Edit')
    document.location.href='data_kriteria.php'
    </script>";
  }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <style>
      body {
        background-color: #ffffff; /* Putih */
      }

      .container {
        min-height: calc(100vh - 211px - -60px);
      }

      .alert-info {
        background-color: #003366; /* Biru tua */
        color: #ffffff; /* Putih */
      }

      .col-md-12 {
        padding: 8px;
        background-color: #003366 !important; /* Biru tua */
      }

      .copyright {
        text-align: center;
        color: #ffffff; /* Putih */
      }

      .navbar-nav .active {
        color: #ffffff !important; 
      }

      a font {
        color: whitesmoke;
      }

      .navbar-nav a:hover {
        color: #003366; /* Biru lebih terang saat hover */
      }
    </style>

    <title>EDIT KRITERIA</title>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark ;" style="background-color: #003366;">
    <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav" style="margin: 10px; background-color: #003366;">
            <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index.php"><b>Home</b></a>
            <a class="nav-link <?= $current_page == 'data_kriteria.php' ? 'active' : '' ?>" href="data_kriteria.php"><b>Data Kriteria</b></a>
            <a class="nav-link <?= $current_page == 'it.php' ? 'active' : '' ?>" href="it.php"><b>Data Bidang Keahlian IT</b></a>
            <a class="nav-link <?= $current_page == 'data_cafe.php' ? 'active' : '' ?>" href="data_cafe.php"><b>Perhitungan</b></a>
            <a class="nav-link <?= $current_page == 'laporan.php' ? 'active' : '' ?>" href="laporan.php"><b>Laporan</b></a>
        </div>
        <div class="navbar-nav ms-auto" style="margin: 10px;">
            <a class="log nav-link" href="../logout.php"><b>Logout</b><img src="../img/logout.png" width="30"></a>
        </div>
    </div>
</nav>
<br>
<div class="container bg-light shadow p-3 mb-5">
    <div class="alert alert-info">
        <center><b>EDIT DATA KRITERIA</b></center>
    </div>

    <div class="col-md-7">
        <form method="post" class="form-group">
            <table class="table">
                <input type="hidden" name="id_kriteria" value="<?= $data_kriteria['id_kriteria']; ?>">
                <tr>
                    <td><label>Kriteria</label></td>
                    <td> : </td>
                    <td width="500"><input type="text" name="kriteria" value="<?= $data_kriteria['kriteria']; ?>" class="form-control" autocomplete="off"></td>
                </tr>

                <tr>
                    <td><label>Bobot</label></td>
                    <td> : </td>
                    <td width="500"> <input type="text" name="bobot" value="<?= $data_kriteria['bobot']; ?>" class="form-control" autocomplete="off"></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>:</td>
                    <?php
                    if ($data_kriteria['type'] === 'Benefit') {
                    ?>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="form-check-input" id="radio1" name="type" value="Benefit" checked> Benefit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" class="form-check-input" id="radio1" name="type" value="Cost"> Cost
                    </td>
                    <?php
                    } else {
                    ?>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="form-check-input" id="radio1" name="type" value="Benefit"> Benefit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" class="form-check-input" id="radio1" name="type" value="Cost" checked> Cost
                    </td>
                    </tr>
                    <?php
                    }
                    ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td><button type="submit" name="edit" class="btn btn-warning">Edit</button> &nbsp;&nbsp;&nbsp;
                        <a href="data_kriteria.php" class="btn btn-danger">Kembali</a>
                    </td>
                </tr>
            </table>

        </form>
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