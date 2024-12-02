const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
    username: { type: String },
    email: { type: String },
    password: { type: String },
    id: { type: String },
    age: { type: Number },
    fullname: { type: String },
});

module.exports = userSchema;