<!-- documentRequest.php -->
<?php
require __DIR__ . '/../../models/StudentModel.php';
session_start();

if (!isset($_SESSION['studentID'])) {
    header("Location: login.php");
    exit();
}

$studentID = $_SESSION['studentID'];
$studentModel = new StudentModel();
$studentData = $studentModel->getStudentById($studentID);

require '../../views/components/topBarStudent.php';

// Fetch the document list from the server
$url = 'http://localhost:4000/getDocumentList';
$response = file_get_contents($url);
$responseData = json_decode($response, true);

// Check if the response is valid
if (is_null($responseData) || !isset($responseData['success']) || !$responseData['success']) {
    echo '<p>Error fetching document list. Please try again later.</p>';
    exit;
}

// Function to render each document list item
function renderDocumentListItem($document)
{
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
                            echo renderDocumentListItem($document); // Using the function to render each document item
                        }
                        ?>
                    </ul>

                    <!-- Added total price display -->
                    <div id="totalAmount" class="total-amount">
                        <h3>Total: ₱0</h3>
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
        event.preventDefault(); // Prevent default form submission
        const date = new Date().toISOString().split('T')[0];

        const requestedDocument = [];

        const selectedCheckboxes = document.querySelectorAll('input[name="document[]"]:checked');
        selectedCheckboxes.forEach((checkbox) => {
            const quantity = parseInt(checkbox.closest(".list-item").querySelector(".quantity").textContent);

            // Add the document to the requestedDocument array as many times as the quantity
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

        // Confirm submission
        Swal.fire({
            title: 'Review Your Request',
            html: `<p>Total Payment: ${totalPayment}</p>`,
            icon: 'info',
            confirmButtonText: 'Submit',
            showCancelButton: true,
            cancelButtonText: 'Edit',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request
                fetch('http://localhost:4000/submitRequest', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: '<?php echo $studentData['name']; ?>', // Pass user name from PHP
                            studentID: '<?php echo $studentData['studentID']; ?>', // Pass student ID from PHP
                            date,
                            requestedDocument,
                            totalPayment,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success', data.message, 'success');
                            // Redirect to the request status page
                            setTimeout(function() {
                                window.location.href = 'requestStatus.php';
                            }, 1500);
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'An error occurred while submitting your request.', 'error');
                    });
            }
        });
    });
</script>

</html>