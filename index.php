<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            font-family: var(--POPPINS);
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        button {
            background-color: var(--PRIMARY);
            font-family: var(--WORK-SANS);
            font-size: 1.5rem;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 1em;
            cursor: pointer;
            margin: 1rem;
        }
        button:hover {
            background-color: var(--ACCENT);
        }
    </style>
</head>

<body>
    <h1>Login As?</h1>
    <button onclick="window.location.href='views/student/login.php'">Login as Student</button>
    <button onclick="window.location.href='views/admin/loginAdmin.php'">Login as Admin</button>
</body>

</html>
