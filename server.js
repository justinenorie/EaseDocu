// import {sendEmail} from '../EaseDocu/api/SendEmail';

const express = require('express');
const path = require('path');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');
const http = require('http');
const socketio = require('socket.io');
const app = express();
const server = http.createServer(app);
const io = socketio(server, {
    cors: {
        origin: "http://localhost:3000",
        methods: ["GET", "POST"]
    }
});

//.env
require('dotenv').config()

//Schemas
const userLogin = require('./models/userLogin');
const DocumentRequest = require('./models/documentRequest');
const DocumentList = require('./models/documentList');

//Send Email API
const { sendEmail } = require('./api/SendEmail');

// Middleware setup
app.use(cors());
app.use(express.static(path.join(__dirname, 'public')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Connect to MongoDB
mongoose.connect(process.env.MongoDBTOken)
    .then(() => console.log('MongoDB connected'))
    .catch(err => console.log('MongoDB connection error:', err));


const clients = new Map(); // Track connected clients
io.on('connection', (socket) => {
    console.log('A user connected:', socket.id);

    socket.on('auth', (data) => {
        const { username } = data;
        clients.set(username, socket.id);
        console.log(`${username} authenticated.`);
    });

    socket.on('chat', (data) => {
        const { from, to, message, time } = data;
        const recipientSocketId = clients.get(to);

        if (recipientSocketId) {
            io.to(recipientSocketId).emit('chat', { from, message, time });
        } else {
            console.log(`User ${to} is not connected.`);
        }
    });

    socket.on('disconnect', () => {
        for (const [username, id] of clients.entries()) {
            if (id === socket.id) {
                clients.delete(username);
                console.log(`${username} disconnected.`);
                break;
            }
        }
    });
});


// Login route -- V2 SESSION HANDLER
app.post('/login', async (req, res) => {
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
app.post('/signup', async (req, res) => {
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
    const { name, studentID, requestedDocument, totalPayment, date, email } = req.body;

    // Check if all required fields are present
    if (!name || !studentID || !requestedDocument || !totalPayment || !date || !email) {
        return res.status(400).json({ success: false, message: 'All fields are required' });
    }

    try {
        const newRequest = new DocumentRequest({
            name,
            studentID,
            date,
            requestedDocument,
            totalPayment,
            email,
        });

        await newRequest.save();

        res.json({ success: true, message: 'Document request submitted successfully!', requestId: newRequest._id });
    } catch (error) {
        console.error(error);  // Log error to server console
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Get ng Document Request (student id gagamitin kasi walang _id foreign key sa document request)
app.get('/getDocumentRequests', async (req, res) => {
    const studentID = req.query.studentID; // Change from userId to studentID

    if (!studentID) {
        return res.status(400).json({ success: false, message: 'Student ID is required' });
    }

    try {
        const requests = await DocumentRequest.find({ studentID: studentID });
        res.json({ success: true, requests });
    } catch (error) {
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

//TODO: Conversation database



// Sending Email
app.post('/send-email', async (req, res) => {
    const { name, email, status, appointment } = req.body;

    try {
        const response = await sendEmail(name, email, status, appointment);
        res.json({ success: true, response });
    } catch (error) {
        console.error("Error sending email:", error);
        res.status(500).json({ success: false, error: error.message });
    }
});


// Start the server
const PORT = 4000;
server.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});


