<?php
require '../../controller/RequestDataController.php';
$controller = new EaseDocuController();
$documentRequests = $controller->getAllDocumentRequests(); // Fetch all the document requests
$documentList = $controller->getAllDocuments(); //Fetch all the document List
$requestByStudentId = $controller->getRequestByStudentId($studentId);

// Handle status update when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentID'])) {
    // Determine the new status based on the current status
    $newStatus = $_POST['currentStatus'] === 'Unpaid' ? 'Paid'
        : ($_POST['currentStatus'] === 'Paid' ? 'Process' : 'Finished');

    // Update the status in the database
    $updateRequestStatus = $controller->updateDocumentRequestStatus($_POST['studentID'], $newStatus);

    // Check if the update was successful before redirecting
    if ($updateRequestStatus) {
        // Redirect to the same page to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        
    }
}

//Filter Request Later
// $filter = $_GET['filter'] ?? null; // Example: Capture filter from URL
// if ($filter) {
//     $filteredRequests = array_filter($documentRequests, function($request) use ($filter) {
//         return $request['status'] === $filter;
//     });
// } else {
//     $filteredRequests = $documentRequests;
// }
// echo '<pre>';
// print_r($documentRequests);
// echo '</pre>';
// exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Reports</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/requestList.css">
</head>

<body>
    <?php
    require '../../views/components/topBarAdmin.php';
    ?>
    <div class="container">
        <div class="title">
            <h1>LIST OF DOCUMENT REQUEST</h1>
        </div>

        <div class="categorize-panel">
            <div class="search-bar">
                <img src="../../public/images/icons/search.png" alt="search-icon">
                <input type="text" class="search-input" id="search-input" placeholder="Search">
            </div>

            <div class="filters">
                <h2>Filters</h2>
                <nav>
                    <ul>
                        <li><a href="#"><img class="icons" src="../../public/images/icons/warning.png" alt="Unpaid Icon">UNPAID</a></li>
                        <li><a href="#"><img class="icons" src="../../public/images/icons/dollar-sign.png" alt="Paid Icon">PAID</a></li>
                        <li><a href="#"><img class="icons" src="../../public/images/icons/data-processing.png" alt="Process Icon">PROCESS</a></li>
                        <li><a href="#"><img class="icons" src="../../public/images/icons/checked.png" alt="Finished Icon">FINISHED</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="list-of-requests">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Date</th>
                        <th>Total Payment</th>
                    </tr>
                </thead>
                <!-- Data Request Fetches Here -->
                <tbody id="request-list">
                    <?php foreach ($documentRequests as $request): ?>
                        <!-- request-list row -->
                        <tr data-id="<?= $request['_id'] ?>" class="data-row">
                            <td class="req-datalist"><?= htmlspecialchars($request['name']) ?></td>
                            <td class="req-datalist"><?= htmlspecialchars($request['studentID']) ?></td>
                            <td class="req-datalist"><?= htmlspecialchars($request['date']) ?></td>
                            <td class="req-datalist">P<?= number_format($request['totalPayment'], 2) ?></td>
                        </tr>

                        <?php
                        // Icons
                        $unpaidIcon = $request['status'] === 'Unpaid' ?
                            '../../public/images/icons/warning.png' :
                            '../../public/images/icons/done-circle.png';

                        $paidIcon = $request['status'] === 'Paid' ?
                            '../../public/images/icons/dollar-sign.png' : ($request['status'] === 'Process' || $request['status'] === 'Finished' ?
                                '../../public/images/icons/done-circle.png' :
                                '../../public/images/icons/standby-circle.png');

                        $processIcon = $request['status'] === 'Process' ?
                            '../../public/images/icons/data-processing.png' : ($request['status'] === 'Finished' ?
                                '../../public/images/icons/done-circle.png' :
                                '../../public/images/icons/standby-circle.png');

                        $finishedIcon = $request['status'] === 'Finished' ?
                            '../../public/images/icons/checked.png' :
                            '../../public/images/icons/standby-circle.png';
                        ?>
                        <!-- Confirmation Status Row -->
                        <!-- TODO: remove class="show" from tr -->
                        <!-- TODO: change style: table-row to none from tr -->
                        <tr class="confirmation-status" id="confirmation-<?= $request['_id'] ?>" style="display: none;">
                            <td class="req-data" colspan="4">
                                <div class="status-details">
                                    <h3>Request Status: <?= htmlspecialchars($request['status']) ?></h3>
                                    <div class="req-container">
                                        <div class="reqstatus-line">
                                            <div class="reqstatus-name unpaid">
                                                <img class="icons" src=<?= $unpaidIcon ?> alt="Unpaid Icon">
                                                <p>Unpaid</p>
                                            </div>
                                            <div class="reqstatus-name paid">
                                                <img class="icons" src=<?= $paidIcon ?> alt="Paid Icon">
                                                <p>Paid</p>
                                            </div>
                                            <div class="reqstatus-name process">
                                                <img class="icons" src=<?= $processIcon ?> alt="Process Icon">
                                                <p>Processing</p>
                                            </div>
                                            <div class="reqstatus-name finished">
                                                <img class="icons" src=<?= $finishedIcon ?> alt="Finished Icon">
                                                <p>Ready for Pick Up</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="summary-container">
                                        <h3>Request Summary</h3>
                                        <div class="requested-documents">
                                            <?php
                                            // Safely convert requestedDocument to a PHP array
                                            $requestedDocuments = isset($request['requestedDocument']) ? (array)$request['requestedDocument'] : [];

                                            if (!empty($requestedDocuments)):
                                                // Count occurrences of each document
                                                $documentCounts = array_count_values($requestedDocuments);
                                            ?>

                                                <?php foreach ($documentCounts as $document => $count): ?>
                                                    <p><?= htmlspecialchars($count) ?>x <?= htmlspecialchars($document) ?></p>
                                                <?php endforeach; ?>

                                            <?php else: ?>
                                                <p>No documents requested.</p>
                                            <?php endif; ?>
                                            <p><strong>Total Payment:</strong> <strong class="prices">P<?= number_format($request['totalPayment'], 2) ?></strong></p>
                                        </div>
                                        <?php
                                        // Changing the text of button based on the status
                                        // If for Unpaid
                                        $confirmBtn = $request['status'] === 'Unpaid' ? 'Confirm Payment'
                                            // Else for Paid
                                            : ($request['status'] === 'Paid' ? 'Confirm to Process'
                                                //Else for Process
                                                : ($request['status'] === 'Process' ? 'Confirm Finished' : null));

                                        //ifFinish the button will display block
                                        $ifFinish = $request['status'] === 'Finished' ? "'display: none;'" : "'display: block;'";
                                        ?>

                                        <!-- // Form to handle status update -->
                                        <!-- TODO: Add a Confirmation Yes or No -->
                                        <form id="status-update-form" method="POST" style=<?= $ifFinish ?>>
                                            <input type="hidden" name="studentID" value="<?= htmlspecialchars($request['studentID']) ?>">
                                            <input type="hidden" name="currentStatus" value="<?= htmlspecialchars($request['status']) ?>">
                                            <button class="confirm-btn"><?= $confirmBtn ?></button>
                                        </form>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Select all filter items
        document.querySelectorAll('.filters nav ul li').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.filters nav ul li').forEach(li => li.classList.remove('active'));
                // Add active class to the clicked item
                item.classList.add('active');
            });
            // Initialization active for unpaid
            if (item.textContent.includes('UNPAID')) {
                item.classList.add('active');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Select all data rows
            const dataRows = document.querySelectorAll('.data-row');
            dataRows.forEach(row => {
                const requestId = row.getAttribute('data-id'); // Get the request ID
                const confirmationRow = document.getElementById(`confirmation-${requestId}`);

                // Add click event to toggle confirmation row
                row.addEventListener('click', () => {
                    const confirmationRows = document.querySelectorAll('.confirmation-status');
                    confirmationRows.forEach(row => {
                        if (row !== confirmationRow && row.classList.contains('show')) {
                            row.classList.remove('show');
                            row.classList.add('hide');
                            setTimeout(() => {
                                row.style.display = 'none';
                                row.classList.remove('hide');
                            }, 500);
                        }
                    });
                    if (confirmationRow.classList.contains('show')) {
                        confirmationRow.classList.remove('show');
                        confirmationRow.classList.add('hide');
                        setTimeout(() => {
                            confirmationRow.style.display = 'none';
                            confirmationRow.classList.remove('hide');
                        }, 500);
                        // console.log('Confirmation status hidden:', requestId);
                    } else {
                        confirmationRow.style.display = 'table-row';
                        confirmationRow.classList.add('show');
                        // console.log('Confirmation status shown:', requestId);
                    }
                });
            });
        });
    </script>
</body>

</html>