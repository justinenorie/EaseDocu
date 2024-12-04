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

    $url = 'http://localhost:4000/getDocumentList';
    $response = file_get_contents($url);
    $responseData = json_decode($response, true);

    if (is_null($responseData) || !isset($responseData['success']) || !$responseData['success']) {
        echo '<p>Error fetching document list. Please try again later.</p>';
        exit;
    }

    //  Render each document list item
    function renderDocumentListItem($document) {
        return '
        <li class="list-item">
            <div class="list-item-left">
                <input type="checkbox" 
                       class="checkbox" 
                       name="document[]" 
                       value="' . htmlspecialchars($document['document']) . '" 
                       data-price="' . htmlspecialchars($document['price']) . '"
                >
                <div class="item-icon">
                    <img src="../../public/images/icons/doc-certificate.png" alt="">
                </div>
            </div>
            <div class="list-item-right">
                <div class="upper-item">
                    <div class="item-name">
                        <h2>' . htmlspecialchars($document['document']) . '</h2>
                    </div>
                </div>
                <div class="lower-item">
                    <div class="price"><span>&#8369;</span>' . htmlspecialchars($document['price']) . '</div>
                    <div class="quantity-controls">
                        <button class="quantity-btn">-</button>
                        <span class="quantity">0</span>
                        <button class="quantity-btn">+</button>
                    </div>
                </div>
            </div>
        </li>';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Request</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/documentRequest.css">
</head>

<body>
    <div class="content-holder">
        <form id="request-form">
            <section class="req-doc">
                <div class="req-doc-description">
                    <h2>Request a Document Now!</h2>
                </div>
                <div class="req-doc-order">
                    <div id="request-summary" style="display:none;"></div>
                    <ul>
                        <?php
                            foreach ($responseData['documentList'] as $document) {
                                echo renderDocumentListItem($document); 
                            }
                        ?>
                    </ul>

                    <div id="totalAmount" class="total-amount">
                        <h3 style="display:none;">Total: ₱0</h3>
                    </div>
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
                <div class="req-doc-total" id="req-doc-total"></div>
                <input type="button" value="Request Now" id="request-btn">
            </section>
        </form>
    </div>
</body>

<script src="../student/js/quantityButton.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('request-btn').addEventListener('click', function(event) {
        event.preventDefault();
        const requestedDocument = [];

        const selectedCheckboxes = document.querySelectorAll('input[name="document[]"]:checked');
        selectedCheckboxes.forEach((checkbox) => {
            const quantity = parseInt(checkbox.closest(".list-item").querySelector(".quantity").textContent);

            for (let i = 0; i < quantity; i++) {
                requestedDocument.push(checkbox.value);
            }
        });

        const totalPaymentText = document.getElementById('req-doc-total').textContent.trim();
        const totalPayment = parseFloat(totalPaymentText.replace('Total: ₱', '').replace(',', ''));

        if (requestedDocument.length === 0) {
            Swal.fire('Error', 'Please select at least one document', 'error');
            return;
        }

        // First, check existing requests
        fetch(`http://localhost:4000/getDocumentRequests?studentID=<?php echo $studentID; ?>`)
        .then(response => response.json())
        .then(existingRequestsData => {
            // Check if there are any existing requests in unpaid or other active statuses
            const activeRequests = existingRequestsData.requests.filter(request => 
                request.status === 'unpaid' || 
                request.status === 'processing' || 
                request.status === 'paid'
            );

            if (activeRequests.length > 0) {
                // If active requests exist, show warning
                Swal.fire({
                    icon: 'warning',
                    title: 'Existing Request Detected',
                    text: 'You already have an active document request. Please complete or cancel your existing request before submitting a new one.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // If no active requests, proceed with submission confirmation
            Swal.fire({
                title: 'Review Your Request',
                html: `<p>Total Payment: ${totalPayment}</p>`,
                icon: 'info',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                cancelButtonText: 'Edit',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('http://localhost:4000/submitRequest', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: '<?php echo $userName; ?>', 
                            studentID: '<?php echo $studentID; ?>',
                            requestedDocument,
                            totalPayment,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Optional: Redirect or refresh the page
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'An error occurred while submitting your request.', 'error');
                    });
                }
            });
        })
        .catch(error => {
            Swal.fire('Error', 'Unable to check existing requests.', 'error');
        });
    });
</script>
</html>