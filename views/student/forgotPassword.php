<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../student/styles/forgotPassword.css">
</head>

<body>
    <div class="form-container">
        <h2>Forgot Password</h2>
        <form id="forgotPasswordForm" onsubmit="event.preventDefault(); forgotPassword();">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function forgotPassword() {
            const email = document.getElementById("email").value;

            try {
                const response = await fetch("http://localhost:4000/api/auth/forgot-password", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        email
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: "success",
                        title: "Reset email sent",
                        showConfirmButton: false,
                        timer: 2500,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Reset Failed",
                        text: "Failed to send reset link",
                    });
                }
            } catch (error) {
                alert("An error occurred. Please try again later.");
                console.error("Forgot Password Error:", error);
            }
        }
    </script>
</body>

</html>