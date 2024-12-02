<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
//Database Connection to configure
function getMongoClient() {
    static $client = null;

    if ($client === null) {
        try {
<<<<<<< HEAD
            //TODO: change the connection into .env
            // $client = new MongoDB\Client($_ENV['MongoDBTOken']);
            $client = new MongoDB\Client("mongodb+srv://easedocu:easedocu123@easecluster.6yvnz.mongodb.net/");
=======
            // $client = new MongoDB\Client($_ENV['MongoDBTOken']);
            $client = new MongoDB\Client("mongodb://localhost:27017/");
>>>>>>> 0a38c103b40ad72e8a5922b3b1b0011fe30c1ff5
            // echo "Connected to MongoDB successfully.";
        } catch (Exception $e) {
            echo "Failed to connect to MongoDB: ", $e->getMessage();
        }
    }
    return $client;
}

function getEaseDocuDatabase() {
    $client = getMongoClient();
    return $client->selectDatabase('easedocu'); //Database Name
}