<?php
session_start();

// Check if session exists
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); 
    exit();
}



$userEmail = $_SESSION['user_email'];
$userName = $_SESSION['user_name'];
$userAge = $_SESSION['user_age'];
var_dump($_SESSION);
echo '<pre>';
print_r($_SESSION['user_age']);
echo '</pre>';

// Access session data safely
echo isset($_SESSION['user']['key_name']) ? $_SESSION['user']['key_name'] : 'N/A';
require '../../views/components/topBarStudent.php';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Request</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/documentRequest.css">

    <style>

    </style>
</head>

<body>
    <div class="content-holder">
        <form action="">
            <section class="req-doc">

                <div class="req-doc-description">
                    <h2>Request a Document Now!</h2>
                </div>
                <div class="req-doc-order">
                    <ul>
                        <?php
                        include '../components/document-request/fetchDocuments.php';
                        echo renderDocumentsList();
                        ?>
                    </ul>
                </div>
            </section>
            <section class="req-doc-payment">
                <div class="req-doc-description">
                    <h2>Payment Details</h2>
                </div>
                <ul id="paymentList">
                </ul>
            </section>

            <section class="req-doc-submit" id="req-doc-submit">
                <div class="req-doc-total" id="req-doc-total">
                    
                </div>

                <!-- <input type="submit" name="" value="Request Now" id=""> -->
                <input type="submit" name="" value="Request Now" id="request-btn" onclick="window.location.href='requestStatus.php'">
            </section>
        </form>
    </div>
</body>
<script src="../student/js/quantityButton.js"></script>

</html>