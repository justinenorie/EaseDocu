<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
                    <!-- Form Submission -->
                    <form id="signup-form" method="POST">
                        <input id="name" name="name" type="text" placeholder="Full Name" required>
                        <input id="studentID" name="studentID" type="text" placeholder="Student ID" required>
                        <input id="email" name="email" type="email" placeholder="Email" required>
                        
                        <div class="password-toggle">
                            <a href=""><img src="../../public/images/icons/pw-toggle-hide.png" alt="" style="z-index: 1;"></a>
                            <input id="password" name="password" type="password" placeholder="Password">
                        </div>
                        <div class="confirm-password-toggle password-toggle">
                            <a href=""><img src="../../public/images/icons/pw-toggle-hide.png" alt="" style="z-index: 1;"></a>
                            <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm Password">
                        </div>

                        <div id="legalTerms">
                            <input type="checkbox" name="terms" required>
                            <p>I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></p>
                        </div>
                        <input id="submit" type="submit" value="Sign Up">
                    </form>

                    <section class="signup-links">
                        <p>Already have an account? <a href="login.php">Login now</a></p>
                    </section>
                </section>
            </div>

            <div class="signup-background">
                <img src="../../public/images/backgrounds/signup-background.jpg" alt="">
                <div class="signup-background-overlay"></div>
                <div class="signup-description">
                    <h3>EaseDocu</h3>
                    <p>Your hassle-free solution for quickly requesting and tracking important school documents online.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for handling signup -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/passToggle.js"> </script>  
    <script>
        document.getElementById("signup-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = {
                name: formData.get("name"),
                studentID: formData.get("studentID"),
                email: formData.get("email"),
                password: formData.get("password"),
                confirmPassword: formData.get("confirmPassword"),
            };

            try {
                const response = await fetch("http://localhost:4000/signup", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Signup Successful",
                        showConfirmButton: false,
                        timer: 1500,
                    }).then(() => {
                        window.location.href = "login.php";
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Signup Failed",
                        text: result.message || "An error occurred.",
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Unable to process your request.",
                });
            }
        });
    </script>
</body>

</html>