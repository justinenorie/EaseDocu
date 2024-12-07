<?php
require __DIR__ . '/../../models/StudentModel.php';
session_start();
$student = new StudentModel();
$getStudentByID = $student->getStudentById($studentId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => 'Invalid username or password'];

    if (!empty($_POST['studentID']) && !empty($_POST['password'])) {
        $studentId = $_POST['studentID'];
        $password = $_POST['password'];

        // Verify login details using AdminModel
        $verifyLogin = $student->verifyLogin($studentId, $password); // Assumes this method exists
        if ($verifyLogin) {
            $_SESSION['studentID'] = $studentId; // Set the session variable
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Login</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/loginStyles.css">
</head>

<body>
    <form class="login-back" id="login-form" method="post">
        <div class="login-container">
            <div class="description">
                <div class="EaseDocu">
                    <img src="../../public/images/icons/logo-white.png" alt="EaseDocu Logo" class="logo">
                    <h1>EaseDocu</h1>
                </div>
                <h2>Your hassle-free solution for quickly requesting and tracking important school documents online.</h2>
            </div>
            <div class="login-form">
                <h1>Sign in to your Account</h1>
                <p>Enter your credentials to access your account</p>
                <div class="inputs">
                    <input type="text" name="studentID" placeholder="StudentID" required>

                    <div class="password-toggle">
                        <input id="password" type="password" name="password" placeholder="Password" required>
                        <a href=""><img src="../../public/images/icons/pw-toggle-hide.png" alt="Eye Icon"></a>
                    </div>

                </div>
                <div class="remember">
                    <div class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <p><a href="forgotPassword.php">Forgot password?</a></p>
                </div>
                <button id="submit-btn" class="btns" type="submit">Login</button>
                <div class="signupPanel">
                    <p>Don't have an account? <a href="signup.php">Sign Up Here</a></p>
                </div>
            </div>
        </div>
    </form>

    <script src="../../public/js/passToggle.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $('#login-form').submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: formData,
                dataType: 'json', // Expect JSON response from the server
                success: function(response) {
                    if (response.success) {
                        console.log("Login success");
                        alertLoginSuccess(); // Call success function
                        setTimeout(function() {
                            window.location.href = 'documentRequest.php'; // Redirect to the desired page after 1 second
                        }, 1000);
                    } else {
                        console.log("Login fail");
                        alertFailStudentLogin(); // Call failure function
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
</body>

</html>