<?php
require '../../controller/FetchDataRequest.php';
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
                <!-- TODO: Working on filter -->
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

                        <!-- Initialize Status -->
                        <?php
                        // Icons
                        $unpaidIcon = $request['status'] === 'unpaid' ?
                            '../../public/images/icons/warning.png' :
                            '../../public/images/icons/done-circle.png';
                        $paidIcon = $request['status'] === 'paid' ?
                            '../../public/images/icons/dollar-sign.png' : ($request['status'] === 'process' || $request['status'] === 'ready' ?
                                '../../public/images/icons/done-circle.png' :
                                '../../public/images/icons/standby-circle.png');
                        $processIcon = $request['status'] === 'process' ?
                            '../../public/images/icons/data-processing.png' : ($request['status'] === 'ready' ?
                                '../../public/images/icons/done-circle.png' :
                                '../../public/images/icons/standby-circle.png');
                        $finishedIcon = $request['status'] === 'ready' ?
                            '../../public/images/icons/checked.png' :
                            '../../public/images/icons/standby-circle.png';
                        ?>

                        <!-- Confirmation Status Row -->
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
                                            // Safely convert requested Document to a PHP array
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
                                        $confirmBtn = $request['status'] === 'unpaid' ? 'Confirm Payment'
                                            // Else for Paid
                                            : ($request['status'] === 'paid' ? 'Confirm to Process'
                                                //Else for Process
                                                : ($request['status'] === 'process' ? 'Confirm Finished' : null));
                                        //ifFinish the button will display block
                                        $ifFinish = $request['status'] === 'ready' ? "'display: none;'" : "'display: block;'";
                                        ?>
                                        <!-- // Form to handle status update -->
                                        <!-- TODO: Add a Confirmation Yes or No -->
                                        <!-- TODO: Add a Confirmation Modal -->
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/requestFunction.js"></script>
</body>

</html>