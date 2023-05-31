<?php

// Database connection settings
$host = "localhost";
$port = "5432";
$dbname = "badanpangantabel"; 
$user = "postgres";
$password = "root";


$connection = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$connection) {
    die('Could not connect to the database');
}

for($i=1;$i < 24;$i++){
    // Date Format 30-05-2023/31-05-2023
    $date_now = date("d-m-Y");
    $date_yesterday= date("d-m-Y", strtotime("-1 days"));
    $json_produsen = "https://panelharga.badanpangan.go.id/data/kabkota-range-by-levelharga/".$i."/1/".$date_yesterday."/".$date_now;
    // $json_eceran = "https://panelharga.badanpangan.go.id/data/kabkota-range-by-levelharga/".$i."/3/30-05-2023/31-05-2023";



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
}
?>