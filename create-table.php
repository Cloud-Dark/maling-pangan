<?php
$host = "localhost";
$port = "5432";
$dbname = "badanpangantabel";
$user = "postgres";
$password = "root";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Check connection
if (!$conn) {
    echo "Failed to connect to PostgreSQL: " . pg_last_error();
    exit;
}

// SQL queries to create tables

// tabel fe_provinsi_eceran
$sql_fe_provinsi_eceran = "CREATE TABLE fe_provinsi_eceran (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT fe_provinsi_eceran_unique UNIQUE (kode_provinsi, tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_fe_provinsi_eceran);
echo "Table fe_provinsi_eceran created successfully\n";

// tabel fe_provinsi_produsen
$sql_fe_provinsi_produsen = "CREATE TABLE fe_provinsi_produsen (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT fe_provinsi_produsen_unique UNIQUE (tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_fe_provinsi_produsen);
echo "Table fe_provinsi_produsen created successfully\n";

// table fe_kabkota_eceran
$sql_fe_kabkota_eceran = "CREATE TABLE fe_kabkota_eceran (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    kode_kabupaten_kota INT,
    nama_kabupaten_kota VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT fe_kabkota_eceran_unique UNIQUE (kode_kabupaten_kota, tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_fe_kabkota_eceran);
echo "Table fe_kabkota_eceran created successfully\n";

// tabel fe_kabkota_produsen
$sql_fe_kabkota_produsen = "CREATE TABLE fe_kabkota_produsen (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    kode_kabupaten_kota INT,
    nama_kabupaten_kota VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT fe_kabkota_produsen_unique UNIQUE (tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_fe_kabkota_produsen);
echo "Table fe_kabkota_produsen created successfully\n";

// tabel api_provinsi_eceran
$sql_api_provinsi_eceran = "CREATE TABLE api_provinsi_eceran (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT api_provinsi_eceran_unique UNIQUE (kode_provinsi, tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_api_provinsi_eceran);
echo "Table api_provinsi_eceran created successfully\n";

// tabel api_provinsi_produsen
$sql_api_provinsi_produsen = "CREATE TABLE api_provinsi_produsen (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT api_provinsi_produsen_unique UNIQUE (tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_api_provinsi_produsen);
echo "Table api_provinsi_produsen created successfully\n";

// table api_kabkota_eceran
$sql_api_kabkota_eceran = "CREATE TABLE api_kabkota_eceran (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    kode_kabupaten_kota INT,
    nama_kabupaten_kota VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT api_kabkota_eceran_unique UNIQUE (kode_kabupaten_kota, tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_api_kabkota_eceran);
echo "Table api_kabkota_eceran created successfully\n";

// tabel api_kabkota_produsen
$sql_api_kabkota_produsen = "CREATE TABLE api_kabkota_produsen (
    id SERIAL PRIMARY KEY,
    kode_provinsi INT,
    nama_provinsi VARCHAR(255),
    kode_kabupaten_kota INT,
    nama_kabupaten_kota VARCHAR(255),
    tanggal DATE,
    komoditas_pangan VARCHAR(255),
    harga_pangan DOUBLE PRECISION,
    CONSTRAINT api_kabkota_produsen_unique UNIQUE (tanggal, komoditas_pangan, harga_pangan)
)";
pg_query($conn, $sql_api_kabkota_produsen);
echo "Table api_kabkota_produsen created successfully\n";


// run script fe and api 
require_once "api-badanpangan.php";
require_once "fe-badanpangan.php";

// Close the connection
?>
