<?php

include "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    $nama_sppg = trim($_POST['nama_sppg']);
    $kecamatan = trim($_POST['kecamatan']);
    $kabupaten = trim($_POST['kabupaten']);

    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    // Validasi data
    if (
        $id === false || $id === null || $id < 1 ||
        $nama_sppg == "" ||
        $kecamatan == "" ||
        $kabupaten == "" ||
        !is_numeric($longitude) ||
        !is_numeric($latitude) ||
        $longitude < -180 || $longitude > 180 ||
        $latitude < -90 || $latitude > 90
    ) {
        die("Data tidak valid. Periksa kembali input.");
    }

    // Query update data
    $stmt = $conn->prepare(
        "UPDATE sppg SET
        nama_sppg = ?,
        kecamatan = ?,
        kabupaten = ?,
        longitude = ?,
        latitude = ?
        WHERE id = ?"
    );

    $stmt->bind_param(
        "ssssdi",
        $nama_sppg,
        $kecamatan,
        $kabupaten,
        $longitude,
        $latitude,
        $id
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
