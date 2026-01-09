<?php

$con = mysqli_connect("localhost", "root", "", "skripsi");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

function login($data)
{
    global $con;

    $username = $data['username'];
    $password = $data['password'];

    $login = mysqli_query($con, "SELECT * FROM login WHERE username = '$username' AND password = '$password'");

    if (!$login) {
        return false;  // Query failed
    }

    return mysqli_affected_rows($con);
}

function query($query)
{
    global $con;

    $data = mysqli_query($con, $query);

    if (!$data) {
        return [];  // Query failed
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $rows[] = $row;
    }
    return $rows;
}

function tampilkriteria($query)
{
    global $con;

    $data = mysqli_query($con, $query);

    if (!$data) {
        return [];  // Query failed
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $rows[] = $row;
    }
    return $rows;
}

function edit_kriteria($data)
{
    global $con;

    $id_kriteria = $data['id_kriteria'];
    $kriteria = $data['kriteria'];
    $bobot = $data['bobot'];
    $type = $data['type'];

    mysqli_query($con, "UPDATE kriteria SET 
        kriteria = '$kriteria',
        bobot = '$bobot',
        type = '$type'
        WHERE id_kriteria = '$id_kriteria'
        ");

    return mysqli_affected_rows($con);
}

function tampilcafe($query)
{
    global $con;

    $data = mysqli_query($con, $query);

    if (!$data) {
        return [];  // Query failed
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $rows[] = $row;
    }
    return $rows;
}

function connect_db() {
    $conn = mysqli_connect("localhost", "root", "", "skripsi");
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}
// Fungsi untuk menambahkan data alternatif
function tambah_data_cafe($data) {
    global $con;
    $id_alternatif = $data['id_alternatif'];
    $nama_alternatif = $data['nama_alternatif'];
    $c1 = $data['c1'];
    $c2 = $data['c2'];
    $c3 = $data['c3'];
    $nama_skill = $data['nama_skill']; // Ambil nama skill

    $query = "INSERT INTO alternatif (id_alternatif, nama_alternatif, c1, c2, c3, nama_skill) VALUES ('$id_alternatif', '$nama_alternatif', '$c1', '$c2', '$c3', '$nama_skill')";
    mysqli_query($con, $query);
    return mysqli_affected_rows($con);
}
function edit_cafe($data)
{
    global $con;

    $id_alternatif = $data['id_alternatif'];
    $nama_alternatif = $data['nama_alternatif'];
    $c1 = $data['c1'];
    $c2 = $data['c2'];
    $c3 = $data['c3'];

    mysqli_query($con, "UPDATE alternatif SET
        nama_alternatif = '$nama_alternatif',
        c1 = '$c1',
        c2 = '$c2',
        c3 = '$c3'
        WHERE id_alternatif = '$id_alternatif'
        ");

    return mysqli_affected_rows($con);
}

function hapus_cafe($id_alternatif)
{
    global $con;

    mysqli_query($con, "DELETE FROM alternatif WHERE id_alternatif = '$id_alternatif' ");
    
    return mysqli_affected_rows($con);
}
function hapus_laporan($id_laporan)
{
    global $con;

    $query = "DELETE FROM laporan WHERE id_laporan = '$id_laporan'";
    $result = mysqli_query($con, $query);
    
    if (!$result) {
        die("Query error: " . mysqli_error($con));
    }
    
    return mysqli_affected_rows($con);
}


?>