const socket = io('http://localhost:4000');

const chatInput = document.querySelector('.message-input');
const sendButton = document.querySelector('.send-button');
const chatMessages = document.getElementById('chatMessages');

socket.on('message', (message) => {
    displayMessage(message, 'admin');
});

sendButton.addEventListener('click', sendMessage);

chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        sendMessage();
    }
});

function sendMessage() {
    const message = chatInput.value.trim();
    if (message) {
        socket.emit('chatMessage', message);

        displayMessage(message, 'user');

        chatInput.value = '';
    }
}

function displayMessage(message, sender) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}-message`;
    messageDiv.innerHTML = `
        ${message}
        <div class="message-time">${formatTime(new Date())}</div>
    `;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function formatTime(date) {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}