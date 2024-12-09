<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => 'Invalid username or password'];

    if (!empty($_POST['studentID']) && !empty($_POST['password'])) {
        $studentID = $_POST['studentID'];
        $password = $_POST['password'];

        // Express server endpoint
        $url = 'http://localhost:4000/login';
        $data = json_encode(['studentID' => $studentID, 'password' => $password]);

        // Set up HTTP options for the request
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => $data,
            ],
        ];
        $context = stream_context_create($options);

        try {
            // Make the HTTP POST request to the Express server
            $result = file_get_contents($url, false, $context);

            if ($result !== false) {
                $responseData = json_decode($result, true);

                if (!empty($responseData['success']) && $responseData['success'] === true) {
                    // Save user data to the session
                    $_SESSION['studentID'] = $responseData['user']['studentID'];
                    $_SESSION['name'] = $responseData['user']['name'] ?? 'Unknown';

                    $response['success'] = true;
                    $response['message'] = 'Login successful';
                } else {
                    $response['message'] = $responseData['message'] ?? 'Invalid login details';
                }
            } else {
                $response['message'] = 'Unable to connect to the server. Please try again later.';
            }
        } catch (Exception $e) {
            $response['message'] = 'An error occurred while processing the request: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Student ID and password are required.';
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