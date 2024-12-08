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
    <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
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

            <!-- TODO: Fetch each Conversation here-->
            
            <!-- 
            html Structure
            * report - div
            * report-info - div
            * report-name - h3
            * report-text - p
            * report-date - span
            * a with img

            id to fetch
            * sender-name
            * convo-status
            * sender-message
            * sender-timestamp 
            -->

            <div class="report-list">
                <!-- Sample Structure -->
                <div class="report" onclick="openChatModal('Marc Jan Banzal', '22-01820')">
                    <div class="report-info">
                        <h3 class="report-name" id="sender-name">Marc Jan Banzal - 22-01820
                            <span class="report-status active-status" id="convo-status">Active</span>
                        </h3>
                        <p class="report-text" id="sender-message">Pa follow up po pl ang tagal ko na nagaanta . . .</p>
                        <span class="report-date" id="sender-timestamp">11/9/24 10:33am</span>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function openChatModal(name, studentNumber, message, date) {
            document.getElementById('chatSenderInfo').innerText = `${name}\n${studentNumber}`;
            const chatBody = document.getElementById('chatBody');
            const chatInput = document.getElementById('chatInput');
            chatBody.innerHTML = '';

            // Add the initial message (if any)
            if (message) {
                const initialMessage = document.createElement('div');
                initialMessage.classList.add('chat-message', 'received');
                initialMessage.innerHTML = `<p>${message}</p><span>${date}</span>`;
                chatBody.appendChild(initialMessage);
            }

            document.body.classList.add('modal-open');
        }

        function closeChatModal() {
            document.body.classList.remove('modal-open');
            document.getElementById('chatBody').innerHTML = ''; // Clear chat history
        }

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    
        // Initialize Socket.IO and connect to the server
        const socket = io('http://localhost:4000'); // Connect to the Socket.IO server
        const username = '<?php echo $_SESSION['admin']; ?>'; // Admin username from session

        // When connected to the server
        socket.on('connect', () => {
            console.log('Connected to chat server as admin');
            socket.emit('auth', { username });
        });

        // Receive chat messages
        socket.on('chat', (data) => {
            const {
                from,
                message,
                time
            } = data;
            
            // Display received messages
            const messageElement = document.createElement('div');
            messageElement.classList.add('chat-message', 'received');
            messageElement.innerHTML = `<p>${message}</p><span>${from} | ${time}</span>`;
            chatBody.appendChild(messageElement);
            chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll to the latest message
        });

        // Send a chat message
        function sendMessage() {
            const message = chatInput.value.trim();
            const recipientInfo = document.getElementById('chatSenderInfo').innerText.split('\n');
            const recipient = recipientInfo[1]; // Assuming the student number is used as the recipient identifier

            if (message) {
                const time = new Date().toLocaleTimeString();
                socket.emit('chat', {
                    from: username,
                    to: recipient,
                    message,
                    time
                });
                chatInput.value = ''; // Clear the input field

                // Display sent message
                const chatBody = document.getElementById('chatBody');
                const messageElement = document.createElement('div');
                messageElement.classList.add('chat-message', 'sent');
                messageElement.innerHTML = `<p>${message}</p>
                <div class="message-time">${time}</div>`;
                chatBody.appendChild(messageElement);

                chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll to the latest message
            }
        }

        // Handle disconnect
        socket.on('disconnect', () => {
            console.log('Disconnected from chat server');
            const chatBody = document.getElementById('chatBody');
            const messageElement = document.createElement('div');
            messageElement.classList.add('chat-message', 'system');
            messageElement.innerHTML = `<p>Disconnected from chat server. Please refresh the page.</p>`;
            chatBody.appendChild(messageElement);
        });
    </script>

</body>

</html>