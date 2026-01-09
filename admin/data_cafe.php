<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("Location: ../index.php?pesan=logindahulu");
    exit;
}

require '../functions.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Menambahkan opsi untuk kriteria
$c1_options = [
    3 => '5 skill yang di kuasai ',
    2 => '4-3 skill yang di kuasai',
    1 => '2-1 skill yang di kuasai',
];

$c2_options = [
    2 => 'Full-time',
    1 => 'contrak',
];

$c3_options = [ 
    4 => 'Director ',
    3 => 'Mid-Senior Level',
    2 => 'Associate',
    1 => 'Entry level',
];

$data_cafe = tampilcafe("SELECT * FROM alternatif");
$data_cafe1 = mysqli_query($con, "SELECT * FROM alternatif");

if (isset($_POST['cari'])) {
    $input = $_POST['input'];
    $data_cafe = tampilcafe("SELECT * FROM alternatif WHERE nama_alternatif LIKE '%$input%' OR id_alternatif LIKE '%$input%' ");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #003366; /* Biru tua */
            background-image: url(../img/galeri/potoo.jpg);
            background-size: cover;
        }
        .container {
            min-height: calc(100vh - 211px - -60px);
            background-color: #ffffff; /* Putih */
            padding: 15px; 
            border-radius: 10px; 
            margin-top: 30px;
        }
        .col-md-12 {
            padding: 8px;
            background-color: #003366 !important; /* Biru tua */
        }
        .copyright {
            text-align: center;
            color: #FFFFFF; /* Putih */
        }
        .navbar {
            background-color: #003366; /* Biru tua */
        }
        .alert-info {
            background-color: #ffffff; /* Putih */
            color: #003366; /* Biru tua */
        }
        .table-striped {
            background-color: #f0f8ff; /* Warna lembut */
        }
        .table th, .table td {
            color: #003366; /* Biru tua */
        }
        .table th {
            background-color: #00509e; /* Biru lebih tua */
            color: white;
        }
        .bg-primary {
            background-color: #003366 !important; /* Biru tua */
        }
        .btn-brown {
            background-color: #00509e; /* Biru lebih tua */
            color: #FFFFFF; 
            border: none;
        }
        .btn-light-brown {
            background-color: #f0f8ff; /* Warna lembut */
            border: none;
        }
        .btn-dark-brown {
            background-color: #002244; /* Biru gelap */
            border: none;
        }
    </style>
    <title>LAPORAN</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav" style="margin: 10px;">
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

    <div class="alert alert-info">
        <center><b>Perhitungan</b></center>
    </div>

    <div class="form-inline">
        <form method="POST" action="" class="form-group">
            <input type="text" name="input" autofocus autocomplete="off" class="form-control" placeholder="Cari...">
            <button type="submit" name="cari" class="btn btn-brown">Cari</button>
        </form>
    </div>
    <br>
    <a href="tambah_data_cafe.php" class="btn btn-light-brown text-dark" style="margin-left: 10px;"><b>Tambah Data</b></a>

    <form method="POST" action="perhitungan.php" class="d-inline">
        <button type="submit" name="perhitungan" class="btn btn-success text-light" style="margin-left: 10px;">
    <b>Hitung</b>
</button>
        <br><br>

        <table class="table table-striped shadow p-3 mb-5">
            <?php $tot = mysqli_num_rows($data_cafe1); 
            echo "Total Data : <b>" . $tot . "</b>";
            ?>
            <tr class="bg-info">
                <th>Id Alternatif</th>
                <th>Nama Alternatif</th>
                <th>Tahun</th>
                <th>Skill</th>
                <th>Score Skill</th>
                <th>Level</th>
                <th>Involvement</th>
                <th>Aksi</th>
            </tr>

            <?php foreach ($data_cafe as $cafe) { ?>
                <tr>
                    <td><?= $cafe['id_alternatif']; ?></td>
                    <td><?= $cafe['nama_alternatif']; ?></td>
                    <td><?= $cafe['tahun']; ?></td>
                    <td><?= $cafe['nama_skill']; ?></td>
                    <td><?= $cafe['c1']; ?></td>
                    <td><?= $cafe['c2']; ?></td>
                    <td><?= $cafe['c3']; ?></td>
                    <td> 
                        <a href="hapus_data_cafe.php?id_alternatif=<?= $cafe['id_alternatif']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </form>

    <div class="col-md-12 bg-primary">
        <div class="copyright">
            <h6>Copyright&copy; Putri Carellilas Fony</h6>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>