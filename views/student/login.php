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
    <form class="login-back" action="login.php" method="post">
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
                    <input type="text" name="student" placeholder="StudentID" required>

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
                    <p><a href="#">Forgot password?</a></p>
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

   <?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['student'];
    $password = $_POST['password'];

    // Send credentials to Node.js backend
    $url = 'http://localhost:4000/login';
    $data = json_encode(['email' => $email, 'password' => $password]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
        ],
    ];
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result !== FALSE) {
        $responseData = json_decode($result, true);

        if (isset($responseData['user'])) {
            $_SESSION['user_email'] = $responseData['user']['email'];
            $_SESSION['user_name'] = $responseData['user']['name'];
            $_SESSION['user_age'] = $responseData['user']['age'];

            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'documentRequest.php';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login failed',
                        text: 'Invalid email or password!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Server error',
                    text: 'Unable to process request!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'login.php';
                });
              </script>";
    }
    exit();
}
?>


</body>

</html>
