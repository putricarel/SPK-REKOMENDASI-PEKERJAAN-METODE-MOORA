<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("Location: ../xml_get_current_byte_index(parser).php?pesan=logindahulu");
    exit;
}
require '../functions.php';

// Ambil semua bobot kriteria
$datakriteriascore_skill = mysqli_query($con, "SELECT * FROM kriteria WHERE kriteria = 'score_skill'");
$score_skill = mysqli_fetch_assoc($datakriteriascore_skill);

$datakriterialevel = mysqli_query($con, "SELECT * FROM kriteria WHERE kriteria = 'level'");
$level = mysqli_fetch_assoc($datakriterialevel);

$datakriteriainvolvement = mysqli_query($con, "SELECT * FROM kriteria WHERE kriteria = 'involvement'");
$involvement = mysqli_fetch_assoc($datakriteriainvolvement);

// Hitung bobot dari database
$bobot_c1 = $score_skill['bobot'];
$bobot_c2 = $level['bobot'];
$bobot_c3 = $involvement['bobot'];

if (isset($_POST['simpan'])) {
    $tanggal = date('Y-m-d H:i:s');
    $id_laporan = date('ymd') . str_pad(mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM laporan"))['count'] + 1, 2, '0', STR_PAD_LEFT);

    // Ambil semua data alternatif
    $data_alternatif_result = mysqli_query($con, "SELECT * FROM alternatif");
    $data_alternatif = [];

    while ($data_row = mysqli_fetch_assoc($data_alternatif_result)) {
        $data_alternatif[] = $data_row;
    }

    // Hitung akar untuk normalisasi
    $akar1 = sqrt(array_sum(array_map(fn($row) => pow(($row['c1'] >= 5 ? 1 : (($row['c1'] >= 3) ? 2 : 3)), 2), $data_alternatif)));
    $akar2 = sqrt(array_sum(array_map(fn($row) => pow($row['c2'], 2), $data_alternatif)));
    $akar3 = sqrt(array_sum(array_map(fn($row) => pow($row['c3'], 2), $data_alternatif)));

    $results = [];

    foreach ($data_alternatif as $data_row) {
        $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);

        $normalized_c1 = ($akar1 != 0) ? $score_skill_value / $akar1 : 0;
        $normalized_c2 = ($akar2 != 0) ? $data_row['c2'] / $akar2 : 0;
        $normalized_c3 = ($akar3 != 0) ? $data_row['c3'] / $akar3 : 0;

        $total = round(($normalized_c1 * $bobot_c1) + ($normalized_c2 * $bobot_c2) + ($normalized_c3 * $bobot_c3), 3);

        $results[] = [
            'id_alternatif' => $data_row['id_alternatif'],
            'nama_alternatif' => $data_row['nama_alternatif'],
            'total' => $total,
        ];
    }

    // Urutkan hasil dari tertinggi ke terendah
    usort($results, fn($a, $b) => $b['total'] <=> $a['total']);

    // Simpan ke DB
    foreach ($results as $index => $result) {
        $ranking = $index + 1;
        $insert_query = "INSERT INTO laporan (id_laporan, tanggal, id_alternatif, nama_alternatif, max_min, ranking)
                         VALUES ('$id_laporan', '$tanggal', '{$result['id_alternatif']}', '{$result['nama_alternatif']}', '{$result['total']}', '$ranking')";

        if (!mysqli_query($con, $insert_query)) {
            echo "Error: " . mysqli_error($con);
        }
    }

    // Redirect jika berhasil
    header("Location: laporan.php");
    exit;
}

// Ambil data dari tabel alternatif
$data_alternatif = query("SELECT * FROM alternatif");
$alternatif_count = count($data_alternatif);

// Buat array untuk menyimpan hasil akhir (untuk ditampilkan & kesimpulan)
$hasil_akhir = [];

$akar1 = sqrt(array_sum(array_map(fn($row) => pow(($row['c1'] >= 5 ? 1 : (($row['c1'] >= 3) ? 2 : 3)), 2), $data_alternatif)));
$akar2 = sqrt(array_sum(array_map(fn($row) => pow($row['c2'], 2), $data_alternatif)));
$akar3 = sqrt(array_sum(array_map(fn($row) => pow($row['c3'], 2), $data_alternatif)));

foreach ($data_alternatif as $data_row) {
    $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);

    $normalized_c1 = ($akar1 != 0) ? $score_skill_value / $akar1 : 0;
    $normalized_c2 = ($akar2 != 0) ? $data_row['c2'] / $akar2 : 0;
    $normalized_c3 = ($akar3 != 0) ? $data_row['c3'] / $akar3 : 0;

    $terbobot_c1 = round($normalized_c1 * $bobot_c1, 3);
    $terbobot_c2 = round($normalized_c2 * $bobot_c2, 3);
    $terbobot_c3 = round($normalized_c3 * $bobot_c3, 3);

    $total = $terbobot_c1 + $terbobot_c2 + $terbobot_c3;

    $hasil_akhir[] = [
        'id_alternatif' => $data_row['id_alternatif'],
        'nama_alternatif' => $data_row['nama_alternatif'],
        'max' => round($total, 3),
    ];
}

// Urutkan berdasarkan total nilai tertinggi ke terendah
usort($hasil_akhir, fn($a, $b) => $b['max'] <=> $a['max']);
$highest_alternatif = !empty($hasil_akhir) ? $hasil_akhir[0]['nama_alternatif'] : null;

// Kesimpulan
if ($alternatif_count === 0) {
    $summary = "Kesimpulan: Tidak ada data untuk ditampilkan.";
} else {
    // Ambil data dari alternatif untuk level dan involvement
    $selected_alternatif = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM alternatif WHERE nama_alternatif = '$highest_alternatif'"));

    $level_id = $selected_alternatif['c2']; // Ambil nilai level
    $involvement_id = $selected_alternatif['c3']; // Ambil nilai involvement
    $category = $selected_alternatif['nama_alternatif']; // Ambil kategori

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

    // Data pekerjaan yang diberikan
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

    // Cari job titles yang sesuai
    $job_recommendation = "Belum ada lowongan pekerjaan dengan kriteria tersebut.";
    foreach ($jobs_data as $job) {
        if ($job['category'] === $category && $job['involvement'] === $level_text && $job['level'] === $involvement_text) {
            $job_titles = implode(', ', $job['job_titles']);
            $job_recommendation = $job_titles;
            break;
        }
    }

    $summary = "Kesimpulan: Berdasarkan hasil perhitungan menggunakan metode MOORA, dari jumlah alternatif ($alternatif_count), dapat dilihat bahwa '$highest_alternatif' memiliki nilai terbesar. Dengan kata lain, $highest_alternatif terpilih sebagai Bidang Keahlian IT tujuan. Maka dapat disimpulkan dari kategori '$category', jenis pekerjaan '$involvement_text', dan level '$level_text', maka rekomendasi pekerjaan nya adalah: $job_recommendation.";
}

if (isset($_POST['hapus_alternatif'])) {
    mysqli_query($con, "DELETE FROM alternatif");
    header("Location: laporan.php");
    exit;
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <style>
    body {
        background-color: #ffffff;
    }

    .alert-info {
        background-color: #B0C4DE;
        color: #003366;
    }

    .table-striped {
        background-color: #E6F7FF;
    }

    @media print {
        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>

    <title>PERHITUNGAN</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #003366;">
        <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" href="index.php"><b>Home</b></a>
                <a class="nav-link" href="data_kriteria.php"><b>Data Kriteria</b></a>
                <a class="nav-link" href="data_cafe.php"><b>Perhitungan</b></a>
                <a class="nav-link" href="laporan.php"><b>Laporan</b></a>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../logout.php"><b>Logout</b><img src="../img/logout.png" width="30"></a>
            </div>
        </div>
    </nav>
    <br>
    <div class="container bg-light shadow p-3 mb-5">
        <div class="alert alert-info">
            <center><b>DATA AWAL</b></center>
        </div>

        <div class="table-responsive p-4">
            <table class="table table-striped shadow">
                <tr class="bg-info">
                    <th width="150">Id Alternatif</th>
                    <th>Nama Alternatif</th>
                    <th>Score Skill (C1)</th>
                    <th>Level (C2)</th>
                    <th>Involvement (C3)</th>
                </tr>

                <?php
                // Ambil semua data alternatif
                $data_alternatif = mysqli_query($con, "SELECT * FROM alternatif");
                while ($data_row = mysqli_fetch_assoc($data_alternatif)) {
                ?>
                <tr>
                    <td><?= $data_row['id_alternatif']; ?></td>
                    <td><?= $data_row['nama_alternatif']; ?></td>
                    <td><?= $data_row['c1']; ?></td>
                    <td><?= $data_row['c2']; ?></td>
                    <td><?= $data_row['c3']; ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>

  <br><br>
    <div class="alert alert-info">
        <center><b>DATA TRANSFORMASI</b></center>
    </div>

    <div class="table-responsive p-4">
        <table class="table table-striped shadow">
            <tr class="bg-info">
                <th width="150">Id Alternatif</th>
                <th>Nama Alternatif</th>
                <th>Score Skill (Transformed)</th>
                <th>Level (Transformed)</th>
                <th>Involvement (Transformed)</th>
            </tr>

            <?php
            // Reset cursor untuk ambil data lagi
            mysqli_data_seek($data_alternatif, 0);
            while ($data_row = mysqli_fetch_assoc($data_alternatif)) {
                // Transform values
                $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);
                $level_value = $data_row['c2']; // Ambil nilai asli untuk Level
                $involvement_value = $data_row['c3']; // Ambil nilai asli untuk Involvement
            ?>
            <tr>
                <td><?= $data_row['id_alternatif']; ?></td>
                <td><?= $data_row['nama_alternatif']; ?></td>
                <td><?= $score_skill_value; ?></td>
                <td><?= $level_value; ?></td>
                <td><?= $involvement_value; ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

<br><br>
<div class="alert alert-info">
    <center><b>NORMALISASI</b></center>
</div>

<div class="table-responsive p-4">
    <table class="table table-striped shadow">
        <tr class="bg-info">
            <th width="150">Id Alternatif</th>
            <th>Nama Alternatif</th>
            <th>score_skill (C1)</th>
            <th>level (C2)</th>
            <th>involvement (C3)</th>
        </tr>

        <?php
        $pembagi1 = 0;
        $pembagi2 = 0;
        $pembagi3 = 0;
        $transformed_values = []; // Inisialisasi array untuk menyimpan nilai yang ditransformasi

        // Ambil semua data alternatif
        $data_alternatif = mysqli_query($con, "SELECT * FROM alternatif");
        while ($data_row = mysqli_fetch_assoc($data_alternatif)) {
            $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);
            
            // Simpan nilai yang ditransformasi
            $transformed_values[$data_row['id_alternatif']] = [
                'nama_alternatif' => $data_row['nama_alternatif'],
                'score_skill' => $score_skill_value,
            ];

            // Hitung pembagi
            $pembagi1 += pow($score_skill_value, 2);
            $pembagi2 += pow($data_row['c2'], 2); // Menggunakan nilai asli untuk c2
            $pembagi3 += pow($data_row['c3'], 2); // Menggunakan nilai asli untuk c3
        }

        $akar1 = sqrt($pembagi1);
        $akar2 = sqrt($pembagi2);
        $akar3 = sqrt($pembagi3);

        // Reset cursor untuk ambil data lagi
        mysqli_data_seek($data_alternatif, 0);
        while ($data_row = mysqli_fetch_assoc($data_alternatif)) {
            // Ambil nilai transformasi yang sudah disimpan
            $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);
            
            // Hitung nilai normalisasi
            $normalized_score_skill = ($akar1 != 0) ? round($score_skill_value / $akar1, 3) : 0;
            $normalized_level = ($akar2 != 0) ? round($data_row['c2'] / $akar2, 3) : 0;
            $normalized_involvement = ($akar3 != 0) ? round($data_row['c3'] / $akar3, 3) : 0;
            ?>

            <tr>
                <td><?= $data_row['id_alternatif']; ?></td>
                <td><?= $data_row['nama_alternatif']; ?></td>
                <td><?= $normalized_score_skill; ?></td>
                <td><?= $normalized_level; ?></td>
                <td><?= $normalized_involvement; ?></td>
            </tr>

            <?php
        }
        ?>
    </table>
</div>
<br><br>
<div class="alert alert-info">
    <center><b>TERBOBOT</b></center>
</div>

<div class="table-responsive p-4">
    <table class="table table-striped shadow">
        <tr class="bg-info">
            <th width="150">Id Alternatif</th>
            <th>Nama Alternatif</th>
            <th>score_skill (C1)</th>
            <th>level (C2)</th>
            <th>involvement (C3)</th>
        </tr>

        <?php
        // Bobot kriteria
        $bobot_c1 = 0.4;  // score_skill
        $bobot_c2 = 0.25; // level
        $bobot_c3 = 0.35; // involvement

        // Reset cursor untuk ambil data lagi
        mysqli_data_seek($data_alternatif, 0);
        while ($data_row = mysqli_fetch_assoc($data_alternatif)) {
            // Transformasi score skill (c1)
            $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);

            // Normalisasi
            $normalized_c1 = ($akar1 != 0) ? $score_skill_value / $akar1 : 0;
            $normalized_c2 = ($akar2 != 0) ? $data_row['c2'] / $akar2 : 0;
            $normalized_c3 = ($akar3 != 0) ? $data_row['c3'] / $akar3 : 0;

            // Terbobot
            $terbobot_c1 = round($normalized_c1 * $bobot_c1, 3);
            $terbobot_c2 = round($normalized_c2 * $bobot_c2, 3);
            $terbobot_c3 = round($normalized_c3 * $bobot_c3, 3);
        ?>

        <tr>
            <td><?= $data_row['id_alternatif']; ?></td>
            <td><?= $data_row['nama_alternatif']; ?></td>
            <td><?= $terbobot_c1; ?></td>
            <td><?= $terbobot_c2; ?></td>
            <td><?= $terbobot_c3; ?></td>
        </tr>

        <?php
        }
        ?>
    </table>
</div>


<br><br>
<div class="alert alert-info">
    <center><b>HASIL AKHIR</b></center>
</div>
<div class="table-responsive p-4">
    <table class="table table-striped shadow">
        <tr class="bg-info">
            <th width="150">Id Alternatif</th>
            <th>Nama Alternatif</th>
            <th>Min()</th>
            <th>Max(C1+C2+C3)</th>
            <th>MAX-MIN</th>
            <th>Rangking</th>
        </tr>

        <?php
        $bobot_c1 = 0.4;
        $bobot_c2 = 0.25;
        $bobot_c3 = 0.35;

        $hasil_akhir = [];

        mysqli_data_seek($data_alternatif, 0); // Reset cursor
        while ($data_row = mysqli_fetch_assoc($data_alternatif)) {
            // Transformasi dan normalisasi ulang
            $score_skill_value = ($data_row['c1'] >= 5) ? 1 : (($data_row['c1'] >= 3) ? 2 : 3);

            $normalized_c1 = ($akar1 != 0) ? $score_skill_value / $akar1 : 0;
            $normalized_c2 = ($akar2 != 0) ? $data_row['c2'] / $akar2 : 0;
            $normalized_c3 = ($akar3 != 0) ? $data_row['c3'] / $akar3 : 0;

            // Terbobot
            $terbobot_c1 = round($normalized_c1 * $bobot_c1, 3);
            $terbobot_c2 = round($normalized_c2 * $bobot_c2, 3);
            $terbobot_c3 = round($normalized_c3 * $bobot_c3, 3);

            $total = $terbobot_c1 + $terbobot_c2 + $terbobot_c3;

            $hasil_akhir[] = [
                'id_alternatif' => $data_row['id_alternatif'],
                'nama_alternatif' => $data_row['nama_alternatif'],
                'max' => round($total, 3),
            ];
        }

        // Urutkan berdasarkan total nilai tertinggi ke terendah
        usort($hasil_akhir, function($a, $b) {
            return $b['max'] <=> $a['max'];
        });

        // Tampilkan hasil dengan ranking
        foreach ($hasil_akhir as $i => $row) {
        ?>
            <tr>
                <td><?= $row['id_alternatif']; ?></td>
                <td><?= $row['nama_alternatif']; ?></td>
                <td>0</td> <!-- Min selalu 0 karena semua kriteria benefit -->
                <td><?= $row['max']; ?></td>
                <td><?= $row['max']; ?> <!-- MAX - MIN = MAX karena MIN = 0 --></td>
                <td><?= $i + 1; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>
        <div class="alert alert-info">
            <p><?= $summary; ?></p>
        </div>

            <div class="text-center no-print">
    <form method="POST" action="">
        <button type="submit" name="simpan" class="btn btn-success mt-3">Simpan Ke Laporan</button>
    </form>
</div>

                    <div class="no-print">
            <form method="POST" action="">
    <button type="submit" name="hapus_alternatif" class="btn btn-danger" style="background-color: #c0392b; color: white; margin-bottom: 15px;">Kembali (Hapus Data Alternatif)</button>
</form>

            <button onclick="window.print()" class="btn btn-primary" style="background-color: #003366; color: white; margin-bottom: 15px;">Cetak</button>
        </div>
        </div>
    </div>

    <footer>
        <div class="col-md-12 bg-primary">
            <div class="copyright">
                <strong><span>© 2025 Sistem Pendukung Keputusan Rekomendasi Pekerjaan</span></strong>
            </div>
        </div>
    </footer>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>