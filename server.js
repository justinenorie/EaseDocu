const express = require('express');
const http = require('http');
const socketio = require('socket.io');

const app = express();
const server = http.createServer(app);

// Setup Socket.IO with CORS
const io = socketio(server, {
    cors: {
        origin: "http://localhost:3000", // Allow your client origin
        methods: ["GET", "POST"]
    }
});

// Handle WebSocket connections
io.on('connection', (socket) => {
    console.log('New WebSocket connection');

    // Welcome message to the connected client
    socket.emit('message', 'Welcome to the chat!');

    // Notify others that a user joined
    socket.broadcast.emit('message', 'A new user has joined the chat.');

    // Handle incoming chat messages
    socket.on('chatMessage', (msg) => {
        // Broadcast the message to all clients
        io.emit('message', msg);
    });

    // Notify others when a user disconnects
    socket.on('disconnect', () => {
        io.emit('message', 'A user has left the chat.');
    });
});

// Start the server
const PORT = 4000;
server.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});