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
                <h1>Sign in to your Account</h1>
                <p>Enter your credentials to access your account</p>
                <form action="login.php" method="post">
                    <div class="inputs">
                        <input type="name" name="student" placeholder="StudentID" required>

                        <div class="password-toggle">
                            <input id="password" type="password" name="password" placeholder="Password" required>
                            <a href=""><img src="../../public//images//icons/pw-toggle-hide.png" alt="Eye Icon"></a>
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
                <div class="signupPanel">
                    <p>Don't have an account? <a href="signup.php">Sign Up Here</a></p>
                </div>
            </div>



        </div>
    </div>
    <script src="../../public/js/passToggle.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Example Student Data -->
    <script>
        async function fetchStudentData() {
            try {
                //Example data file path
                const response = await fetch('../../data/studentsData.json');
                const students = await response.json();
                document.querySelector('form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const studentID = document.querySelector('input[name="student"]').value;
                    const password = document.querySelector('input[name="password"]').value;

                    const student = students.find(s => s.studentID === studentID && s.password === password);

                    if (student) {
                        //Call the success function here
                        alertLoginSuccess();
                        document.getElementById("submit-btn").addEventListener("click", alertLoginSuccess);
                        setTimeout(() => {
                            window.location.href = "documentRequest.php";
                        }, 1500);
                    } else {
                        alertFailLogin();
                        document.getElementById("submit-btn").addEventListener("click", alertFailLogin);
                    }
                });
            } catch (error) {
                console.error('Error fetching student data:', error);
            }
        }
        fetchStudentData();
    </script>
</body>

</html>