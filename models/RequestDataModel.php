<?php
require_once __DIR__ . '/../models/connection.php';

//This is the model for manipulating the database
//Request Data Model
class RequestDataModel {
    private $collection;

    public function __construct() {
        $db = getEaseDocuDatabase();
        $this->collection = $db->selectCollection('documentRequestsList');
    }

     // Fetch all request data
    public function getAllRequests() {
        $cursor = $this->collection->find();
        return iterator_to_array($cursor);
    }

    // Fetch a single request by student ID
    public function getRequestByStudentId($studentId) {
        return $this->collection->findOne(['studentID' => $studentId]);
    }

    // Add a new request
    public function addRequest($data) {
        $result = $this->collection->insertOne($data);
        return $result->getInsertedId();
    }

    // Update a request status by student ID
    public function updateRequestStatus($studentId, $status) {
        $result = $this->collection->updateOne(
            ['studentID' => $studentId],
            ['$set' => ['status' => $status]]
        );
        return $result->getModifiedCount();
    }

    // Delete a request by student ID
    public function deleteRequestByStudentId($studentId) {
        $result = $this->collection->deleteOne(['studentID' => $studentId]);
        return $result->getDeletedCount();
    }
}

//Document List Model
class DocumentModel {
    private $collection;

    public function __construct() {
        $db = getEaseDocuDatabase();
        $this->collection = $db->selectCollection('documentList');
    }

    public function getAllDocuments() {
        $cursor = $this->collection->find();
        return iterator_to_array($cursor);
    }

    // Fetch a single document by ID
    public function getDocumentById($id) {
        return $this->collection->findOne(['id' => $id]);
    }

    // Add a new document
    public function addDocument($data) {
        $result = $this->collection->insertOne($data);
        return $result->getInsertedId();
    }

    // Update a document by ID
    public function updateDocumentById($id, $data) {
        $result = $this->collection->updateOne(
            ['id' => $id],
            ['$set' => $data]
        );
        return $result->getModifiedCount();
    }

    // Delete a document by ID
    public function deleteDocumentById($id) {
        $result = $this->collection->deleteOne(['id' => $id]);
        return $result->getDeletedCount();
    }
}