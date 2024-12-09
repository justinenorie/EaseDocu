<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .chat-button {
            background-color: #044620;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .chat-button:hover {
            transform: scale(1.05);
            background-color: #33BA7D;
        }

        .chat-modal {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform-origin: bottom right;
            transform: scale(0.6);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            pointer-events: none;
        }

        .chat-modal.active {
            border-radius: 12px;
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .chat-header {
            background: #044620;
            color: white;
            padding: 15px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
        }

        .chat-messages {
            max-height: 240px;
            overflow-y: auto;
            padding: 15px;

            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message {
            padding: 8px 12px;
            max-width: 80%;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.4;
            display: inline-block;
            word-wrap: break-word;
        }

        .user-message {
            font-size: 15px;
            background: #e5e7eb;
            margin-left: auto;
            border-radius: 15px 15px 0 15px;
            align-self: flex-end;
        }

        .admin-message {
            font-size: 15px;
            background: #044620;
            color: white;
            margin-right: auto;
            border-radius: 15px 15px 15px 0;
            align-self: flex-start;
        }

        .chat-input {
            padding: 15px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
        }

        .message-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            outline: none;
        }

        .send-button {
            background: #044620;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
        }

        .message-time {
            margin-top: 4px;
            font-size: 10px;
        }
    </style>
    <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
</head>

<body>
    <div class="chat-container">
        <button class="chat-button" onclick="toggleChat()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
            </svg>
            Chat with Admin
        </button>

        <div class="chat-modal" id="chatModal">
            <div class="chat-header">
                <p id="convo-id" style="display: none;"></p>
                <span>Admin Support</span>
                <button class="close-button" onclick="toggleChat()">×</button>
            </div>

            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be inserted here by JavaScript -->
            </div>
            <div class="chat-input">
                <input type="text" class="message-input" placeholder="Type your message...">
                <button class="send-button" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        const chatModal = document.getElementById('chatModal');
        const messagesDiv = document.getElementById('chatMessages');
        const messageInput = document.querySelector('.message-input');
        const convoIdElement = document.querySelector("#convo-id");
        const socket = io('http://localhost:4000'); // Connect to the Socket.IO server
        const username = '<?php echo $_SESSION['studentID']; ?>'; // Admin username from session
        const name = '<?php echo $_SESSION['name']; ?>';

        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // When connected to the server
        socket.on('connect', () => {
            console.log('Connected to chat server as student');
            socket.emit('auth', {
                username
            });
        });

        //Gumagana na
        // Toggle chat and fetch conversation
        function toggleChat() {
            chatModal.classList.toggle("active");
            messagesDiv.innerHTML = ""; // Clear previous chat messages

            fetch(`http://localhost:4000/conversations?participant=${username}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success && data.conversations.length > 0) {
                        const conversation = data.conversations[0]; // Assuming first conversation is relevant
                        const {
                            _id,
                            messages
                        } = conversation;

                        // Set conversation ID
                        convoIdElement.textContent = _id;

                        // Render messages
                        messages.forEach((msg) => {
                            const messageElement = document.createElement("div");
                            messageElement.classList.add(
                                "message",
                                msg.sender === username ? "user-message" : "admin-message"
                            );
                            
                            if (msg.sender === username) {
                                messageElement.innerHTML = `<p>${msg.message}</p><div class="message-time">${new Date(msg.timestamp).toLocaleTimeString()}</div>`;
                            } else {
                                messageElement.innerHTML = `<p>${msg.message}</p><div class="message-time">${msg.sender} | ${new Date(msg.timestamp).toLocaleTimeString()}</div>`;
                            }

                            messagesDiv.appendChild(messageElement);
                        });

                        messagesDiv.scrollTop = messagesDiv.scrollHeight; // Auto-scroll to latest message
                    } else {
                        console.error("No conversations found.");
                    }
                })
                .catch((error) => console.error("Error fetching messages:", error));
        }

        function sendMessage() {
            const message = messageInput.value.trim();
            const convoId = convoIdElement.textContent.trim(); // Get conversation ID from the element

            if (!message) {
                console.error("Message is empty. Cannot send.");
                return;
            }

            // Function to create a new conversation
            const createConversation = () => {
                return fetch("http://localhost:4000/conversation", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            participants: [username, "admin"], // Assuming the other participant is "admin"
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Set the new conversation ID
                            convoIdElement.textContent = data.conversation._id;
                            return data.conversation._id;
                        } else {
                            throw new Error("Failed to create a conversation");
                        }
                    });
            };

            // Function to send a message
            const sendChatMessage = (conversationId) => {
                fetch("http://localhost:4000/conversation/message", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            conversationId,
                            sender: username,
                            message,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Render sent message
                            const messageElement = document.createElement("div");
                            const time = new Date().toLocaleTimeString();

                            socket.emit("chat", {
                                from: username,
                                to: "admin", // Admin Session
                                message,
                                time,
                            });

                            messageElement.classList.add("message", "user-message");
                            messageElement.innerHTML = `<p>${message}</p><div class="message-time">${time}</div>`;
                            messagesDiv.appendChild(messageElement);

                            messagesDiv.scrollTop = messagesDiv.scrollHeight; // Auto-scroll to latest message
                            messageInput.value = ""; // Clear input field
                        } else {
                            console.error("Error sending message:", data.message);
                        }
                    })
                    .catch((error) => console.error("Error sending message:", error));
            };

            // Check if convoId exists, otherwise create a new conversation
            if (!convoId) {
                console.log("No conversation ID found. Creating a new conversation...");
                createConversation()
                    .then((newConvoId) => {
                        sendChatMessage(newConvoId); // Send the message after creating the conversation
                    })
                    .catch((error) => console.error("Error creating conversation:", error));
            } else {
                sendChatMessage(convoId); // Send the message directly if convoId exists
            }
        }


        //Receive Message
        socket.on('chat', (data) => {
            const {
                from,
                message,
                time
            } = data;

            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${from}-message admin-message`;
            messageDiv.innerHTML = `
                <p>${message}</p><div class="message-time">${from} | ${time}</div>`;
            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        })

        //Handle Disconnected
        socket.on('disconnect', () => {
            console.log('Disconnected from chat server');
            const chatBody = document.getElementById('chatBody');
            const messageElement = document.createElement('div');
            messageElement.classList.add('chat-message', 'system');
            messageElement.textContent = 'You have been disconnected from the chat.';
            chatBody.appendChild(messageElement);
        });
    </script>

</body>

</html>