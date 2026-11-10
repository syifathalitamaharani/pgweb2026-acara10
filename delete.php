<?php

include "db.php";

if (isset($_GET['id'])) {

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $id < 1) {
        die("ID tidak valid.");
    }

    // Query menghapus data
    $stmt = $conn->prepare(
        "DELETE FROM sppg WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: index.php");
        exit();

    } else {

        echo "Error: " . $stmt->error;

    }

    $stmt->close();

} else {

    echo "ID tidak ditemukan.";

}

$conn->close();

?>
