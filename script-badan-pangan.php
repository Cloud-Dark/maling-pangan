<?php

// Database connection settings
$host = "localhost";
$port = "5432";
$dbname = "badanpangantabel"; 
$user = "postgres";
$password = "root";

$jsonDataURL = "https://panelharga.badanpangan.go.id/data/kabkota-range-by-levelharga/8/1/22-05-2023/29-05-2023";

$jsonDataFetch = file_get_contents($jsonDataURL);

if ($jsonDataFetch === false){
    echo "error fetching data from url";
    exit;
}

// Convert JSON string to PHP array
$data = json_decode($jsonDataFetch, true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error decoding JSON: " . json_last_error_msg();
    exit;
}

// Check if "data" key exists and is an array
if (!isset($data['data']) || !is_array($data['data'])) {
    echo "Invalid JSON format: missing or invalid 'data' key";
    exit;
}

// Create a new database connection
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
$conn = new PDO($dsn);


// Check the database connection
if (!$conn) {
    die("Connection failed: " . print_r($conn->errorInfo(), true));
}


$sql = "INSERT INTO badanpangantabel (name, date, price, geomean) VALUES (:name, :date, :prices, :geomean)";

$stmt = $conn->prepare($sql);

// Execute the INSERT statement


foreach ($data['data'] as $item) {
    $name = $item['name'];

    // Iterate over the by_date array
    foreach ($item['by_date'] as $record) {
        $date = $record['date'];
        $prices = json_encode($record['prices']);
        $geomean = $record['geomean'];

        // Bind the values to the prepared statement placeholders
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':prices', $prices, PDO::PARAM_STR);
        $stmt->bindParam(':geomean', $geomean);

        // Execute the INSERT statement
        $stmt->execute();
        // if (!$stmt->execute()) {
            // die("Error executing query: " . print_r($stmt->errorInfo(), true));
        // }
    }
}

// ...

// Close the database connection
$conn = null;
?>






