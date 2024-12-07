<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .form-container h2 {
            text-align: center;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }
    </style>
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
    <script src="../../public/js/passToggle.js"> </script>
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
                        title: "Signup Failed",
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