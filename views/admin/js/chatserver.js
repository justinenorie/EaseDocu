const socket = io('http://localhost:4000');

const chatInput = document.getElementById('chatInput');
const sendButton = document.querySelector('.send-icon');
const chatBody = document.getElementById('chatBody');

socket.on('message', (message) => {
    displayMessage(message, 'received');
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

        displayMessage(message, 'sent');

        chatInput.value = '';
    }
}

function displayMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('chat-message', type);
    messageDiv.innerHTML = `<p>${message}</p><span>Just now</span>`;
    chatBody.appendChild(messageDiv);
}