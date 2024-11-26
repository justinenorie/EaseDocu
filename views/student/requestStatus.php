<?php
    require '../../views/components/topBarStudent.php';
    require_once '../../views/components/progressIndicator.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Status</title>
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
                        renderProgressIndicator($currentStatus, $statusSteps);
                        renderStatusMessage($currentStatus);
                    ?>
                </div>

                <div class="line-breaker"></div>

                <div class="req-doc-description">
                    <h2>Document Requested</h2>
                </div>

                <div class="req-doc-order">
                    <ul>
                        <?php
                            include '../components/request-status/requestedDocuments.php';
                            echo renderDocumentsList();
                        ?>
                    </ul>
                </div>

                <div class="req-doc-payment">
                    <p>Total Amount:</p>
                </div>
            </section>
    

        </form>
    </div>
</body>

</html>