<?php

$env = parse_ini_file(".env");


// Database connection settings
$host = $env["DB_HOST"];
$port = $env["DB_PORT"];
$dbname = $env["DB_NAME"]; 
$user = $env["DB_USER"];
$password = $env["DB_PASSWORD"];

$connection = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$connection) {
    die('Could not connect to the database');
}


    $json_produsen = "https://panelharga.badanpangan.go.id/api/hargaharianprovinsi/1/1";

    $apiKey = $env["API_KEY"]; // Replace with your actual API key

    $ch = curl_init($json_produsen);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'apikey: ' . $apiKey
    ]);

    $jsonDataFetch = curl_exec($ch);


    if ($jsonDataFetch === false) {
        echo "Error fetching data from URL: " . curl_error($ch);
        exit;
    }

    curl_close($ch);

    $data = json_decode($jsonDataFetch, true);


    if (isset($data['data']) && is_array($data['data'])) {
        foreach ($data['data'] as $item) {
            $kodeProvinsi = 11;
            $namaProvinsi = "Aceh";
            $komoditas = $item['name'];
            $tanggal = date("Y-m-d");
            
            $geomeans = $item['geomean'];

            if($geomeans != "-"){

                $queryProdusen = "INSERT INTO api_provinsi_produsen (kode_provinsi, nama_provinsi, tanggal, komoditas_pangan, harga_pangan) VALUES ($1, $2, $3, $4, $5) on conflict on constraint api_provinsi_produsen_unique do nothing";
                $resultProdusen = pg_query_params($connection, $queryProdusen,[$kodeProvinsi, $namaProvinsi, $tanggal, $komoditas, $geomeans]);
                if (!$resultProdusen) {
                    die('Error inserting data into the database');
                }
                echo "Data API Provinsi Produsen inserted successfully. \n";
            }

        }
    } else {
        echo "Invalid data format or missing 'data' key in JSON";
    }



    // eceran
    $json_eceran = "https://panelharga.badanpangan.go.id/api/hargaharianprovinsi/3/1";


    $ch = curl_init($json_eceran);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'apikey: ' . $apiKey
    ]);

    $jsonDataFetch = curl_exec($ch);


    if ($jsonDataFetch === false) {
        echo "Error fetching data from URL: " . curl_error($ch);
        exit;
    }

    curl_close($ch);

    $data = json_decode($jsonDataFetch, true);


    if (isset($data['data']) && is_array($data['data'])) {
        foreach ($data['data'] as $item) {
            $kodeProvinsi = 11;
            $namaProvinsi = "Aceh";
            $komoditas = $item['name'];            
            $geomeans = $item['geomean'];
            if($geomeans != "-"){
                $queryEceran = "INSERT INTO api_provinsi_eceran (kode_provinsi, nama_provinsi, tanggal, komoditas_pangan, harga_pangan) VALUES ($1, $2, $3, $4, $5) on conflict on constraint api_provinsi_eceran_unique do nothing";
                $resultEceran = pg_query_params($connection, $queryEceran,[$kodeProvinsi, $namaProvinsi, $tanggal, $komoditas, $geomeans]);
                if (!$resultEceran) {
                    die('Error inserting data into the database');
                }
                echo "Data API provinsi Eceran inserted successfully.\n";
            }

        }
    } else {
        echo "Invalid data format or missing 'data' key in JSON";
    }


    //kabupaten kota today
    $listkabkotapath = file_get_contents('kode_daerah.json');
    $jsonKabKota = json_decode($listkabkotapath);
    
    foreach($jsonKabKota->data as $kabkota){
        $json_produsen = "https://panelharga.badanpangan.go.id/api/hargahariankabkota/1/" . $kabkota->id;

        $apiKey = '294543cfa1ae88aa6e2cb83213707d21b03892c7'; // Replace with your actual API key
    
        $ch = curl_init($json_produsen);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'apikey: ' . $apiKey
        ]);
    
        $jsonDataFetch = curl_exec($ch);
    
    
        if ($jsonDataFetch === false) {
            echo "Error fetching data from URL: " . curl_error($ch);
            exit;
        }
    
        curl_close($ch);
    
        $data = json_decode($jsonDataFetch, true);
    
    
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                $kodeProvinsi = 11;
                $namaProvinsi = "Aceh";
                $kodeKabupatenKotaBps = $kabkota->kode_bps;
                $kodeKabupatenKotaKemendagri=$kabkota->kode_kemendagri;
                $namaKabupatenKota = $kabkota->nama;
                $komoditas = $item['name'];
                $tanggal = date("Y-m-d");
                
                $geomeans = $item['geomean'];
    
                if($geomeans != "-"){
    
                    $queryProdusen = "INSERT INTO api_kabkota_produsen (kode_provinsi, nama_provinsi,kode_kabupaten_kota_bps,kode_kabupaten_kota_kemendagri, nama_kabupaten_kota, tanggal, komoditas_pangan, harga_pangan) VALUES ($1, $2, $3, $4, $5, $6, $7, $8) on conflict on constraint api_kabkota_produsen_unique do nothing";
                    $resultProdusen = pg_query_params($connection, $queryProdusen,[$kodeProvinsi, $namaProvinsi, $kodeKabupatenKotaBps, $kodeKabupatenKotaKemendagri, $namaKabupatenKota, $tanggal, $komoditas, $geomeans]);
                    if (!$resultProdusen) {
                        die('Error inserting data into the database');
                    }
                    echo "Data API KabKota Produsen inserted successfully. \n";
                }
    
            }
        } else {
            echo "Invalid data format or missing 'data' key in JSON";
        }
    
    
    
        // eceran
        $json_eceran = "https://panelharga.badanpangan.go.id/api/hargaharianprovinsi/3/" .$kabkota->id;
    
    
        $ch = curl_init($json_eceran);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'apikey: ' . $apiKey
        ]);
    
        $jsonDataFetch = curl_exec($ch);
    
    
        if ($jsonDataFetch === false) {
            echo "Error fetching data from URL: " . curl_error($ch);
            exit;
        }
    
        curl_close($ch);
    
        $data = json_decode($jsonDataFetch, true);
    
    
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                $kodeProvinsi = 11;
                $namaProvinsi = "Aceh";
                $kodeKabupatenKotaBps = $kabkota->kode_bps;
                $kodeKabupatenKotaKemendagri=$kabkota->kode_kemendagri;
                $namaKabupatenKota = $kabkota->nama;
                $komoditas = $item['name'];
                $geomeans = $item['geomean'];
                if($geomeans != "-"){
                    $queryEceran = "INSERT INTO api_kabkota_eceran (kode_provinsi, nama_provinsi,kode_kabupaten_kota_bps,kode_kabupaten_kota_kemendagri, nama_kabupaten_kota, tanggal, komoditas_pangan, harga_pangan) VALUES ($1, $2, $3, $4, $5, $6, $7, $8) on conflict on constraint api_kabkota_eceran_unique do nothing";
                    $resultEceran = pg_query_params($connection, $queryEceran,[$kodeProvinsi, $namaProvinsi,$kodeKabupatenKotaBps, $kodeKabupatenKotaKemendagri, $namaKabupatenKota, $tanggal, $komoditas, $geomeans]);
                    if (!$resultEceran) {
                        die('Error inserting data into the database');
                    }
                    echo "Data API KabKota Eceran inserted successfully.\n";
                }
    
            }
        } else {
            echo "Invalid data format or missing 'data' key in JSON";
        }
    }


pg_close($connection);
?>
