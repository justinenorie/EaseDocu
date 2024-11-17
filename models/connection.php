<?php
require_once __DIR__ . '/../vendor/autoload.php';
//Database Connection to configure
function getMongoClient() {
    static $client = null;

    if ($client === null) {
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            // echo "Connected to MongoDB successfully.";
        } catch (Exception $e) {
            echo "Failed to connect to MongoDB: ", $e->getMessage();
        }
    }
    return $client;
}

function getEaseDocuDatabase() {
    $client = getMongoClient();
    return $client->selectDatabase('EaseDocu'); //Database Name
}