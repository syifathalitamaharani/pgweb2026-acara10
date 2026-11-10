
<?php
// Menghubungkan PHP dengan database
include 'db.php';

// Mengambil seluruh data SPPG
$sql = "SELECT * FROM sppg";
$result = $conn->query($sql);

// Menampung data SPPG
$data_sppg = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_sppg[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data dan Persebaran SPPG Sleman</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        html, body {
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #dddddd;
        }

        h2 {
            margin-top: 25px;
        }

        #map {
            height: 500px;
            width: 100%;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <h2>Data SPPG Kabupaten Sleman</h2>

    <a href="input/index.html">Tambah Data SPPG</a>

    <!-- Tabel Data SPPG -->
    <table>
        <tr>
            <th>ID</th>
            <th>Nama SPPG</th>
            <th>Kecamatan</th>
            <th>Kabupaten</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($data_sppg as $data) { ?>
        <tr>
            <td><?php echo htmlspecialchars($data['id']); ?></td>
            <td><?php echo htmlspecialchars($data['nama_sppg']); ?></td>
            <td><?php echo htmlspecialchars($data['kecamatan']); ?></td>
            <td><?php echo htmlspecialchars($data['kabupaten']); ?></td>
            <td><?php echo htmlspecialchars($data['longitude']); ?></td>
            <td><?php echo htmlspecialchars($data['latitude']); ?></td>
            <td>
                <a href="edit/index.php?id=<?php echo $data['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $data['id']; ?>"
                onclick="return confirm('Yakin ingin menghapus data ini?')">
                    Hapus
                </a>
            </td>
        </tr>
        <?php } ?>

    </table>

    <h2>Peta Persebaran SPPG Kabupaten Sleman</h2>

    <!-- Peta Leaflet -->
    <div id="map"></div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>

        // Mengambil data SPPG dari PHP
        var dataSPPG = <?php
            echo json_encode(
                $data_sppg,
                JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_QUOT |
                JSON_HEX_AMP
            );
        ?>;

        // Inisialisasi peta
        var map = L.map('map').setView([-7.710888, 110.400000], 12);

        // Basemap Esri Imagery
        var basemap1 = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                attribution: 'Tiles &copy; Esri'
            }
        );

        // Basemap Google Street
        var basemap2 = L.tileLayer(
            'https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
            {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: 'Map data &copy; Google'
            }
        );

        // Basemap OpenStreetMap
        var basemap3 = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }
        );

        // Menambahkan OpenStreetMap sebagai basemap awal
        basemap3.addTo(map);

        // Membuat layer marker SPPG
        var markerSPPG = L.layerGroup();

        // Menampung koordinat marker
        var koordinatSPPG = [];

        // Membuat marker berdasarkan data database
        dataSPPG.forEach(function(data) {

            var latitude = Number(data.latitude);
            var longitude = Number(data.longitude);

            // Memastikan koordinat valid
            if (
                Number.isFinite(latitude) &&
                Number.isFinite(longitude) &&
                latitude >= -90 && latitude <= 90 &&
                longitude >= -180 && longitude <= 180
            ) {

                // Membuat marker
                var marker = L.marker([latitude, longitude]);

                // Membuat informasi popup
                var popupContent = document.createElement('div');

                var nama = document.createElement('b');
                nama.textContent = data.nama_sppg;

                var kecamatan = document.createElement('p');
                kecamatan.textContent =
                    'Kecamatan: ' + data.kecamatan;

                var kabupaten = document.createElement('p');
                kabupaten.textContent =
                    'Kabupaten: ' + data.kabupaten;

                var koordinat = document.createElement('p');
                koordinat.textContent =
                    'Koordinat: ' + latitude + ', ' + longitude;

                // Menambahkan informasi ke popup
                popupContent.appendChild(nama);
                popupContent.appendChild(kecamatan);
                popupContent.appendChild(kabupaten);
                popupContent.appendChild(koordinat);

                // Menambahkan popup ke marker
                marker.bindPopup(popupContent);

                // Menambahkan marker ke layer
                markerSPPG.addLayer(marker);

                // Menyimpan koordinat marker
                koordinatSPPG.push([latitude, longitude]);
            }
        });

        // Menambahkan marker ke peta
        markerSPPG.addTo(map);

        // Menyesuaikan posisi dan zoom berdasarkan marker
        if (koordinatSPPG.length > 0) {
            map.fitBounds(koordinatSPPG, {
                padding: [30, 30]
            });
        }

        // Pengaturan pilihan basemap
        var baseMaps = {
            "Esri Imagery": basemap1,
            "Google Street": basemap2,
            "OpenStreetMap": basemap3
        };

        // Pengaturan overlay
        var overlayMaps = {
            "Persebaran SPPG": markerSPPG
        };

        // Menambahkan kontrol layer
        L.control.layers(
            baseMaps,
            overlayMaps,
            {
                collapsed: false
            }
        ).addTo(map);

        // Menambahkan skala peta
        L.control.scale({
            maxWidth: 150,
            position: 'bottomright'
        }).addTo(map);

    </script>

</body>
</html>

<?php
$conn->close();
?>
