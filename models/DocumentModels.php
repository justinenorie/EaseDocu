<?php
require_once __DIR__ . '/connection.php';

//This is the model for manipulating the database
//Request Data Model
//TODO: Add a security validation in the models
class RequestDataModel {
    private $collection;

    public function __construct() {
        $db = getEaseDocuDatabase();
        $this->collection = $db->selectCollection('documentRequestsList'); //Collection Name
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
    public function updateRequestStatus($id, $status, $date, $time) {
        // Convert the string ID to a MongoDB ObjectId
        $objectId = new MongoDB\BSON\ObjectId($id); //May Pula lang talaga siya pero gumagana
        $result = $this->collection->findOneAndUpdate(
            ['_id' => $objectId], // Query using _id
            ['$set' => ['status' => $status, 'appointmentDate' => $date, 'appointmentTime' => $time]], // Update operation
            ['returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER] // Return the document after update
        );
        return $result;
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
        $this->collection = $db->selectCollection('documentList'); //Collection Name
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