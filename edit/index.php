
<!DOCTYPE html>
<html>
<body>

<h2>Form Edit</h2>

<?php

include "../db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM sppg WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<form action='edit.php' onsubmit='return validateForm()' method='post'>";

        echo "<input type='hidden' name='id' value='".$row['id']."'>";

        echo "<label for='nama_sppg'>Nama SPPG:</label><br>";
        echo "<input type='text' id='nama_sppg' name='nama_sppg' value='".htmlspecialchars($row['nama_sppg'], ENT_QUOTES)."'><br>";

        echo "<label for='kecamatan'>Kecamatan:</label><br>";
        echo "<input type='text' id='kecamatan' name='kecamatan' value='".htmlspecialchars($row['kecamatan'], ENT_QUOTES)."'><br>";

        echo "<label for='kabupaten'>Kabupaten:</label><br>";
        echo "<input type='text' id='kabupaten' name='kabupaten' value='".htmlspecialchars($row['kabupaten'], ENT_QUOTES)."'><br>";

        echo "<label for='longitude'>Longitude:</label><br>";
        echo "<input type='text' id='longitude' name='longitude' value='".$row['longitude']."'><br>";

        echo "<label for='latitude'>Latitude:</label><br>";
        echo "<input type='text' id='latitude' name='latitude' value='".$row['latitude']."'><br><br>";

        echo "<input type='submit' value='Submit'>";

        echo "</form>";
    }
}

$conn->close();

?>

<p id="informasi"></p>

<a href="../index.php">Kembali</a>

<script>
function validateForm() {

    let longitude = document.getElementById("longitude").value;
    let latitude = document.getElementById("latitude").value;
    let text = "";

    if (isNaN(longitude) || longitude < -180 || longitude > 180) {
        text = "Data longitude harus berupa angka antara -180 sampai 180.";
    }
    else if (isNaN(latitude) || latitude < -90 || latitude > 90) {
        text = "Data latitude harus berupa angka antara -90 sampai 90.";
    }

    if (text != "") {
        document.getElementById("informasi").innerHTML = text;
        return false;
    }

    return true;
}
</script>

</body>
</html>
