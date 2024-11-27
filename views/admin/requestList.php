<?php
require '../../controller/FetchDataRequest.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Requests</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/requestList.css">
</head>

<body>
    <?php require '../../views/components/topBarAdmin.php'; ?>
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
                <tbody id="request-list">
                    <!-- Data will be dynamically populated here -->
                </tbody>
            </table>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/requestFunction.js"></script>
</body>

</html>