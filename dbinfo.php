<?php
// $servername = "my-db.c56aeozf7don.us-east-1.rds.amazonaws.com";
// $username = "admin";
// $password = "abcd1234";
// $dbname = "my-db";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-db";

// Connect to MySQL
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect to the database. " . mysqli_connect_error());
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === true) {
    // Select the database
    mysqli_select_db($conn, $dbname);

    // Check if the table exists
    $tableExists = mysqli_query($conn, "SHOW TABLES LIKE 'internships'");

    // If table does not exist, create it
    if (!$tableExists->num_rows) {
        $sql = "CREATE TABLE employees (
            id int(11) NOT NULL AUTO_INCREMENT,
            firstName varchar(100) NOT NULL,
            lastName varchar(100) NOT NULL,
            email varchar(50) NOT NULL,
            contact int(11) NOT NULL,
            position varchar(50) NOT NULL,
            PRIMARY KEY (id)
        )";

        $conn->query($sql);
    }
} else {
    echo "Error creating database: " . $conn->error;
    exit;
}
?>