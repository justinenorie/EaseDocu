<!-- Login Session -->
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
                <form action="login.php" method="post">
                    <div class="inputs">
                        <input type="name" name="admin" placeholder="Admin" required>

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
            </div>
        </div>
    </div>
    <!-- SweetAlert for Popup -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/passToggle.js"> </script>
    <!-- Example Student Data -->
    <!-- TODO: Replace this with the actual admin credentials data -->
    <script>
        async function fetchStudentData() {
            try {
                //Example data file path
                //Session Login
                const response = await fetch('../../data/admin.json');
                const adminsAccount = await response.json();
                document.querySelector('form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const admin = document.querySelector('input[name="admin"]').value;
                    const password = document.querySelector('input[name="password"]').value;

                    const admins = adminsAccount.find(s => s.adminUsername === admin && s.adminPassword === password);

                    if (admins) {
                        alertLoginSuccess();
                        document.getElementById("submit-btn").addEventListener("click", alertLoginSuccess);
                        //Redirect to the document request page
                        setTimeout(() => {
                            window.location.href = "requestList.php";
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