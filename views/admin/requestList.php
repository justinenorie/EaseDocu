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
                <!-- <img src="" alt="search-icon"> -->
                <h2>Search</h2>
            </div>

            <div class="filters">
                <nav>
                    <ul>
                        <li><img class="icons" src="path/to/unpaid-icon.png" alt="Unpaid Icon"><a href="#">Unpaid</a></li>
                        <li><img class="icons" src="path/to/paid-icon.png" alt="Paid Icon"><a href="#">Paid</a></li>
                        <li><img class="icons" src="path/to/process-icon.png" alt="Process Icon"><a href="#">Process</a></li>
                        <li><img class="icons" src="path/to/finished-icon.png" alt="Finished Icon"><a href="#">Finished</a></li>
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
                // Fetch data from JSON file
                const response = await fetch('../../data/requestData.json');
                const students = await response.json();
                const tableBody = document.getElementById('request-list');

                //Get all the data from students
                students.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.name}</td>
                        <td>${student.studentID}</td>
                        <td>${student.date}</td>
                        <td>${student.totalPayment}</td>
                    `;
                    // This is where to list it in the table called "request-list"
                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error('Error loading student data:', error);
            }
        }
        // Call the function to load the data 
        window.onload = loadStudentData;
    </script>
</body>

</html>