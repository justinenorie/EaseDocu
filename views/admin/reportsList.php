<!-- Session -->
<?php
//If the user is not logged in, it will redirect  the login page
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
                <!-- <span class="filter-label">COnve</span> -->
                <!-- <button class="filter-btn all">All</button>
                <button class="filter-btn open">Open</button>
                <button class="filter-btn active">Active</button>
                <button class="filter-btn closed">Closed</button> -->
            </div>

            <div class="report-list">
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
        let activeConversationId = null; // Track the currently active conversation

        document.addEventListener("DOMContentLoaded", () => {
            const reportList = document.querySelector(".report-list");

            // Fetch all conversations
            fetch("http://localhost:4000/conversations?participant=admin")
                .then((response) => response.json())
                .then((data) => {
                    if (data.success && data.conversations) {
                        reportList.innerHTML = ""; // Clear existing list
                        data.conversations.forEach((conversation) => {
                            const {
                                _id,
                                participants,
                                messages,
                                chatStatus,
                                updatedAt
                            } = conversation;

                            // Find participant other than admin
                            const student = participants.find((p) => p !== "admin");

                            // Use the latest message in conversation
                            const lastMessage = messages[messages.length - 1] || {
                                message: "No messages yet",
                                timestamp: updatedAt,
                            };

                            const html = `
                    <div class="report" onclick="openChatModal('${_id}', '${student}', '${lastMessage.message}', '${lastMessage.timestamp}')">
                        <div class="report-info">
                            <p class="report-id" style="display: none;">${_id}</p>
                            <h3 class="report-name">${student}
                                <span class="report-status active-status">${chatStatus}</span>
                            </h3>
                            <p class="report-text">${lastMessage.message}</p>
                            <span class="report-date">${new Date(lastMessage.timestamp).toLocaleString()}</span>
                        </div>
                        <a href="javascript:void(0)">
                            <img src="../../public/images/icons/chat-black.png" class="chat-icon">
                        </a>
                    </div>`;
                            reportList.innerHTML += html;
                        });
                    } else {
                        reportList.innerHTML = `<p>No conversations available</p>`;
                    }
                })
                .catch((error) => console.error("Error fetching conversations:", error));
        });

        function openChatModal(conversationId, studentName, lastMessage, lastTimestamp) {
            const chatSenderInfo = document.getElementById("chatSenderInfo");
            const chatBody = document.getElementById("chatBody");
            const chatInput = document.getElementById("chatInput");

            // Set active conversation
            activeConversationId = conversationId;
            chatSenderInfo.innerText = `${studentName}`;

            // Clear chat messages
            chatBody.innerHTML = "";

            // Fetch conversation messages
            fetch(`http://localhost:4000/conversation/${conversationId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success && data.conversation) {
                        const {
                            messages
                        } = data.conversation;

                        messages.forEach((msg) => {
                            const messageElement = document.createElement("div");
                            messageElement.classList.add("chat-message", msg.sender === "admin" ? "sent" : "received");
                            if (msg.sender === "admin") {
                                messageElement.innerHTML = `<p>${msg.message}</p><div class="message-time">${new Date(msg.timestamp).toLocaleTimeString()}</div>`;
                            } else {
                                messageElement.innerHTML = `<p>${msg.message}</p><div class="message-time">${msg.sender} | ${new Date(msg.timestamp).toLocaleTimeString()}</div>`;
                            }
                            chatBody.appendChild(messageElement);
                        });

                        chatBody.scrollTop = chatBody.scrollHeight; // Scroll to latest
                    }
                })
                .catch((error) => console.error("Error fetching messages:", error));

            document.body.classList.add("modal-open");

            // Remove any existing event listeners to prevent duplicates
            chatInput.removeEventListener("keypress", handleKeyPress);

            // Add the event listener for the new conversation
            chatInput.addEventListener("keypress", handleKeyPress);
        }

        // Keypress handler for sending messages
        function handleKeyPress(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        }

        // Send a chat message
        function sendMessage() {
            const chatInput = document.getElementById("chatInput");
            const chatBody = document.getElementById("chatBody");
            const message = chatInput.value.trim();
            const recipientInfo = document.getElementById("chatSenderInfo").innerText;

            if (message && activeConversationId) {
                fetch("http://localhost:4000/conversation/message", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            conversationId: activeConversationId,
                            sender: "admin",
                            message,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            const time = new Date().toLocaleTimeString();

                            // Emit the message via socket
                            socket.emit("chat", {
                                from: "admin",
                                to: recipientInfo,
                                message,
                                time,
                            });

                            // Append the sent message
                            const messageElement = document.createElement("div");
                            messageElement.classList.add("chat-message", "sent");
                            messageElement.innerHTML = `<p>${message}</p><div class="message-time">${time}</div>`;
                            chatBody.appendChild(messageElement);

                            chatBody.scrollTop = chatBody.scrollHeight; // Scroll to latest
                            chatInput.value = ""; // Clear input field
                        }
                    })
                    .catch((error) => console.error("Error sending message:", error));
            }
        }

        function closeChatModal() {
            document.body.classList.remove('modal-open');
            document.getElementById('chatBody').innerHTML = ''; // Clear chat history
        }



        // Initialize Socket.IO and connect to the server
        const socket = io('http://localhost:4000'); // Connect to the Socket.IO server
        const username = '<?php echo $_SESSION['admin']; ?>'; // Admin username from session

        // When connected to the server
        socket.on('connect', () => {
            console.log('Connected to chat server as admin');
            socket.emit('auth', {
                username
            });
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
            messageElement.innerHTML = `<p>${message}</p><span class="message-time">${from} | ${time}</span>`;
            chatBody.appendChild(messageElement);
            chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll to the latest message
        });




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