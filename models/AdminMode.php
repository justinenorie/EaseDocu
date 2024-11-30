<?php
require_once __DIR__ . '/connection.php';

// Admin Credentials Model
class AdminModel {
    private $collection;

    public function __construct() {
        $db = getEaseDocuDatabase();
        $this->collection = $db->selectCollection('admin'); // Collection Name
    }

    // Fetch an admin by username
    public function getAdminByUsername($username) {
        return $this->collection->findOne(['username' => $username]);
    }

    // Add a new admin
    public function addAdmin($data) {
        $result = $this->collection->insertOne($data);
        return $result->getInsertedId();
    }

    // Update an admin by username
    public function updateAdminByUsername($username, $data) {
        $result = $this->collection->updateOne(
            ['username' => $username],
            ['$set' => $data]
        );
        return $result->getModifiedCount();
    }

    // Delete an admin by username
    public function deleteAdminByUsername($username) {
        $result = $this->collection->deleteOne(['username' => $username]);
        return $result->getDeletedCount();
    }

    // Verify login credentials
    public function verifyLogin($username, $password) {
        $admin = $this->collection->findOne(['username' => $username]);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return null;
    }
}
