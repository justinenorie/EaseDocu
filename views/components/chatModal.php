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
        }

        .message {
            margin-bottom: 10px;
            max-width: 80%;
            padding: 8px 12px;
            border-radius: 15px;
        }

        .user-message {
            font-size: 12px;
            background: #e5e7eb;
            margin-left: auto;
            border-radius: 15px 15px 0 15px;
        }

        .admin-message {
            font-size: 12px;
            background: #044620;
            color: white;
            margin-right: auto;
            border-radius: 15px 15px 15px 0;
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
        .message-time{
            margin-top: 4px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <button class="chat-button" onclick="toggleChat()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
            Chat with Admin
        </button>

        <div class="chat-modal" id="chatModal">
            <div class="chat-header">
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
        // SAMPLE DATA ONLY
        const chatData = {
            messages: [
                {
                    timestamp: "2024-03-19T09:30:00Z",
                    sender: "user",
                    message: "Hi, I need help with my TOR request"
                },
                {
                    timestamp: "2024-03-19T09:32:00Z",
                    sender: "admin",
                    message: "Hello! I'd be happy to help. What specific information do you need about your TOR request?"
                },
                {
                    timestamp: "2024-03-19T09:30:00Z",
                    sender: "user",
                    message: "Hi, I need help with my TOR request"
                },
                {
                    timestamp: "2024-03-19T09:32:00Z",
                    sender: "admin",
                    message: "Hello! I'd be happy to help. What specific information do you need about your TOR request?"
                },
                {
                    timestamp: "2024-03-19T09:30:00Z",
                    sender: "user",
                    message: "Hi, I need help with my TOR request"
                },
                {
                    timestamp: "2024-03-19T09:32:00Z",
                    sender: "admin",
                    message: "Hello! I'd be happy to help. What specific information do you need about your TOR request?"
                },
                {
                    timestamp: "2024-03-19T09:30:00Z",
                    sender: "user",
                    message: "Hi, I need help with my TOR request"
                },
                {
                    timestamp: "2024-03-19T09:32:00Z",
                    sender: "admin",
                    message: "Hello! I'd be happy to help. What specific information do you need about your TOR request?"
                }
            ]
        };

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('chatModal');
            const chatButton = document.querySelector('.chat-button');

            // Check if the modal is active and the click is outside the modal and the chat button
            if (modal.classList.contains('active') && !modal.contains(event.target) && !chatButton.contains(event.target)) {
                modal.classList.remove('active');
            }
        });


        function toggleChat() {
            const modal = document.getElementById('chatModal');
            modal.classList.toggle('active');
            
            if (modal.classList.contains('active')) {
                loadMessages();
            }
        }

        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function loadMessages() {
            const messagesDiv = document.getElementById('chatMessages');
            messagesDiv.innerHTML = '';
            
            chatData.messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${msg.sender}-message`;
                messageDiv.innerHTML = `
                    ${msg.message}
                    <div class="message-time">${formatTime(msg.timestamp)}</div>
                `;
                messagesDiv.appendChild(messageDiv);
            });
            
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function sendMessage() {
            const input = document.querySelector('.message-input');
            const message = input.value.trim();
            
            if (message) {
                chatData.messages.push({
                    timestamp: new Date().toISOString(),
                    sender: 'user',
                    message: message
                });
                
                loadMessages();
                input.value = '';
            }
        }

        document.querySelector('.message-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
      <script src="http://localhost:4000/socket.io/socket.io.js"></script>
      <script src="../../views/student/js/chatSupport.js"></script>
</body>
</html>