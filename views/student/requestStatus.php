<?php
session_start();

if (!isset($_SESSION['user_studentID'])) {
    header("Location: login.php");
    exit();
}

$studentID = $_SESSION['user_studentID'];
$userEmail = $_SESSION['user_email'];
$userName = $_SESSION['user_name'];

require '../../views/components/topBarStudent.php';
require '../../views/components/chatModal.php';
require_once '../../views/components/progressIndicator.php';

$url = 'http://localhost:4000/getDocumentRequests';
$response = file_get_contents($url);
$responseData = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Request</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/requestStatus.css">
    <link rel="stylesheet" href="styles/progressIndicator.css">
</head>

<body>
    <div class="content-holder">
        <section class="req-doc">
            <div class="req-doc-description">
                <h2>Request Status</h2>
            </div>

            <div class="req-doc-order">
                <?php
                if ($responseData['success']) {
                    foreach ($responseData['requests'] as $request) {
                        renderProgressIndicator($request['status'], $statusSteps);
                        renderStatusMessage($request['status']);
                    }
                } else {
                    echo '<p>No document requests found.</p>';
                }
                ?>
            </div>

            <div class="line-breaker"></div>

            <div class="req-doc-description">
                <h2>Document Requested</h2>
            </div>

            <div class="req-doc-order">
                <ul id='document-list'>
                    <?php
                    if ($responseData['success']) {
                        foreach ($responseData['requests'] as $request) {
                            foreach ($request['requestedDocument'] as $doc) {
                                echo "
                                <li class='list-item'>
                                    <div class='list-item-left'>
                                        <p>x3</p>
                                        <div class='item-icon'>
                                            <img src='../../public/images/icons/doc-certificate.png' alt=''>
                                        </div>
                                    </div>
                                    <div class='list-item-right'>
                                        <div class='upper-item'>
                                            <div class='item-name'>
                                                <h2>" . htmlspecialchars($doc) . "</h2>
                                            </div>
                                        </div>
                                        <div class='lower-item'>
                                            <div class='price'><span>&#8369;</span>" . htmlspecialchars($request['totalPayment']) . "</div>
                                        </div>
                                    </div>
                                </li>
                                ";
                            }
                        }
                    } else {
                        echo '<p>No document requests found.</p>';
                    }
                    ?>
                </ul>
            </div>

            <div class="req-doc-payment">
                <p>Total Amount: &#8369;
                    <?php echo htmlspecialchars($request['totalPayment'] ?? '0'); ?>
                </p>
            </div>
        </section>
    </div>
</body>

</html>
