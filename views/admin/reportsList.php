<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReportList</title>
    <link rel="stylesheet" href="styles/report.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
        <img src="../../public/images/icons/logo-white.png" class="logo">
        <h1 class="title">EaseDocu</h1>
        <div class="navbar-buttons">
            <a href="report.php">
                <h2>Request</h2>
            </a>
            <a href="concern.php">
                <h2>Reports</h2>
            </a>
        </div>
        <div class="profile-section">
            <img src="../../public/images/icons/profile.png" class="profile">
            <span class="admin-text">
                <h2>Admin</h2>
            </span>
        </div>
    </div>

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

        // Send a message (for demo purposes)
        function sendMessage() {
            const messageInput = document.getElementById('chatInput');
            const message = messageInput.value.trim();
            if (message) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('chat-message', 'sent');
                messageDiv.innerHTML = `<p>${message}</p><span>Just now</span>`;
                document.getElementById('chatBody').appendChild(messageDiv);
                messageInput.value = ''; // Clear the input after sending
                // Scroll to the bottom for new message
                document.getElementById('chatBody').scrollTop = document.getElementById('chatBody').scrollHeight;
            }
        }
    </script>
</body>

</html>