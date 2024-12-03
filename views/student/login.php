

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EaseDocu - Login</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="styles/loginStyles.css">
</head>

<body>
    <form class="login-back" action="login.php" method="post">
        <div class="login-container">
            <div class="description">
                <div class="EaseDocu">
                    <img src="../../public/images/icons/logo-white.png" alt="EaseDocu Logo" class="logo">
                    <h1>EaseDocu</h1>
                </div>
                <h2>Your hassle-free solution for quickly requesting and tracking important school documents online.</h2>
            </div>
            <div class="login-form">
                <h1>Sign in to your Account</h1>
                <p>Enter your credentials to access your account</p>
                <div class="inputs">
                    <input type="text" name="studentID" placeholder="StudentID" required>

                    <div class="password-toggle">
                        <input id="password" type="password" name="password" placeholder="Password" required>
                        <a href=""><img src="../../public/images/icons/pw-toggle-hide.png" alt="Eye Icon"></a>
                    </div>

                </div>
                <div class="remember">
                    <div class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <p><a href="#">Forgot password?</a></p>
                </div>
                <button id="submit-btn" class="btns" type="submit">Login</button>
                <div class="signupPanel">
                    <p>Don't have an account? <a href="signup.php">Sign Up Here</a></p>
                </div>
            </div>
        </div>
    </form>

    <script src="../../public/js/passToggle.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php
        // testing function lang
        function log_message($message) {
            $log_file = __DIR__ . '/login_debug.log'; 
            $timestamp = date('Y-m-d H:i:s');
            file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
        }

        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentID = $_POST['studentID'];
            $password = $_POST['password'];

            $url = 'http://localhost:4000/login';
            $data = json_encode(['studentID' => $studentID, 'password' => $password]);

            $options = [
                'http' => [
                    'header'  => "Content-Type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => $data,
                ],
            ];
            $context = stream_context_create($options);

            // Log the request data
            log_message("Sending request to $url with data: $data");

            $result = @file_get_contents($url, false, $context);

            if ($result !== FALSE) {
                $responseData = json_decode($result, true);
                log_message("Received response: " . print_r($responseData, true));

                if (isset($responseData['user'])) {
                    $_SESSION['user_studentID'] = $responseData['user']['studentID'];
                    $_SESSION['user_name'] = $responseData['user']['name'];
                    $_SESSION['user_email'] = $responseData['user']['email'];

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'documentRequest.php';
                        });
                    </script>";
                } else {
                    log_message("Login failed: Invalid credentials.");
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Login failed',
                            text: 'Invalid Student ID or password!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    </script>";
                }
            } else {
                log_message("Error: Unable to connect to the backend.");
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Server error',
                        text: 'Unable to process request!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                </script>";
            }
            exit();
        }

    ?>
</body>
</html>

// server.js

const express = require('express');
const path = require('path');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');
const http = require('http');
const socketio = require('socket.io');
const userLogin = require('./models/userLogin');

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

// MongoDB connection
// mongoose.connect('mongodb://localhost:27017/easedocu')

// Connect to MongoDB
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
                // email: user.email,
                name: user.name,
                studentID: user.studentID
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

// Start the server
const PORT = 4000;
server.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});


Query: IN LOGIN WE JUST TAKE THE STUDENT ID AND PASSWORD BUT WE NEED TO TAKE THE NAME AND EMAIL AS WELL TO DISPLAY IN THE PROFILE LATER ON AND DONT MIND THAT I WILL WORK WITH THAT LATER 