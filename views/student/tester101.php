<?php
$url = 'http://localhost:4000/getDocumentRequests'; 
$response = file_get_contents($url);
$responseData = json_decode($response, true);

if ($responseData['success']) {
    foreach ($responseData['requests'] as $request) {
        echo '<div>';
        echo 'ID: ' . htmlspecialchars($request['_id']) . '<br>';
        echo 'Student ID: ' . htmlspecialchars($request['studentID']) . '<br>';
        echo 'Name: ' . htmlspecialchars($request['name'] ?? 'N/A') . '<br>';
        echo 'Date: ' . htmlspecialchars($request['date'] ?? 'N/A') . '<br>';
        echo 'Status: ' . htmlspecialchars($request['status']) . '<br>';
        echo 'Total Payment: ' . htmlspecialchars($request['totalPayment']) . '<br>';
        echo 'Appointment Date: ' . htmlspecialchars($request['appointmentDate'] ?? 'N/A') . '<br>';
        echo 'Appointment Time: ' . htmlspecialchars($request['appointmentTime'] ?? 'N/A') . '<br>';

        // Display the requested documents array
        echo 'Requested Documents: <br>';
        if (!empty($request['requestedDocument'])) {
            echo '<ul>';
            foreach ($request['requestedDocument'] as $doc) {
                echo '<li>' . htmlspecialchars($doc) . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'None<br>';
        }

        echo '</div><hr>';
    }
} else {
    echo 'Failed to fetch document requests.';
}
?>
