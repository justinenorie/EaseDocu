<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" href="../../public/images/icons/easedocu-icon.png" type="image/png"> -->
    <title>EaseDocu - Sign Up</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../student/styles/signup.css">
</head>

<body>
    <div class="signup-holder">
        <div class="signup-container">

            <div class="signup-form-container">

                <section class="signup-header">
                    <h1>Sign Up with EaseDocu</h1>
                </section>

                <section class="signup-content">
                    <form id="signup-form" action="">
                        <input id="name" type="text" placeholder="Full Name">
                        <input id="studentID" type="text" placeholder="Student ID">
                        <input id="email" type="email" placeholder="Email">
                        <input id="password" type="password" placeholder="Password">
                        <div class="password-toggle">
                            <a href=""><img src="../../public/images/icons/pw-toggle-hide.png" alt="" style="z-index: 1;"></a>
                            <input id="confirmPassword" type="password" placeholder="Confirm Password">
                        </div>

                        <div id="legalTerms">
                            <input type="checkbox" name="text" id="">
                            <p>I agree to the <a href="">Terms & Conditions</a> and <a href="">Privacy Policy</a></p>
                        </div>
                        <input id="submit" type="submit" value="Sign Up">

                        <section class="signup-links">
                            <p>Already have account? <a href="login.php">Login now</a></p>
                        </section>

                    </form>



                </section>
            </div>

            <div class="signup-background">
                <img src="../../public/images/backgrounds/signup-background.jpg" alt="">
                <div class="signup-background-overlay"></div>
                <div class="signup-background-overlay"></div>
                <div class="signup-description">
                    <h3 id="docuText">EaseDocu</h3>
                    <p>Your hassle-free solution for quickly requesting and tracking important school documents online.</p>
                </div>
            </div>

        </div>
    </div>

    <script src="../student/js/signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>