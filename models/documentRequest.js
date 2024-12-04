const mongoose = require("mongoose");

const documentRequestSchema = new mongoose.Schema({
    name: { type: String, required: true },
    studentID: { type: String, required: true },
    date: { 
        type: String, 
        default: () => {
            const now = new Date();
            return now.toISOString().split('T')[0]; 
        }
    },
    status: { type: String, default: "unpaid" },
    requestedDocument: { type: [String], required: true },
    totalPayment: { type: Number, required: true },
    appointmentDate: { type: String, default: null },
    appointmentTime: { type: String, default: null },
});

const DocumentRequest = mongoose.model('DocumentRequest', documentRequestSchema, 'documentRequestsList');

module.exports = DocumentRequest;