// const mongoose = require('mongoose');

const { default: mongoose } = require("mongoose");

const documentRequestSchema = new mongoose.Schema({
    name: { type: String, required: true },
    studentID: { type: String, required: true },
    date: { type: Date, default: Date.now },
    status: { type: String, default: "unpaid" },
    requestedDocument: { type: [String], required: true },
    totalPayment: { type: Number, required: true },
    appointmentDate: { type: Date, default: null },
    appointmentTime: { type: String, default: null },
});

// const DocumentRequest = mongoose.model('DocumentRequest', documentRequestSchema, {
//     collection: 'documentRequestList'
// });

const DocumentRequest = mongoose.model('DocumentRequest', documentRequestSchema, 'documentRequestsList');

module.exports = DocumentRequest;
