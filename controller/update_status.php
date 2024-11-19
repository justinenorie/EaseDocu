<?php
require 'RequestDataController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentID'])) {
    $controller = new EaseDocuController();
    $studentID = $_POST['studentID'];
    $currentStatus = $_POST['currentStatus'];

    // Determine the new status
    $newStatus = $currentStatus === 'Unpaid' ? 'Paid' :
                ($currentStatus === 'Paid' ? 'Process' : 'Finished');

    // Update the status in the database
    $updateSuccess = $controller->updateDocumentRequestStatus($studentID, $newStatus);

    if ($updateSuccess) {
        // Fetch the updated record
        $updatedRequest = $controller->getRequestByStudentId($studentID);

        // Send response as JSON
        echo json_encode([
            'success' => true,
            'data' => $updatedRequest
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the status.']);
    }
    exit;
}
