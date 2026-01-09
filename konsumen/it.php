<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "skripsi");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT alternatif, skill_name FROM skills";
$result = $conn->query($sql);

// Menyiapkan HTML
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
            background-color: #003366; /* Biru tua */
            background-image: url(../img/galeri/potoo.jpg);
            background-size: cover;
        }
        .container {
            min-height: calc(100vh - 211px);
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav" style="margin: 10px;">
                <a class="nav-link" href="index.php"><b>Home</b></a>
                <a class="nav-link" href="it.php"><b>Data Bidang Keahlian IT</b></a>
                <a class="nav-link" href="data_cafe.php"><b>Perhitungan</b></a>
            <div class="navbar-nav ms-auto" style="margin: 10px;">
                <a class="nav-link" href="../logout.php"><b>Logout</b><img src="../img/logout.png" width="30"></a>
            </div>
        </div>
    </nav>

    <div class="alert alert-info">
        <center><b>Data Bidang Keahlian IT</b></center>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Kategory Bidang IT</th>
                <th>Skill</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Array untuk menyimpan data
            $data = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Kumpulkan skill berdasarkan alternatif
                    $data[$row["alternatif"]][] = $row["skill_name"];
                }

                // Tampilkan data
                foreach ($data as $alternatif => $skills) {
                    echo "<tr><td>" . $alternatif . "</td><td>" . implode(", ", $skills) . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="col-md-12 bg-primary">
        <div class="copyright">
            <h6>Copyright&copy; Putri Carellilas Fony</h6>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>