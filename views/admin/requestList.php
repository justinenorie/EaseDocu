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
                <tbody id="request-list">
                    <!-- Automatic display all the Table Data Here -->
                </tbody>
            </table>

        </div>
    </div>
    <script>
        // Function to load JSON data and insert it into the table
        async function loadStudentData() {
            try {
                const response = await fetch('../../data/requestData.json');
                const students = await response.json();
                const tableBody = document.getElementById('request-list');

                students.forEach(student => {
                    // Create the data row for each student
                    const row = document.createElement('tr');
                    row.innerHTML = `
            <td class="req-datalist">${student.name}</td>
            <td class="req-datalist">${student.studentID}</td>
            <td class="req-datalist">${student.date}</td>
            <td class="req-datalist">${student.totalPayment}</td>
        `;

                    // Determine icon sources based on the status
                    const unpaidIcon = student.status === 'Unpaid' ?
                        '../../public/images/icons/warning.png' :
                        '../../public/images/icons/done-circle.png';
                    const paidIcon = student.status === 'Paid' ?
                        '../../public/images/icons/dollar-sign.png' :
                        student.status === 'Process' || student.status === 'Finished' ?
                        '../../public/images/icons/done-circle.png' :
                        '../../public/images/icons/standby-circle.png';
                    const processIcon = student.status === 'Process' ?
                        '../../public/images/icons/data-processing.png' :
                        student.status === 'Finished' ?
                        '../../public/images/icons/done-circle.png' :
                        '../../public/images/icons/standby-circle.png';
                    const finishedIcon = student.status === 'Finished' ?
                        '../../public/images/icons/checked.png' :
                        '../../public/images/icons/standby-circle.png';

                    // Count occurrences of each document
                    const documentCounts = student.requestedDocument.reduce((acc, document) => {
                        acc[document] = (acc[document] || 0) + 1;
                        return acc;
                    }, {});

                    // Create a hidden confirmation status row
                    const confirmationRow = document.createElement('tr');
                    confirmationRow.classList.add('confirmation-status');
                    confirmationRow.style.display = 'none'; 
                    confirmationRow.innerHTML = `
                        <td class="req-data" colspan="4">
                            <div class="status-details">
                                <h3>Request Status: ${student.status}</h3>
                                <div class="req-container">
                                    <div class="reqstatus-line">
                                        <div class="reqstatus-name unpaid">
                                            <img class="icons" src="${unpaidIcon}" alt="Unpaid Icon">
                                            <p>Unpaid</p>
                                        </div>
                                        <div class="reqstatus-name paid">
                                            <img class="icons" src="${paidIcon}" alt="Paid Icon">
                                            <p>Paid</p>
                                        </div>
                                        <div class="reqstatus-name process">
                                            <img class="icons" src="${processIcon}" alt="Process Icon">
                                            <p>Processing</p>
                                        </div>
                                        <div class="reqstatus-name finished">
                                            <img class="icons" src="${finishedIcon}" alt="Finished Icon">
                                            <p>Ready for Pick Up</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="summary-container"> 
                                    <h3>Request Summary</h3>
                                    <div class="requested-documents">
                                        <ul>
                                            ${Object.entries(documentCounts)
                                                .map(([document, count]) => `<li>${count > 1 ? `x${count}` : ''} ${document} </li>`)
                                                .join('')}
                                        </ul>
                                        <p>Total Payment: ${student.totalPayment}</p>
                                    </div>
                                    <button onclick="confirmPayment()">Confirm Payment</button>
                                </div>
                            </div>
                        </td>
                    `;

                    

                    // Add click event to toggle the confirmation status row
                    row.addEventListener('click', () => {
                        if (confirmationRow.classList.contains('show')) {
                            confirmationRow.classList.remove('show');
                            confirmationRow.classList.add('hide');
                            setTimeout(() => {
                                confirmationRow.style.display = 'none';
                                confirmationRow.classList.remove('hide');
                            }, 500);
                        } else {
                            confirmationRow.style.display = 'table-row';
                            confirmationRow.classList.add('show');
                        }
                    });

                    // Append both the data row and the confirmation row to the table
                    tableBody.appendChild(row);
                    tableBody.appendChild(confirmationRow);
                });
            } catch (error) {
                console.error('Error loading student data:', error);
            }

        }
        // Function to simulate payment confirmation
        function confirmPayment() {
            alert('Payment confirmed!');
        }

        window.onload = loadStudentData;

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
    </script>
</body>

</html>