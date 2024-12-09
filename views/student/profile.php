<?php
session_start();

if (!isset($_SESSION['studentID'])) {
    header("Location: login.php");
    exit();
}

$studentId = isset($_SESSION['studentID']) ? $_SESSION['studentID'] : 'N/A';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'N/A';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'N/A';
$profileImage = isset($_SESSION['profileImage']) ? $_SESSION['profileImage'] : 'default.png';

require '../../views/components/topBarStudent.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['profileImage']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES['profileImage']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile)) {
        $_SESSION['profileImage'] = $targetFile;
        header("Location: profile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .profile_1 {
            max-width: 800px;
            margin: 20px auto;
            margin-top: 130px;
            background-color: #fff;
            padding: 40px 80px;
            border-radius: 30px;
            border: 1px solid rgb(207, 207, 207);
            border-right: 5px solid rgb(163, 163, 163);
            border-bottom: 5px solid rgb(163, 163, 163);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile_1 h1 {
            font-size: 34px;
            margin-bottom: 40px;
            font-weight: 900;
            color: #000000;
        }

        .profile-image-container {
            position: relative;
            margin-bottom: 30px;
            text-align: right;
        }

        .profile-text {
            text-align: center;
            margin-right: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ccc;
            cursor: pointer;
            position: absolute;
            top: -120px;
            right: 0;
            margin-right: -70px;
        }

        .formbox_1 {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .formbox_1 .field {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid black;
            border-radius: 8px;
            padding: 10px 15px;
            width: 450px;
            margin: 0 auto;
            background-color: #f9f9f9;
        }

        .formbox_1 .field h1 {
            font-size: 16px;
            font-weight: 400;
            margin: 0;
            flex-grow: 1;
            text-align: left;
        }

        .formbox_1 .edit {
            color: rgb(41, 41, 252);
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .formbox_1 .edit:hover {
            text-decoration: underline;
        }

        #profileImageInput {
            display: none;
        }
    </style>
</head>
<body>
    <div class="profile_1">
        <div class="profile-text">
            <h1>Profile</h1>
        </div>
        <div class="profile-image-container">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="profile-image" id="profileImage">
            <form method="POST" enctype="multipart/form-data" id="imageUploadForm">
                <input type="file" name="profileImage" id="profileImageInput" accept="image/*" onchange="document.getElementById('imageUploadForm').submit();">
            </form>
        </div>
        <div class="formbox_1">
            <div class="field">
                <h1 id="name-display"><?php echo htmlspecialchars($name); ?></h1>
                <a class="edit" id="edit-name" onclick="enableEdit('name')">Edit</a>
            </div>
            <div class="field">
                <h1 id="student-id-display"><?php echo htmlspecialchars($studentId); ?></h1>
                <a class="edit" id="edit-student-id" onclick="enableEdit('student-id')">Edit</a>
            </div>
            <div class="field">
                <h1 id="email-display"><?php echo htmlspecialchars($email); ?></h1>
                <a class="edit" id="edit-email" onclick="enableEdit('email')">Edit</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profileImage').addEventListener('click', function () {
            document.getElementById('profileImageInput').click();
        });

        function enableEdit(field) {
            const displayElement = document.getElementById(${field}-display);
            const currentValue = displayElement.textContent;

            const inputElement = document.createElement('input');
            inputElement.type = 'text';
            inputElement.value = currentValue;
            inputElement.style.border = "1px solid black";
            inputElement.style.borderRadius = "8px";
            inputElement.style.padding = "10px";
            inputElement.style.width = "100%";

            displayElement.replaceWith(inputElement);

            const editLink = document.getElementById(edit-${field});
            editLink.textContent = 'Save';
            editLink.onclick = function () {
                saveEdit(field, inputElement.value);
            };
        }

        function saveEdit(field, newValue) {
            const inputElement = document.querySelector(#${field}-display ~ input);

            const displayElement = document.createElement('h1');
            displayElement.id = ${field}-display;
            displayElement.textContent = newValue;

            inputElement.replaceWith(displayElement);

            const saveLink = document.getElementById(edit-${field});
            saveLink.textContent = 'Edit';
            saveLink.onclick = function () {
                enableEdit(field);
            };

            console.log(Updated ${field}: ${newValue});
        }
    </script>
</body>
</html>
