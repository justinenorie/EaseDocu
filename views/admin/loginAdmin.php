<!-- Login Session -->
<?php
require '../../api/AdminLoginHandler.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Admin</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/loginAdmin.css">
</head>

<body>
    <div class="login-back">
        <div class="login-container">
            <div class="description">
                <div class="EaseDocu">
                    <img src="../../public/images/icons/logo-white.png" alt="EaseDocu Logo" class="logo">
                    <h1>EaseDocu</h1>
                </div>
                <h2>Your hassle-free solution for quickly requesting and tracking important school documents online.</h2>
            </div>
            <div class="login-form">
                <h1>Sign in as Admin</h1>
                <p>Admin account controls everything</p>
                <form id="login-form">
                    <div class="inputs">
                        <input type="name" name="admin" placeholder="Admin" required autocomplete="off">

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
                </form>
            </div>
        </div>
    </div>
    <!-- SweetAlert for Popup -->
    <script src="../../public/js/passToggle.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php if ($newAccountCreated): ?>
        <script>
            //TODO: Add a email confirmation
            //TODO: Admin account not found do you want to generate an account for admin? Confirmation
            Swal.fire({
                title: 'Default Admin Account Created!',
                html: `<p>Username: <strong><?= $username ?></strong></p><p>Password: <strong><?= $password ?></strong></p>`,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>

    <script>
        // login_ajax.js
        // TODO: It will not break the session until the admin didn't click the logout button
        $('#login-form').submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../../api/AdminLoginHandler.php',
                data: formData,
                dataType: 'json', // Expect JSON response from the server
                success: function(response) {
                    if (response.success) {
                        console.log("Login success");
                        alertLoginSuccess(); // Call success function
                        setTimeout(function() {
                            window.location.href = 'requestList.php'; // Redirect to the desired page after 1 second
                        }, 1000);
                    } else {
                        console.log("Login fail");
                        alertFailAdminLogin(); // Call failure function
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