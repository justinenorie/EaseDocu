<?php
require '../vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");

try {
    $client->listDatabases();
    echo "Connected to MongoDB successfully.";
} catch (Exception $e) {
    echo "Failed to connect to MongoDB: ", $e->getMessage();
}