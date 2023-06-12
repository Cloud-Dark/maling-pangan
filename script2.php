<?php

// Database connection settings
$host = "localhost";
$port = "5432";
$dbname = "badanpangantabel"; 
$user = "postgres";
$password = "root";

$listkabkota = json_decode('{
    "data": [{
        "nama": "Kabupaten Aceh Barat",
        "id": 7,
        "kode_bps": 1107,
        "kode_kemendagri": "11.05"
    }, {
        "nama": "Kabupaten Aceh Barat Daya",
        "id": 12,
        "kode_bps": 1112,
        "kode_kemendagri": "11.12"
    }, {
        "nama": "Kabupaten Aceh Besar",
        "id": 8,
        "kode_bps": 1108,
        "kode_kemendagri": "11.06"
    }, {
        "nama": "Kabupaten Aceh Jaya",
        "id": 16,
        "kode_bps": 1116,
        "kode_kemendagri": "11.14"
    }, {
        "nama": "Kabupaten Aceh Selatan",
        "id": 3,
        "kode_bps": 1103,
        "kode_kemendagri": "11.01"
    }, {
        "nama": "Kabupaten Aceh Singkil",
        "id": 2,
        "kode_bps": 1102,
        "kode_kemendagri": "11.10"
    }, {
        "nama": "Kabupaten Aceh Tamiang",
        "id": 14,
        "kode_bps": 1114,
        "kode_kemendagri": "11.16"
    }, {
        "nama": "Kabupaten Aceh Tengah",
        "id": 6,
        "kode_bps": 1106,
        "kode_kemendagri": "11.04"
    }, {
        "nama": "Kabupaten Aceh Tenggara",
        "id": 4,
        "kode_bps": 1104,
        "kode_kemendagri": "11.02"
    }, {
        "nama": "Kabupaten Aceh Timur",
        "id": 5,
        "kode_bps": 1105,
        "kode_kemendagri": "11.03"
    }, {
        "nama": "Kabupaten Aceh Utara",
        "id": 11,
        "kode_bps": 1111,
        "kode_kemendagri": "11.08"
    }, {
        "nama": "Kabupaten Bener Meriah",
        "id": 17,
        "kode_bps": 1117,
        "kode_kemendagri": "11.17"
    }, {
        "nama": "Kabupaten Bireuen",
        "id": 10,
        "kode_bps": 1110,
        "kode_kemendagri": "11.11"
    }, {
        "nama": "Kabupaten Gayo Lues",
        "id": 13,
        "kode_bps": 1113,
        "kode_kemendagri": "11.13"
    }, {
        "nama": "Kabupaten Nagan Raya",
        "id": 15,
        "kode_bps": 1115,
        "kode_kemendagri": "11.15"
    }, {
        "nama": "Kabupaten Pidie",
        "id": 9,
        "kode_bps": 1109,
        "kode_kemendagri": "11.07"
    }, {
        "nama": "Kabupaten Pidie Jaya",
        "id": 18,
        "kode_bps": 1118,
        "kode_kemendagri": "11.18"
    }, {
        "nama": "Kabupaten Simeulue",
        "id": 1,
        "kode_bps": 1101,
        "kode_kemendagri": "11.09"
    }, {
        "nama": "Kota Banda Aceh",
        "id": 19,
        "kode_bps": 1171,
        "kode_kemendagri": "11.71"
    }, {
        "nama": "Kota Langsa",
        "id": 21,
        "kode_bps": 1173,
        "kode_kemendagri": "11.73"
    }, {
        "nama": "Kota Lhokseumawe",
        "id": 22,
        "kode_bps": 1174,
        "kode_kemendagri": "11.74"
    }, {
        "nama": "Kota Sabang",
        "id": 20,
        "kode_bps": 1172,
        "kode_kemendagri": "11.72"
    }, {
        "nama": "Kota Subulussalam",
        "id": 23,
        "kode_bps": 1175,
        "kode_kemendagri": "11.75"
    }]
}');


$jsonDataURL = "https://panelharga.badanpangan.go.id/data/kabkota-range-by-levelharga/8/1/30-05-2023/31-05-2023";


$jsonDataFetch = file_get_contents($jsonDataURL);

if ($jsonDataFetch === false) {
    echo "Error fetching data from URL";
    exit;
}

// Convert JSON string to PHP array
$data = json_decode($jsonDataFetch, true);
if (!$data) {
    die('Error parsing JSON');
}

$connection = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$connection) {
    die('Could not connect to the database');
}

foreach ($data['data'] as $item) {
    $name = $item['name'];
    foreach ($item['by_date'] as $entry) {
        $date = $entry['date'];
        $prices = @$entry['prices'][0];
        $geomeans = $entry['geomean'];

        if ($prices === "-") {
            $prices = null;
        }
        
        if ($geomeans === "-") {
            $geomeans = null;
        }

        $query = "INSERT INTO badanpangantabel (name, date, price, geomean) VALUES ($1, $2, $3, $4)";
        $result = pg_query_params($connection, $query, [$name, $date, $prices, $geomeans]);
        if (!$result) {
            die('Error inserting data into the database');
        }
    }
}

// Close the database connection
pg_close($connection);

echo "Data inserted successfully.";
?>
