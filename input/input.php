<?php

include "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_sppg = trim($_POST['nama_sppg']);
    $kecamatan = trim($_POST['kecamatan']);
    $kabupaten = trim($_POST['kabupaten']);

    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    // Validasi koordinat
    if (
        !is_numeric($longitude) ||
        !is_numeric($latitude) ||
        $longitude < -180 || $longitude > 180 ||
        $latitude < -90 || $latitude > 90 ||
        $nama_sppg == "" ||
        $kecamatan == "" ||
        $kabupaten == ""
    ) {
        die("Data tidak valid. Periksa kembali input.");
    }

    // Query input data
    $stmt = $conn->prepare(
        "INSERT INTO sppg
        (nama_sppg, kecamatan, kabupaten, longitude, latitude)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssd",
        $nama_sppg,
        $kecamatan,
        $kabupaten,
        $longitude,
        $latitude
    );

    if ($stmt->execute()) {

        header("Location: ../index.php");
        exit();

    } else {

        echo "Error: " . $stmt->error;

    }

    $stmt->close();
}

$conn->close();

?>
