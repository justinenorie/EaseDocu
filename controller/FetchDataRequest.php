<?php
require 'RequestDataController.php';
// TODO: Add a login session for admin
$controller = new EaseDocuController();
$documentRequests = $controller->getAllDocumentRequests(); // Fetch all the document requests
$documentList = $controller->getAllDocuments(); //Fetch all the document List
$requestByStudentId = $controller->getRequestByStudentId($studentId); // Fetch a specific document request by student ID

// Handle status update 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];
    $currentStatus = $_POST['currentStatus'];

    $newStatus = $currentStatus === 'unpaid' ? 'paid' : ($currentStatus === 'paid' ? 'process' : 'ready');

    $updateRequestStatus = $controller->updateDocumentRequestStatus($studentID, $newStatus);

    //Sending the data as JSON
    if ($updateRequestStatus) {
        echo json_encode(['success' => true, 'newStatus' => $newStatus]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Add a function here if the page is refresh all the request status will be updated as unpaid

//Filter Request Later
// $filter = $_GET['filter'] ?? null; // Example: Capture filter from URL
// if ($filter) {
//     $filteredRequests = array_filter($documentRequests, function($request) use ($filter) {
//         return $request['status'] === $filter;
//     });
// } else {
//     $filteredRequests = $documentRequests;
// }
// echo '<pre>';
// print_r($documentRequests);
// echo '</pre>';
// exit;