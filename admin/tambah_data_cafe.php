<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("Location: ../index.php?pesan=logindahulu");
    exit;
}

// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "skripsi");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fungsi untuk menambahkan data ke database
function tambah_data_cafe($data) {
    global $con;

    $id_alternatif = $data['id_alternatif'];
    $nama_alternatif = $data['nama_alternatif'];
    $nama_skill = $data['nama_skill'];
    $c1 = $data['c1'];
    $c2 = $data['c2'];
    $c3 = $data['c3'];
    $tahun = $data['tahun'];

    $query = "INSERT INTO alternatif (id_alternatif, nama_alternatif, nama_skill, c1, c2, c3, tahun) 
              VALUES ('$id_alternatif', '$nama_alternatif', '$nama_skill', '$c1', '$c2', '$c3', '$tahun')";

    // Eksekusi query dan cek jika ada error
    if (!mysqli_query($con, $query)) {
        echo "Error: " . mysqli_error($con);
        return 0; // Mengembalikan 0 jika ada error
    }
    
    return mysqli_affected_rows($con); // Mengembalikan jumlah baris yang terpengaruh
}

// Ambil tahun saat ini
$current_year = date("Y");

// Fetch nama_alternatif dari tabel skills untuk select options
$query_nama_alternatif = "
    SELECT DISTINCT alternatif 
    FROM skills 
    WHERE alternatif NOT IN (SELECT nama_alternatif FROM alternatif)";
$result_nama_alternatif = mysqli_query($con, $query_nama_alternatif);

// Ambil ID alternatif terakhir untuk mengisi otomatis
$query_last_id = "SELECT MAX(id_alternatif) as last_id FROM alternatif";
$result_last_id = mysqli_query($con, $query_last_id);
$row_last_id = mysqli_fetch_assoc($result_last_id);
$next_id = $row_last_id['last_id'] + 1; // ID berikutnya

// Handle form submission
if (isset($_POST['simpan'])) {
    // Ambil skill yang dipilih
    $selected_skills = isset($_POST['selected_skills']) ? explode(',', $_POST['selected_skills']) : [];
    $score_skill = count($selected_skills); // Hitung jumlah skill yang dipilih

    // Tambahkan data ke database
    $_POST['c1'] = $score_skill; // Masukkan jumlah skill ke dalam field c1

    // Gabungkan skill yang dipilih menjadi string
    $nama_skill = implode(',', $selected_skills);
    $_POST['nama_skill'] = $nama_skill; // Simpan nama skill ke dalam field nama_skill

    // Tambahkan tahun yang otomatis diisi
    $_POST['tahun'] = $current_year; // Menyimpan tahun saat ini

    if (tambah_data_cafe($_POST) > 0) {
        echo "<script>
        alert('Data Berhasil Di Tambah');
        document.location.href='data_cafe.php';
        </script>";
    } else {
        echo "<script>
        alert('Data Gagal Di Tambah');
        </script>";
    }
}

// Ambil data skills berdasarkan alternatif yang dipilih
$skills = [];
$selected_alternatif = '';
$selected_skills = [];
if (isset($_POST['nama_alternatif'])) {
    $selected_alternatif = $_POST['nama_alternatif'];
    $query_skills = "SELECT skill_name FROM skills WHERE alternatif = '$selected_alternatif'";
    $result_skills = mysqli_query($con, $query_skills);
    
    while ($row = mysqli_fetch_assoc($result_skills)) {
        $skills[] = $row['skill_name'];
    }
}

// Handle skill selection
if (isset($_POST['selected_skills'])) {
    $selected_skills = explode(',', $_POST['selected_skills']); // Simpan skill yang dipilih
} else {
    $selected_skills = []; // Pastikan ini adalah array
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <title>TAMBAH DATA CAFE</title>
    <style>
        body {
            background-color: #003366; /* Biru tua */
            color: #ffffff; /* Putih */
        }
        .container {
            min-height: calc(100vh - 211px);
            background-color: #ffffff; /* Putih */
            padding: 20px;
            border-radius: 10px;
        }
        .col-md-12.bg-primary {
            padding: 8px;
            background-color: #003366 !important; /* Biru tua */
        }
        .copyright {
            text-align: center;
            color: #fff;
        }
        .navbar {
            background-color: #003366; /* Biru tua */
        }
        .navbar-nav a:hover {
            color: #ffffff; /* Putih */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#"><img src="../img/stt.png" width="50"></a>
        <div class="collapse navbar-collapse">
            <div class="navbar-nav" style="margin: 10px;">
                <a class="nav-link" href="index.php"><b>Home</b></a>
                <a class="nav-link" href="data_kriteria.php"><b>Data Kriteria</b></a>
                <a class="nav-link" href="it.php"><b>Data Bidang Keahlian IT</b></a>
                <a class="nav-link" href="data_cafe.php"><b>Perhitungan</b></a>
                <a class="nav-link" href="laporan.php"><b>Laporan</b></a>
            </div>
            <div class="navbar-nav ml-auto" style="margin: 10px;">
                <a class="log nav-link" href="../logout.php"><b>Logout</b><img src="../img/logout.png" width="30"></a>
            </div>
        </div>
    </nav>

<div class="container bg-light shadow p-3 mb-5">
    <h2 class="text-center">TAMBAH DATA ALTERNATIF</h2>

    <form method="post">
        <table class="table">
            <tr>
                <td><label>Id Alternatif</label></td>
                <td><input type="text" name="id_alternatif" class="form-control" value="<?= $next_id ?>" readonly></td>
            </tr>
            <tr>
                <td><label>Nama Alternatif</label></td>
                <td>
                    <select name="nama_alternatif" id="nama_alternatif" onchange="this.form.submit()" class="form-control" required>
                        <option value="">Select Nama Alternatif</option>
                        <?php
                        if ($result_nama_alternatif) {
                            while ($row = mysqli_fetch_assoc($result_nama_alternatif)) {
                                $selected = ($row['alternatif'] === $selected_alternatif) ? 'selected' : '';
                                echo "<option value=\"{$row['alternatif']}\" $selected>{$row['alternatif']}</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Score Skill (C1) - Pilih Skill</label></td>
                <td>
                    <select name="skill" id="skill" class="form-control" onchange="addSkill()">
                        <option value="">Select Skill (Bisa lebih dari 1)</option>
                        <?php
                        foreach ($skills as $skill) {
                            echo "<option value=\"$skill\">$skill</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Selected Skills</label></td>
                <td>
                    <div id="selected-skills">
                        <?php
                        foreach ($selected_skills as $skill) {
                            echo "<span class='badge badge-primary'>$skill <button type='button' onclick='removeSkill(\"$skill\")' class='btn btn-danger btn-sm'>X</button></span> ";
                        }
                        ?>
                    </div>
                    <input type="hidden" name="selected_skills" id="hidden-selected-skills" value="<?php echo implode(',', $selected_skills); ?>">
                </td>
            </tr>
            <tr>
                <td><label>Level (C2)</label></td>
                <td>
                    <select name="c2" class="form-control" required>
                        <option value="">Select Level</option>
                        <option value="2">Full-time</option>
                        <option value="1">Contrak</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Involvement (C3)</label></td>
                <td>
                    <select name="c3" class="form-control" required>
                        <option value="">Select Involvement</option>
                        <option value="1">Entry level</option>
                        <option value="2">Associate</option>
                        <option value="3">Mid-Senior Level</option>
                        <option value="4">Director</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Tahun</label></td>
                <td>
                    <input type="text" name="tahun" class="form-control" value="<?= $current_year ?>" readonly>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                    <a href="data_cafe.php" class="btn btn-danger">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</div>

    <div class="col-md-12 bg-primary">
        <div class="copyright">
            <h6>Copyright&copy; Putri Carellilas Fony</h6>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addSkill() {
            var skillSelect = document.getElementById("skill");
            var selectedSkill = skillSelect.value;
            var selectedSkillsDiv = document.getElementById("selected-skills");
            var hiddenSkillsInput = document.getElementById("hidden-selected-skills");

            if (selectedSkill) {
                // Create a new badge for the selected skill
                var badge = document.createElement("span");
                badge.className = "badge badge-primary";
                badge.innerHTML = selectedSkill + " <button type='button' onclick='removeSkill(\"" + selectedSkill + "\")' class='btn btn-danger btn-sm'>X</button>";
                selectedSkillsDiv.appendChild(badge);

                // Update hidden input
                var currentSkills = hiddenSkillsInput.value ? hiddenSkillsInput.value.split(",") : [];
                currentSkills.push(selectedSkill);
                hiddenSkillsInput.value = currentSkills.join(",");

                // Reset the select
                skillSelect.value = "";
            }
        }

        function removeSkill(skill) {
            var selectedSkillsDiv = document.getElementById("selected-skills");
            var hiddenSkillsInput = document.getElementById("hidden-selected-skills");

            // Remove skill badge
            Array.from(selectedSkillsDiv.children).forEach(function(badge) {
                if (badge.innerText.includes(skill)) {
                    selectedSkillsDiv.removeChild(badge);
                }
            });

            // Update hidden input
            var currentSkills = hiddenSkillsInput.value.split(",").filter(function(s) {
                return s !== skill;
            });
            hiddenSkillsInput.value = currentSkills.join(",");
        }
    </script>
</body>
</html>

<?php
mysqli_close($con);
?>