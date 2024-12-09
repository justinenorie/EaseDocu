// import {sendEmail} from '../EaseDocu/api/SendEmail';

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
const authRoutes = require('./routes/authRoutes')

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

//Send Email API
const { sendEmail } = require('./api/SendEmail');

// Middleware setup
app.use(cors());
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.static(path.join(__dirname, "views")));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.use("/api/auth", authRoutes);

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
                _id: user._id,
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

app.get("/api/auth/reset-password/:resetToken", (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'student', 'resetPassword.html'));
});

app.post('/checkExistingRequests', async (req, res) => {
    const { studentID } = req.body;

    if (!studentID) {
        return res.status(400).json({ success: false, message: 'Student ID is required' });
    }

    try {
        // Find any requests for the student that are not completed
        const existingRequests = await DocumentRequest.find({ 
            studentID: studentID, 
            status: { $nin: ['Completed', 'Rejected'] } // Exclude completed or rejected requests
        });

        // If there are existing requests, return details
        if (existingRequests.length > 0) {
            return res.json({
                hasPendingRequests: true,
                existingRequests: existingRequests.map(req => ({
                    requestId: req._id,
                    requestedDocuments: req.requestedDocument,
                    date: req.date,
                    status: req.status || 'Pending'
                }))
            });
        }

        // No existing active requests found
        res.json({
            hasPendingRequests: false
        });
    } catch (error) {
        console.error('Error checking existing requests:', error);
        res.status(500).json({
            success: false,
            message: 'Error checking existing requests',
            error: error.message
        });
    }
});


//TODO: Conversation database
const ChatConversation = require('./models/chatConversation');
// Create or fetch a conversation
app.post('/conversation', async (req, res) => {
    const { participants } = req.body;

    if (!participants || participants.length < 2) {
        return res.status(400).json({ success: false, message: 'Participants are required' });
    }

    try {
        // Check if a conversation already exists
        let conversation = await ChatConversation.findOne({ participants: { $all: participants } });

        // If not, create a new one
        if (!conversation) {
            conversation = new ChatConversation({ participants });
            await conversation.save();
        }

        res.json({ success: true, conversation });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Add a message to a conversation
app.post('/conversation/message', async (req, res) => {
    const { conversationId, sender, message } = req.body;

    if (!conversationId || !sender || !message) {
        return res.status(400).json({ success: false, message: 'All fields are required' });
    }

    try {
        const conversation = await ChatConversation.findById(conversationId);

        if (!conversation) {
            return res.status(404).json({ success: false, message: 'Conversation not found' });
        }

        // Add message to the conversation
        conversation.messages.push({ sender, message });
        conversation.updatedAt = Date.now();

        await conversation.save();

        res.json({ success: true, conversation });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Fetch a conversation by ID
app.get('/conversation/:id', async (req, res) => {
    const { id } = req.params;

    try {
        const conversation = await ChatConversation.findById(id);

        if (!conversation) {
            return res.status(404).json({ success: false, message: 'Conversation not found' });
        }

        res.json({ success: true, conversation });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});

// Fetch all conversations for a participant
app.get('/conversations', async (req, res) => {
    const { participant } = req.query;

    if (!participant) {
        return res.status(400).json({ success: false, message: 'Participant is required' });
    }

    try {
        const conversations = await ChatConversation.find({ participants: participant });

        res.json({ success: true, conversations });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});



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


