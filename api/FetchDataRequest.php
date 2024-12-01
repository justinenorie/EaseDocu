<?php
// TODO: Add a login session for admin
// TODO: Security - add a input validation for $_POST and $_GET
// require '../controller/RequestDataController.php';
// $controller = new EaseDocuController();
// $documentRequests = $controller->getAllDocumentRequests(); // Fetch all the document requests
// $documentList = $controller->getAllDocuments(); //Fetch all the document List

require '../models/DocumentModels.php';
$RequestModel = new RequestDataModel();
// Fetch all the document requests
$documentRequests = $RequestModel->getAllRequests(); 

// Handle status update 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentObjectID'])) {
    $id = $_POST['studentObjectID'];
    $currentStatus = $_POST['currentStatus'];
    $date = $_POST['date'] ?? null; // Get the date from the request
    $time = $_POST['time'] ?? null; // Get the time from the request

    $newStatus = $currentStatus === 'unpaid' ? 'paid' : ($currentStatus === 'paid' ? 'process' : 'ready');
    $updateRequestStatus = $RequestModel->updateRequestStatus($id, $newStatus, $date, $time);

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
   
    $status = $_GET['status'] ?? null; // Get the status filter 
    $query = $_GET['query'] ?? ""; // Get the search query 

    $filteredRequests = $documentRequests; //Call the controller

    // Filter Status
    if ($status) {
        
        $filteredRequests = array_filter($filteredRequests, function ($request) use ($status) {
            return $request['status'] === $status;
        });
    }

    // Search query
    if ($query) {
        $filteredRequests = array_filter($filteredRequests, function ($request) use ($query) {
            $query = strtolower($query);
            return strpos(strtolower($request['name']), $query) !== false || 
                   strpos(strtolower($request['studentID']), $query) !== false;
        });
    }
    
    // Passed and Received the data as JSON
    echo json_encode(['documentRequests' => array_values($filteredRequests)]);
    exit;
}