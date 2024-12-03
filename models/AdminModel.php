<?php
require_once __DIR__ . '/connection.php';

// Admin Credentials Model
class AdminModel {
    private $collection;

    public function __construct() {
        $db = getEaseDocuDatabase();
        $this->collection = $db->selectCollection('adminCredential'); // Collection Name
    }

    public function getAdminAccount() {
        $cursor = $this->collection->find();
        return iterator_to_array($cursor);
    }

    // Add a new admin
    public function addAdminAccount($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $this->collection->insertOne(['username' => $username, 'password' => $hashedPassword]);
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
    public function deleteAdminById($id) {
        $result = $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectID($id)]); //Gumagana yan kahit may Pula
        return $result->getDeletedCount();
    }

    // Verify login credentials
    public function verifyLogin($username, $password) {
        $admin = $this->collection->findOne(['username' => ['$regex' => '^' . preg_quote($username) . '$', '$options' => 'i']]);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return null;
    }
}