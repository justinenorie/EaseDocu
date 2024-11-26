<?php
require '../controller/RequestDataController.php';

$controller = new EaseDocuController();
$documentList = $controller->getAllDocuments();

// Retrieve the index from the POST request
$index = isset($_POST['index']) ? intval($_POST['index']) : 0;
if ($index >= 0 && $index < count($documentList)) {
    $document = $documentList[$index];
    echo '<tr>';
    echo '<td>' . $document['_id'] . '</td>';
    echo '<td>' . $document['document'] . '</td>';
    echo '<td>' . $document['price'] . '</td>';
    echo '</tr>';
} else {
    
}
