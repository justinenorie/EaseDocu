<?php
require_once __DIR__ . '/../models/DocumentModels.php';
class EaseDocuController {
    private $documentModel;
    private $requestDataModel;

    public function __construct() {
        $this->documentModel = new DocumentModel();
        $this->requestDataModel = new RequestDataModel();
    }
    //DOCUMENT REQUEST LIST CONTROLLER
    // Retrieve all document requests
    public function getAllDocumentRequests() {
        return $this->requestDataModel->getAllRequests();
    }

    // Retrieve a specific document request by student ID
    public function getRequestByStudentId($studentId) {
        return $this->requestDataModel->getRequestByStudentId($studentId);
    }

    // Add a new document request
    public function addNewDocumentRequest($data) {
        return $this->requestDataModel->addRequest($data);
    }

    // Update the status of a document request
    public function updateDocumentRequestStatus($studentId, $status, $date, $time) {
        return $this->requestDataModel->updateRequestStatus($studentId, $status, $date, $time);
    }

    // Delete a document request by student ID
    public function deleteDocumentRequest($studentId) {
        return $this->requestDataModel->deleteRequestByStudentId($studentId);
    }

    //DOCUMENT LIST CONTROLLER
    // Retrieve all documents
    public function getAllDocuments() {
        return $this->documentModel->getAllDocuments();
    }

    // Retrieve a specific document by ID
    public function getDocumentById($id) {
        return $this->documentModel->getDocumentById($id);
    }

    // Add a new document
    public function addNewDocument($data) {
        return $this->documentModel->addDocument($data);
    }

    // Update a document by ID
    public function updateDocument($id, $data) {
        return $this->documentModel->updateDocumentById($id, $data);
    }

    // Delete a document by ID
    public function deleteDocument($id) {
        return $this->documentModel->deleteDocumentById($id);
    }

    //TODO: Add a control credentials for Admin and Student
    
}
//Testing if it works
// $controller = new EaseDocuController();
// $documents = $controller->getAllDocuments();

// foreach ($documents as $doc) {
//     print_r($doc);
// }

