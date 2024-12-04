<!-- Session -->
<?php
//If the user is not logged in, it will redirect to the login page
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: loginAdmin.php");
    exit;
}
    // echo "Current account: " . $_SESSION['admin'] . "<br>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReportList</title>
    <link rel="stylesheet" href="styles/report.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <?php require '../../views/components/topBarAdmin.php'; ?>
    <div class="container">
        <h1 class="concern">REPORT CONCERNS</h1>
        <div class="card">
            <div class="filter">
                <span class="filter-label">Filter</span>
                <button class="filter-btn all">All</button>
                <button class="filter-btn open">Open</button>
                <button class="filter-btn active">Active</button>
                <button class="filter-btn closed">Closed</button>
            </div>

            <div class="report-list">
                <div class="report" onclick="openChatModal('Marc Jan Banzal', '22-0003', 'Pa follow up po ples ang tagal ko na nagaanta . . .', '11/9/24 10:33am')">
                    <div class="report-info">
                        <h3 class="report-name">Marc Jan Banzal - 22-0003
                            <span class="report-status active-status">Active</span>
                        </h3>
                        <p class="report-text">Pa follow up po pl ang tagal ko na nagaanta . . .</p>
                        <span class="report-date">11/9/24 10:33am</span>
                    </div>
                    <a href="javascript:void(0)">
                        <img src="../../public/images/icons/chat-black.png" class="chat-icon">
                    </a>
                </div>
                <div class="report" onclick="openChatModal('Marc Jan Banzal', '22-0003', 'Pa follow up po ples ang tagal ko na nagaanta . . .', '11/9/24 10:33am')">
                    <div class="report-info">
                        <h3 class="report-name">Marc Jan Banzal - 22-0003
                            <span class="report-status active-status">Active</span>
                        </h3>
                        <p class="report-text">Pa follow up po ples ang tagal ko na nagaanta . . .</p>
                        <span class="report-date">11/9/24 10:33am</span>
                    </div>
                    <a href="javascript:void(0)">
                        <img src="../../public/images/icons/chat-black.png" class="chat-icon">
                    </a>
                </div>
                <div class="report" onclick="openChatModal('Marc Jan Banzal', '22-0003', 'Pa follow up po ples ang tagal ko na nagaanta . . .', '11/9/24 10:33am')">
                    <div class="report-info">
                        <h3 class="report-name">Marc Jan Banzal - 22-0003
                            <span class="report-status active-status">Active</span>
                        </h3>
                        <p class="report-text">Pa follow up po ples ang tagal ko na nagaanta . . .</p>
                        <span class="report-date">11/9/24 10:33am</span>
                    </div>
                    <a href="javascript:void(0)">
                        <img src="../../public/images/icons/chat-black.png" class="chat-icon">
                    </a>
                </div>
                <div class="report" onclick="openChatModal('Marc Jan Banzal', '22-0003', 'Pa follow up po ples ang tagal ko na nagaanta . . .', '11/9/24 10:33am')">
                    <div class="report-info">
                        <h3 class="report-name">Marc Jan Banzal - 22-0003
                            <span class="report-status active-status">Active</span>
                        </h3>
                        <p class="report-text">Pa follow up po ples ang tagal ko na nagaanta . . .</p>
                        <span class="report-date">11/9/24 10:33am</span>
                    </div>
                    <a href="javascript:void(0)">
                        <img src="../../public/images/icons/chat-black.png" class="chat-icon">
                    </a>
                </div>
                <div class="report" onclick="openChatModal('Marc Jan Banzal', '22-0003', 'Pa follow up po ples ang tagal ko na nagaanta . . .', '11/9/24 10:33am')">
                    <div class="report-info">
                        <h3 class="report-name">Marc Jan Banzal - 22-0003
                            <span class="report-status active-status">Active</span>
                        </h3>
                        <p class="report-text">Pa follow up po ples ang tagal ko na nagaanta . . .</p>
                        <span class="report-date">11/9/24 10:33am</span>
                    </div>
                    <a href="javascript:void(0)">
                        <img src="../../public/images/icons/chat-black.png" class="chat-icon">
                    </a>
                </div>
            </div>
        </div>

        <!-- Chat Modal -->
        <div class="chat-modal" id="chatModal">
            <div class="chat-header">
                <div class="chat-title-container">
                    <img src="../../public/images/icons/backbtn.png" alt="Back" onclick="closeChatModal()" class="backbtn">
                    <img src="../../public/images/icons/profile.png" alt="Profile" class="profile-icon">
                    <span id="chatSenderInfo"></span>
                </div>
            </div>


            <div class="chat-body" id="chatBody">
                <!-- Chat messages will go here -->
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Aa">
                <img src="../../public/images/icons/send-logo.png" alt="Send" onclick="sendMessage()" class="send-icon">
            </div>
        </div>
    </div>


    <script>
        // Open the chat modal with specific report details
        function openChatModal(name, studentNumber, message, date) {
            document.getElementById('chatSenderInfo').innerText = `${name}\n${studentNumber}`;
            const initialMessage = document.createElement('div');
            initialMessage.classList.add('chat-message', 'received');
            initialMessage.innerHTML = `<p>${message}</p><span>${date}</span>`;
            document.getElementById('chatBody').appendChild(initialMessage);
            document.body.classList.add('modal-open');
        }

        // Close the chat modal
        function closeChatModal() {
            document.body.classList.remove('modal-open');
            document.getElementById('chatBody').innerHTML = ''; // Clear chat history
        }

        //         function sendMessage() {
        //     const messageInput = document.getElementById('chatInput');
        //     const message = messageInput.value.trim();
        //     if (message) {
        //         socket.emit('chatMessage', message);


        //         const messageDiv = document.createElement('div');
        //         messageDiv.classList.add('chat-message', 'sent');
        //         messageDiv.innerHTML = `<p>${message}</p><span>Just now</span>`;
        //         document.getElementById('chatBody').appendChild(messageDiv);

        //         messageInput.value = '';

        //         document.getElementById('chatBody').scrollTop = document.getElementById('chatBody').scrollHeight;
        //     }
        // }
    </script>
    <script src="http://localhost:4000/socket.io/socket.io.js"></script>
    <script src="../../views/admin/js/chatserver.js"></script>
</body>

</html>