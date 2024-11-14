<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/loginAdmin.css">
</head>

<body>
    <div class="container">
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
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="remember">
                        <div class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <p><a href="#">Forgot password?</a></p>
                    </div>
                    <button class="btns" type="submit">Login</button>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Example Student Data -->
    <script>
        async function fetchStudentData() {
            try {
                //Example data file path
                const response = await fetch('../../data/admin.json');
                const students = await response.json();
                document.querySelector('form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const admin = document.querySelector('input[name="admin"]').value;
                    const password = document.querySelector('input[name="password"]').value;

                    const student = students.find(s => s.adminUsername === admin && s.adminPassword === password);

                    if (student) {
                        alert('Login successful!');
                        //Redirect to the document request page
                        window.location.href = 'reportsList.php';
                    } else {
                        alert('Invalid Admin or Password');
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