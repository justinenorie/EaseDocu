const mongoose = require('mongoose');

const MessageSchema = new mongoose.Schema({
    sender: { type: String, required: true },
    message: { type: String, required: true },
    timestamp: { type: Date, default: Date.now },
});

const ChatConversationSchema = new mongoose.Schema({
    chatStatus: { type: String, default: 'open' },
    participants: [{ type: String, required: true }],
    messages: [MessageSchema],
    createdAt: { type: Date, default: Date.now },
    updatedAt: { type: Date, default: Date.now },
});

module.exports = mongoose.model('ChatConversation', ChatConversationSchema, 'chatConversations');
