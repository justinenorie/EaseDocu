<?php
$apiUrl = 'http://localhost:4000/getDocumentList';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    curl_close($ch);

    $data = json_decode($response, true);

    if ($data['success']) {
        if (!empty($data['documentList'])) {
            echo "<h1>Document List</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Document</th><th>Price</th></tr>";

            foreach ($data['documentList'] as $document) {
                echo "<tr><td>" . htmlspecialchars($document['document']) . "</td><td>" . htmlspecialchars($document['price']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No documents found.</p>";
        }
    } else {
        echo "<p>Error fetching document list: " . htmlspecialchars($data['message']) . "</p>";
    }
}
?>
