<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="loginStyles.css">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="description">
                <h1>EaseDocu</h1>
                <h2>Your hassle-free solution for quickly requesting and tracking important school documents online.</h2>
            </div>
            <div class="login-form">
                <h1>Sign in to your Account</h1>
                <p>Enter your credentials to access your account</p>
                <form action="login.php" method="post">
                    <div class="inputs">
                        <input type="name" name="student" placeholder="StudentID" required>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="remember">
                        <p>Remember me</p>
                        <p><a href="#">Forgot password?</a></p>
                    </div>
                    <button class="btn" type="submit">Login</button>
                </form>
                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>
</body>

</html>