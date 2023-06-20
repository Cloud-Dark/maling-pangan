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


$jsonKomoditas = "https://panelharga.badanpangan.go.id/api/komoditas";
$apiKey = $env["API_KEY"];

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


    if($data === false) {};

?>