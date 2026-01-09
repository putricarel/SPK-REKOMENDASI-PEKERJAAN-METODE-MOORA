<?php
session_start();
// Redirect to login if the session status is not set
if (!isset($_SESSION['status'])) {
    header("Location: ../index.php?pesan=logindahulu");
    exit;
}

require '../functions.php';

$kode = $_GET['kode'];
$data = query("SELECT * FROM laporan WHERE id_laporan = '$kode'");

// Calculate the summary
$alternatif_count = count($data);
$highest_ranking = null;
$highest_alternatif = null;
$highest_hasil = null;

foreach ($data as $detail_data) {
    if ($detail_data['ranking'] == 1) {
        $highest_ranking = $detail_data['ranking'];
        $highest_alternatif = $detail_data['nama_alternatif'];
        $highest_hasil = $detail_data['max_min'];
        break; // We found the highest ranking, exit the loop
    }
}

// If there are no records
// Kesimpulan lengkap dengan rekomendasi pekerjaan
if ($alternatif_count === 0) {
    $summary = "Kesimpulan: Tidak ada data untuk ditampilkan.";
} else {
    // Ambil detail dari tabel alternatif berdasarkan nama_alternatif
    $selected_alternatif = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM alternatif WHERE nama_alternatif = '$highest_alternatif'"));

    $level_id = $selected_alternatif['c2'];
    $involvement_id = $selected_alternatif['c3'];
    $category = $selected_alternatif['nama_alternatif'];

    // Interpretasi level
    $level_mapping = [
        2 => 'Full-time',
        1 => 'Contract',
    ];
    $level_text = $level_mapping[$level_id] ?? 'Tidak Diketahui';

    // Interpretasi involvement
    $involvement_mapping = [
        4 => 'Director',
        3 => 'Mid-Senior',
        2 => 'Associate',
        1 => 'Entry level',
    ];
    $involvement_text = $involvement_mapping[$involvement_id] ?? 'Tidak Diketahui';

    // Data pekerjaan
    $jobs_data = [
        ['category' => 'AI & NLP Engineer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Lead Administrator', 'Technical Lead', 'Consultant-Technology & Implementation']],
        ['category' => 'AI & NLP Engineer', 'involvement' => 'Full-time', 'level' => 'Associate', 'job_titles' => ['Sr. Quality Analyst', 'Technology Analyst - .Net', 'Administrative Officer']],
        ['category' => 'AI & NLP Engineer', 'involvement' => 'Full-time', 'level' => 'Entry level', 'job_titles' => ['Test Engineer', 'Product Marketing Manager', 'AI/ML - Data Scientist']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Full-time', 'level' => 'Entry level', 'job_titles' => ['Opening for Database Developer', 'PL/PM (Scrum Master)', 'Data Modeler']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Contract', 'level' => 'Entry level', 'job_titles' => ['RPA UI PATH']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Contract', 'level' => 'Director', 'job_titles' => ['Customer Delivery Head']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Contract', 'level' => 'Mid-Senior', 'job_titles' => ['Change Manager', 'Oracle Finance Technical Consultant', 'Required SAP FICO Consultants for Contract']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Opening for Scrum Master', 'BA - P and C Insurance - Billing', 'Workday Consultant']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Full-time', 'level' => 'Director', 'job_titles' => ['Delivery Head']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Full-time', 'level' => 'Associate', 'job_titles' => ['Sr. Quality Analyst']],
        ['category' => 'Big Data Engineer', 'involvement' => 'Contract', 'level' => 'Mid-Senior', 'job_titles' => ['Change Manager', 'Oracle Finance Technical Consultant', 'Network Lead']],
        ['category' => 'Data Science & ML Engineer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Python Architect/Python Tech Lead/Python Technical Lead']],
        ['category' => 'Database Engineer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Software Engineer – Senior', 'Staff Software Engineer', 'Engineer - Lead']],
        ['category' => 'Full-Stack JavaScript Developer', 'involvement' => 'Full-time', 'level' => 'Entry level', 'job_titles' => ['Java Developer', 'APEX Developer', 'Java Full stack Developer']],
        ['category' => 'Full-Stack JavaScript Developer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Pega CSSA Specialist', 'Full Stack Developer', 'Java Associate Developer']],
        ['category' => 'Full-Stack JavaScript Developer', 'involvement' => 'Contract', 'level' => 'Entry level', 'job_titles' => ['UI/UX Developer', 'Net Core Developer', 'Front end developers']],
        ['category' => 'Full-Stack JavaScript Developer', 'involvement' => 'Contract', 'level' => 'Mid-Senior', 'job_titles' => ['Dot Net Developer']],
        ['category' => 'Python Web Developer', 'involvement' => 'Full-time', 'level' => 'Entry level', 'job_titles' => ['Python Developer']],
        ['category' => 'Python Web Developer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Python Software Engineer', 'Python Web Developer', 'Senior Python Programmer']],
        ['category' => 'Python Web Developer', 'involvement' => 'Full-time', 'level' => 'Associate', 'job_titles' => ['Python QA & Automation Engineer']],
        ['category' => 'QA & Automation Engineer', 'involvement' => 'Full-time', 'level' => 'Entry level', 'job_titles' => ['Web Developer', 'MERN Developer']],
        ['category' => 'QA & Automation Engineer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['React Developer']],
        ['category' => 'Systems / Backend Developer', 'involvement' => 'Full-time', 'level' => 'Mid-Senior', 'job_titles' => ['Artificial Intelligence Engineer', 'Big Data Engineer', 'Data Scientist']],
        ['category' => 'Systems / Backend Developer', 'involvement' => 'Full-time', 'level' => 'Entry level', 'job_titles' => ['GCP Professionals']],
    ];

    // Cari rekomendasi pekerjaan
    $job_recommendation = "Belum ada lowongan pekerjaan dengan kriteria tersebut.";
    foreach ($jobs_data as $job) {
        if ($job['category'] === $category && $job['involvement'] === $level_text && $job['level'] === $involvement_text) {
            $job_titles = implode(', ', $job['job_titles']);
            $job_recommendation = $job_titles;
            break;
        }
    }

    $summary = "Kesimpulan: Berdasarkan hasil perhitungan menggunakan metode MOORA, dari jumlah alternatif ($alternatif_count), dapat dilihat bahwa '$highest_alternatif' memiliki nilai terbesar dengan hasil $highest_hasil. Dengan kata lain, '$highest_alternatif' terpilih sebagai Bidang Keahlian IT tujuan. Maka dapat disimpulkan dari kategori '$category', jenis pekerjaan '$involvement_text', dan level '$level_text', maka rekomendasi pekerjaan yang sesuai adalah: $job_recommendation.";
}
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
            background-color: #ffffff; /* Putih */
        }
        .container {
            min-height: calc(100vh - 211px - -60px);
        }
        .col-md-12 {
            padding: 8px;
            background-color: #003366 !important; /* Biru tua */
        }
        .copyright {
            text-align: center;
            color: #ffffff; /* Putih */
        }
        .navbar-nav a:hover {
            color: #00509e; /* Biru lebih terang */
        }
        tr:hover {
            transform: scale(1.03);
            font-weight: bold;
        }
        .alert-info {
            background-color: #003366; /* Biru tua */
            color: #ffffff; /* Putih */
        }
        .table-striped {
            background-color: #e6f7ff; /* Light Blue */
        }
        .table th, .table td {
            color: #003366; /* Biru tua */
        }
        .table th {
            background-color: #003366; /* Biru tua */
            color: white;
        }
        @media print {
            .no-print {
                display: none; /* Hide elements with class 'no-print' when printing */
            }
        }
    </style>
    <title>Detail Laporan</title>
</head>

<body>
    <form method="post" action="perhitungan.php">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #003366;">
            <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav" style="margin: 10px;">
                    <a class="nav-link" href="index.php"><b>Home</b></a>
                    <a class="nav-link" href="data_kriteria.php"><b>Data Kriteria</b></a>
                    <a class="nav-link" href="it.php"><b>Data Bidang Keahlian IT</b></a>
                    <a class="nav-link" href="data_cafe.php"><b>Perhitungan</b></a>
                    <a class="nav-link active" href="laporan.php"><b>Laporan</b></a>
                </div>

                <div class="navbar-nav ms-auto" style="margin: 10px;">
                    <a class="log nav-link m-auto" href="../logout.php">
                        <b>Logout</b>
                        <img src="../img/logout.png" width="30">
                    </a>
                </div>
            </div>
        </nav>
    </form>
    <br>
  <div class="container bg-light shadow p-3 mb-5">
    <div class="alert alert-info">
        <center><b>SISTEM PENDUKUNG KEPUTUSAN REKOMENDASI PEKERJAAN PRODI TEKNIK INFORMATIKA</b></center>
    </div>

    <div class="text-center mb-3">
        <span style="font-size: 1.5em; font-weight: bold;">Kode Laporan: <?= $data[0]['id_laporan']; ?></span><br>
        <span style="font-size: 1.2em; font-weight: bold;">Tanggal: <?= date('d-m-Y'); ?></span>
    </div>

    <div class="no-print">
        <a href="laporan.php" class="btn btn-primary" style="background-color: #003366; color: white; margin-bottom: 15px;">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary" style="background-color: #003366; color: white; margin-bottom: 15px;">Cetak</button>
    </div>

    <div class="table-responsive p-4">
        <table class="table table-striped shadow">
            <tr class="bg-info">
                <th>ID Alternatif</th>
                <th>Nama Alternatif</th>
                <th>Hasil</th>
                <th>Ranking</th>
            </tr>

            <?php if ($alternatif_count > 0): ?>
                <?php foreach ($data as $detail_data): ?>
                    <tr>
                        <td><?= $detail_data['id_alternatif']; ?></td>
                        <td><?= $detail_data['nama_alternatif']; ?></td>
                        <td><?= $detail_data['max_min']; ?></td>
                        <td><?= $detail_data['ranking']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

        <div class="alert alert-info">
            <p><?= $summary; ?></p>
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