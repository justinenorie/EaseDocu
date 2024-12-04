const mongoose = require('mongoose');

const DocumentListSchema = new mongoose.Schema({
    id: {
        type: String,
        default: ''
    },
    document: {
        type: String,
        required: true
    },
    price: {
        type: Number,
        required: true
    }
}); 

module.exports = mongoose.model('DocumentList', DocumentListSchema, 'documentList');
