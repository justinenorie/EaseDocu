const express = require('express');
const path = require('path');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');
const userLogin = require('./models/userLogin');




const app = express();
app.use(cors());
app.use(express.static(path.join(__dirname, 'public')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

mongoose.connect('mongodb://localhost:27017/easedocu')
    .then(() => console.log('MongoDB connected'))
    .catch(err => console.log('MongoDB connection error:', err));

app.post('/login', async(req, res) => {
    const { email, password } = req.body;

    try {
        const user = await userLogin.findOne({ email });
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
                email: user.email,
                name: user.name,
                age: user.age,
                studentID: user.studentID

            },
        });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Server error', error: error.message });
    }
});
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


app.listen(4000, () => {
    console.log('Server is running on http://localhost:4000');
});