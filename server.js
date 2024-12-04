const express = require('express');
const path = require('path');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');
const http = require('http');
const socketio = require('socket.io');

const userLogin = require('./models/userLogin');
const DocumentRequest = require('./models/documentRequest');
const DocumentList = require('./models/documentList');

const app = express();
const server = http.createServer(app);
const io = socketio(server, {
    cors: {
        origin: "http://localhost:3000",
        methods: ["GET", "POST"]
    }
});

// Middleware setup
app.use(cors());
app.use(express.static(path.join(__dirname, 'public')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Connect to MongoDB
// mongoose.connect('mongodb://localhost:27017/easedocu')
mongoose.connect('mongodb+srv://easedocu:easedocu123@easecluster.6yvnz.mongodb.net/easedocu')
.then(() => console.log('MongoDB connected'))
.catch(err => console.log('MongoDB connection error:', err));

// WebSocket setup
io.on('connection', (socket) => {
    console.log('New WebSocket connection');

    socket.emit('message', 'Welcome to the chat!');

    socket.broadcast.emit('message', 'A new user has joined the chat.');

    socket.on('chatMessage', (msg) => {
        io.emit('message', msg);
    });

    socket.on('disconnect', () => {
        io.emit('message', 'A user has left the chat.');
    });
});

// Login route
app.post('/login', async(req, res) => {
    const { studentID, password } = req.body;

    try {
        const user = await userLogin.findOne({ studentID });
        if (!user) {
            return res.status(400).json({ success: false, message: 'User not found!' });
        }

        const isMatch = await user.comparePassword(password);
        if (!isMatch) {
            return res.status(400).json({ success: false, message: 'Invalid password!' });
        }

        res.json({
            success: true,
            user: {
                _id: user._id, // Ito gagamitin for Student Session
                name: user.name,
                studentID: user.studentID,
                email: user.email 
            },
        });
        
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});


// Signup route
app.post('/signup', async(req, res) => {
    const { name, studentID, email, password } = req.body;

    try {
        const existingUser = await userLogin.findOne({ email });
        if (existingUser) {
            return res.status(400).json({ success: false, message: 'Email already in use!' });
        }

        const newUser = new userLogin({ name, studentID, email, password });
        await newUser.save();

        res.json({ success: true, message: 'Signup successful!' });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Handling the POST request to submit the request
app.post('/submitRequest', async (req, res) => {
    const { name, studentID, requestedDocument, totalPayment, date } = req.body;

    // Check if all required fields are present
    if (!name || !studentID || !requestedDocument || !totalPayment || !date) {
        return res.status(400).json({ success: false, message: 'All fields are required' });
    }

    try {
        const newRequest = new DocumentRequest({
            name,
            studentID,
            date,
            requestedDocument,
            totalPayment,
        });

        await newRequest.save();

        res.json({ success: true, message: 'Document request submitted successfully!', requestId: newRequest._id });
    } catch (error) {
        console.error(error);  // Log error to server console
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Get Document List
app.get('/getDocumentList', async (req, res) => {
    try {
        const documentList = await DocumentList.find({});
        res.json({ 
            success: true, 
            documentList: documentList.length ? documentList : [] // Return empty array if no documents found
        });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Start the server
const PORT = 4000;
server.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});


