<?php
require_once __DIR__ . '/connection.php';

// Students Credentials Model
class StudentModel {
    private $collection;

    public function __construct() {
        $db = getEaseDocuDatabase();
        $this->collection = $db->selectCollection('students'); // Collection Name
    }

    // Fetch all students
    public function getAllStudents() {
        $cursor = $this->collection->find();
        return iterator_to_array($cursor);
    }

    // Fetch a student by student ID
    public function getStudentById($studentId) {
        return $this->collection->findOne(['studentID' => $studentId]);
    }

    // Add a new student
    public function addStudent($data) {
        // Hash the password before saving
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $result = $this->collection->insertOne($data);
        return $result->getInsertedId();
    }

    // Update a student by ID
    public function updateStudentById($studentId, $data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $result = $this->collection->updateOne(
            ['studentID' => $studentId],
            ['$set' => $data]
        );
        return $result->getModifiedCount();
    }

    // Delete a student by ID
    public function deleteStudentById($studentId) {
        $result = $this->collection->deleteOne(['studentID' => $studentId]);
        return $result->getDeletedCount();
    }

    // Verify login credentials
    public function verifyLogin($studentId, $password) {
        $student = $this->collection->findOne(['studentID' => $studentId]);
        if ($student && password_verify($password, $student['password'])) {
            return $student;
        }
        return null;
    }
}
