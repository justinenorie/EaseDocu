<!-- documentRequest.php -->

<?php
require __DIR__ . '/../../models/StudentModel.php';
session_start();

// Check if session exists
if (!isset($_SESSION['studentID'])) {
    header("Location: login.php"); 
    exit();
}

$studentId = $_SESSION['studentID'];
$studentModel = new StudentModel();
$studentData = $studentModel->getStudentById($studentId);

    // Access session data safely
    // isset($_SESSION['user']['key_name']) ? $_SESSION['user']['key_name'] : 'N/A';
    // echo isset($_SESSION['user']['key_name']) ? $_SESSION['user']['key_name'] : 'N/A';
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
        <form action="requestStatus.php" method="POST">
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
                <input type="submit" name="" value="Request Now" id="request-btn">
            </section>
        </form>
    </div>
</body>
<script src="../student/js/quantityButton.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('input[name="document[]"]');
    const totalElement = document.getElementById('req-doc-total');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotal);
    });

    function updateTotal() {
        let total = 0;
        const selectedCheckboxes = document.querySelectorAll('input[name="document[]"]:checked');

        selectedCheckboxes.forEach(checkbox => {
            const price = parseFloat(checkbox.getAttribute('data-price'));
            total += price;
        });

        if (totalElement) {
            totalElement.textContent = `₱${total.toLocaleString()}`;
        }
    }

    // Quantity controls
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const quantitySpan = e.target.parentElement.querySelector('.quantity');
            let quantity = parseInt(quantitySpan.textContent);

            if (e.target.textContent === '+') {
                quantity++;
            } else if (e.target.textContent === '-' && quantity > 0) {
                quantity--;
            }

            quantitySpan.textContent = quantity;
        });
    });
});

document.getElementById('request-btn').addEventListener('click', async (event) => {
    event.preventDefault(); 

    const requestedDocument = []; 
    const selectedCheckboxes = document.querySelectorAll('input[name="document[]"]:checked');

    selectedCheckboxes.forEach((checkbox) => {
        requestedDocument.push(checkbox.value);
    });

    if (requestedDocument.length === 0) {
        Swal.fire('Error', 'Please select at least one document', 'error');
        return;
    }

    const totalPayment = document.getElementById('req-doc-total').textContent.trim();  

    if (!totalPayment || totalPayment === '₱0') {
        Swal.fire('Error', 'Total payment cannot be zero', 'error');
        return;
    }

    const name = "<?php echo $userName; ?>"; 
    const studentID = "<?php echo $studentID; ?>";

    const requestData = {
        name,
        studentID,
        requestedDocument,
        totalPayment: parseFloat(totalPayment.replace('₱', '').replace(',', '')),
    };

    try {
        const response = await fetch('http://localhost:4000/submitRequest', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData),
        });

        const result = await response.json();
        if (result.success) {
            Swal.fire('Success', result.message, 'success').then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Error', result.message, 'error');
        }
    } catch (error) {
        console.error('Submission error:', error);
        Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
    }
});
</script>


</html>
