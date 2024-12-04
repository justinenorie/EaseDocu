<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); 
        exit();
    }

    $userId = $_SESSION['user_id']; 
    $studentID = $_SESSION['user_studentID'];
    $userEmail = $_SESSION['user_email'];
    $userName = $_SESSION['user_name'];

    require '../../views/components/topBarStudent.php';
    require '../../views/components/chatModal.php';

    $url = 'http://localhost:4000/getDocumentRequests?studentID=' . urlencode($studentID);
    $response = file_get_contents($url);
    $responseData = json_decode($response, true);

    $statusSteps = [
        'unpaid' => [
            'image' => '../../public/images/icons/warning.png',
            'order' => 1
        ],
        'paid' => [
            'image' => 'path/to/paid-icon.png',
            'order' => 2
        ],
        'processing' => [
            'image' => 'path/to/processing-icon.png',
            'order' => 3
        ],
        'ready' => [
            'image' => 'path/to/ready-icon.png',
            'order' => 4
        ]
    ];

    function renderProgressIndicator($currentStatus, $statusSteps) {
        $currentOrder = $statusSteps[$currentStatus]['order'];
        ?>
        <div class="status-line">
            <?php foreach ($statusSteps as $status => $info): ?>
                <?php
                $circleClass = '';
                if ($info['order'] < $currentOrder) {
                    $circleClass = 'completed';
                } elseif ($info['order'] === $currentOrder) {
                    $circleClass = 'current';
                } else {
                    $circleClass = 'upcoming';
                }
                ?>
                <div class="circle <?php echo $circleClass; ?>">
                    <?php if ($info['order'] === $currentOrder): ?>
                        <img src="<?php echo $info['image']; ?>" alt="<?php echo ucfirst($status); ?>">
                    <?php else: ?>
                        <?php echo $info['order']; ?>
                    <?php endif; ?>
                    <span class="status-label"><?php echo ucfirst($status); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    function renderStatusMessage($currentStatus, $request = null) {
        $messages = [
            'unpaid' => [
                'title' => 'Payment process expires in 24 hours',
                'description' => 'Please complete your payment to proceed with your request.'
            ],
            'paid' => [
                'title' => 'Payment successfully received',
                'description' => 'Thank you for your payment. Your request will be processed shortly.'
            ],
            'processing' => [
                'title' => 'Your document is on the process......',
                'description' => 'We are currently working on your document request.'
            ],
            'ready' => [
                'title' => 'Your document is available to pick-up',
                'description' => $request && $request['appointmentDate'] && $request['appointmentTime'] 
                    ? formatPickupDescription($request['appointmentDate'], $request['appointmentTime'])
                    : 'Your document is ready for pick-up.'
            ]
        ];
    
        $message = $messages[$currentStatus];
        ?>
        <div class="status-message">
            <h3><?php echo $message['title']; ?></h3>
            <p><?php echo $message['description']; ?></p>
        </div>
        <?php
    }
    
    // Date and Time Format to Readable
    function formatPickupDescription($dateString, $timeString) {

        $timestamp = strtotime($dateString);
        $formattedDate = date('F j, Y', $timestamp);
    
        // TODO: Format the time (assuming 24-hour format)
        $formattedTime = date('h:i A', strtotime($timeString));
    
        return "Your document is ready for pick-up on $formattedDate at $formattedTime Onwards.";
    }

    // Count Document Quantities
    function countDocumentQuantities($documents) {
        $docCount = [];
        foreach ($documents as $doc) {
            if (isset($docCount[$doc])) {
                $docCount[$doc]++;
            } else {
                $docCount[$doc] = 1;
            }
        }
        return $docCount;
    }
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
                                renderStatusMessage($request['status'], $request);
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
                                // Count the quantity of each document
                                $docCount = countDocumentQuantities($request['requestedDocument']);
                                foreach ($docCount as $docName => $quantity) {
                                    echo "
                                    <li class='list-item'>
                                        <div class='list-item-left'>
                                            <p>x$quantity</p>
                                            <div class='item-icon'>
                                                <img src='../../public/images/icons/doc-certificate.png' alt=''>
                                            </div>
                                        </div>
                                        <div class='list-item-right'>
                                            <div class='upper-item'>
                                                <div class='item-name'>
                                                    <h2>" . htmlspecialchars($docName) . "</h2>
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
