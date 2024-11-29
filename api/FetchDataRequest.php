<?php
// TODO: Add a login session for admin
// TODO: Security - add a input validation for $_POST and $_GET
require '../controller/RequestDataController.php';
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

    if ($updateRequestStatus) {
        echo json_encode(['success' => true, 'newStatus' => $newStatus]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Get the document requests from database
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    // echo json_encode(['documentRequests' => $documentRequests]);

    $status = $_GET['status'] ?? null; // Get the status filter (if provided)

    if ($status) {
        // Filter requests by status
        $filteredRequests = array_filter($documentRequests, function ($request) use ($status) {
            return $request['status'] === $status;
        });
        echo json_encode(['documentRequests' => array_values($filteredRequests)]);
    } else {
        // Return all requests if no filter is applied
        echo json_encode(['documentRequests' => $documentRequests]);
    }
    exit;
}