<?php
require __DIR__ . '/../models/AdminModel.php';

session_start();
// If the user is already logged in, it will not open the Login Page
if (isset($_SESSION['admin'])) {
    header("Location: requestList.php");
    exit();
}

$admin = new AdminModel();
$getAllAdmin = $admin->getAdminAccount();
$newAccountCreated = false;
$username = '';
$password = '';

// Generate Account Account
if (empty($getAllAdmin)) {
    $username = 'admin';
    $password = generateRandomPassword();
    $generateAccount = $admin->addAdminAccount($username, $password);
    $newAccountCreated = true;
}

// LOGIN SESSION
//TODO: Add a security validation here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => 'Invalid username or password'];

    if (!empty($_POST['admin']) && !empty($_POST['password'])) {
        $username = $_POST['admin'];
        $password = $_POST['password'];

        // Verify login details using AdminModel
        $verifyLogin = $admin->verifyLogin($username, $password); // Assumes this method exists
        if ($verifyLogin) {
            $_SESSION['admin'] = $username; // Set the session variable
            $response['success'] = true;
            $response['message'] = 'Login successful';
        } else {
            $response['message'] = 'Invalid login details';
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// RANDOM PASSWORD
function generateRandomPassword($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i =  0; $i < $length; $i++) {
        $randomPassword .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomPassword;
}
