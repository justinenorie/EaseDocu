<?php
require '../../vendor/autoload.php';

try {
    $mongo = new MongoDB\Client($_ENV['MONGODB_TOKEN']);
    $collection = $mongo->easedocu->documentList;
    
    $documents = $collection->find()->toArray();
    
    $documentsArray = array_map(function($doc) {
        $doc['_id'] = (string)$doc['_id'];
        return $doc;
    }, $documents);
    
    // Return as JSON
    header('Content-Type: application/json');
    echo json_encode($documentsArray);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>