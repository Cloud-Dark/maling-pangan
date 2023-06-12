<?php

// Database connection settings
$host = "localhost";
$port = "5432";
$dbname = "badanpangantabel";
$user = "postgres";
$password = "root";

$listkabkotapath = file_get_contents('kode_daerah.json');
$parseJson = json_decode($listkabkotapath);

$connection = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$connection) {
    die('Could not connect to the database');
}

// Date Format 30-05-2023/31-05-2023
$date_now = date("d-m-Y");
$datea = $argv[1];
$date_yesterday = date("d-m-Y", strtotime("-1 days"));

// // produsen kabkota
foreach ($parseJson->data as $kabkota) {
    $json_produsen = "https://panelharga.badanpangan.go.id/data/kabkota-range-by-levelharga/" . $kabkota->id . "/1/" . $datea . "/" . $date_now;
    $jsonDataFetch = file_get_contents($json_produsen);

    if ($jsonDataFetch === false) {
        echo "Error fetching data from URL";
        exit;
    }

    // Convert JSON string to PHP array
    $data = json_decode($jsonDataFetch, true);
    if (!$data) {
        die('Error parsing JSON');
    }

    foreach ($data['data'] as $item) {
        $kodeProvinsi = 11;
        $namaProvinsi = "Aceh";
        $kodeKabupatenKota = $kabkota->kode_bps;
        $namaKabupatenKota = $kabkota->nama;
        $komoditas = $item['name'];
        foreach ($item['by_date'] as $entry) {
            $tanggal = $entry['date'];
            $geomeans = $entry['geomean'];

            if ($geomeans != "-") {
                $queryProdusen = "INSERT INTO fe_kabkota_produsen (kode_provinsi, nama_provinsi, kode_kabupaten_kota, nama_kabupaten_kota, tanggal, komoditas_pangan, harga_pangan)
                VALUES ($1, $2, $3, $4, $5, $6, $7)
                ON CONFLICT ON CONSTRAINT fe_kabkota_produsen_unique DO NOTHING";

                $resultProdusen = pg_query_params($connection, $queryProdusen, [$kodeProvinsi, $namaProvinsi, $kodeKabupatenKota, $namaKabupatenKota, $tanggal, $komoditas, $geomeans]);
                if (!$resultProdusen) {
                    die('Error inserting data into the database');
                }
                echo "Data FE Produsen inserted successfully by date " . $tanggal . $namaKabupatenKota . $komoditas . "\n";
            }
        }
    }
}

// // eceran kabkota
foreach ($parseJson->data as $kabkota) {
    $json_eceran = "https://panelharga.badanpangan.go.id/data/kabkota-range-by-levelharga/" . $kabkota->id . "/3/" . $date_yesterday . "/" . $date_now;

    $jsonDataFetch = file_get_contents($json_eceran);

    if ($jsonDataFetch === false) {
        echo "Error fetching data from URL";
        exit;
    }

    // Convert JSON string to PHP array
    $data = json_decode($jsonDataFetch, true);
    if (!$data) {
        die('Error parsing JSON');
    }

    foreach ($data['data'] as $item) {
        $kodeProvinsi = 11;
        $namaProvinsi = "Aceh";
        $kodeKabupatenKota = $kabkota->kode_bps;
        $namaKabupatenKota = $kabkota->nama;
        $komoditas = $item['name'];
        foreach ($item['by_date'] as $entry) {
            $tanggal = $entry['date'];
            $geomeans = $entry['geomean'];

            if ($geomeans != "-") {
                $queryEceran = "INSERT INTO fe_kabkota_eceran (kode_provinsi, nama_provinsi, kode_kabupaten_kota, nama_kabupaten_kota, tanggal, komoditas_pangan, harga_pangan)
                VALUES ($1, $2, $3, $4, $5, $6, $7)
                ON CONFLICT ON CONSTRAINT fe_kabkota_eceran_unique DO NOTHING";

                $resultEceran = pg_query_params($connection, $queryEceran, [$kodeProvinsi, $namaProvinsi, $kodeKabupatenKota, $namaKabupatenKota, $tanggal, $komoditas, $geomeans]);
                if (!$resultEceran) {
                    die('Error inserting data into the database');
                }
            }
            echo "Data FE Eceran inserted successfully by date" . $tanggal . "\n";
        }
    }
}


// produsen provinsi
$json_produsen_provinsi = "https://panelharga.badanpangan.go.id/data/provinsi-range-by-levelharga/1/1/" . $datea . "/" . $date_now;

$jsonDataFetch = file_get_contents($json_produsen_provinsi);

if ($jsonDataFetch === false) {
    echo "Error fetching data from URL";
    exit;
}

$data = json_decode($jsonDataFetch, true);


if (isset($data['data']) && is_array($data['data'])) {
    
    foreach ($data['data'] as $item) {
        $kodeProvinsi = 11;
        $namaProvinsi = "Aceh";
        $komoditas = $item['name'];
        foreach ($item['by_date'] as $entry) {
            $tanggal = $entry['date'];
            $geomeans = $entry['geomean'];
            if ($geomeans != "-") {
                $queryProdusen = "INSERT INTO fe_provinsi_produsen (kode_provinsi, nama_provinsi, tanggal, komoditas_pangan, harga_pangan)
                VALUES ($1, $2, $3, $4, $5)
                ON CONFLICT ON CONSTRAINT fe_provinsi_produsen_unique DO NOTHING";

                $resultProdusen = pg_query_params($connection, $queryProdusen, [$kodeProvinsi, $namaProvinsi, $tanggal, $komoditas, $geomeans]);
                if (!$resultProdusen) {
                    die('Error inserting data into the database');
                }
                echo "Data FE Produsen inserted successfully. \n";
            }
        }
    }
} else {
    echo "Invalid data format or missing 'data' key in JSON";
}

// // eceran
$json_eceran_provinsi = "https://panelharga.badanpangan.go.id/data/provinsi-range-by-levelharga/1/1/". $datea . "/" .$date_now;
$jsonDataFetch = file_get_contents($json_eceran_provinsi);

if ($jsonDataFetch === false) {
    echo "Error fetching data from URL";
    exit;
}

$data = json_decode($jsonDataFetch, true);

if (isset($data['data']) && is_array($data['data'])) {
    foreach ($data['data'] as $item) {
        $kodeProvinsi = 11;
        $namaProvinsi = "Aceh";
        $komoditas = $item['name'];
        foreach ($item['by_date'] as $entry) {
            $tanggal = $entry['date'];
            $geomeans = $entry['geomean'];
            if ($geomeans != "-") {
                $queryEceran = "INSERT INTO fe_provinsi_eceran (kode_provinsi, nama_provinsi, tanggal, komoditas_pangan, harga_pangan)
                VALUES ($1, $2, $3, $4, $5)
                ON CONFLICT ON CONSTRAINT fe_provinsi_eceran_unique DO NOTHING";

                $resultEceran = pg_query_params($connection, $queryEceran, [$kodeProvinsi, $namaProvinsi, $tanggal, $komoditas, $geomeans]);
                if (!$resultEceran) {
                    die('Error inserting data into the database');
                }
                echo "Data FE Eceran inserted successfully.\n";
            }
    }
    }
} else {
    echo "Invalid data format or missing 'data' key in JSON";
}

// Close the database connection
pg_close($connection);
?>
