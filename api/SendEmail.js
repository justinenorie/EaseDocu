const nodemailer = require('nodemailer');
require('dotenv').config();

const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASSWORD,
    },
});

/**
 * Function to send an email
 * @param {string} recipientEmail - The recipient's email address
 * @param {string} status - The recipient's status
 * @param {string} appointment - The appointment date and time
 * @returns {Promise} - Resolves if the email is sent successfully, rejects if there is an error
 */

const sendEmail = (recipientName, recipientEmail, status, appointment) => {
    return new Promise((resolve, reject) => {
        // Convert 24-hour time to 12-hour time with AM/PM
        const formatTime = (appointment) => {
            const [date, time] = appointment.split(" "); // Split date and time
            const [hours, minutes] = time.split(":").map(Number); // Get hours and minutes
            const amPm = hours >= 12 ? "PM" : "AM"; // Determine AM/PM
            const formattedHours = hours % 12 || 12; // Convert to 12-hour format (0 becomes 12)
            const formattedTime = `${formattedHours}:${minutes.toString().padStart(2, "0")} ${amPm}`;
            return `${date} ${formattedTime}`;
        };

        const formattedAppointment = appointment ? formatTime(appointment) : "N/A";

        // Dynamic subject and text based on the status
        const subjectMap = {
            paid: "Confirmed Payment!",
            process: "Your Request is on the Process now!",
            ready: "Your Request Document is Ready for Pickup!",
        };

        const textMap = {
            paid: `Hello ${recipientName},\n\nWe have received your payment. Please wait to process your request. \n\nThank you!`,
            process: `Hello ${recipientName},\n\nYour request is now being processed. Please wait for further updates. `,
            ready: `Hello ${recipientName},\n\nYour requested document is ready for pickup. Please visit us to our CDM Registrar office to get your requested document. 
            \n\nPlease bring your payment receipt. 
            \n\nScheduled appointment: ${formattedAppointment}\n\nThank you!`,
        };

        const mailOptions = {
            from: process.env.EMAIL_USER,
            to: recipientEmail,
            subject: subjectMap[status] || "Status Update",
            text: textMap[status] || `Hello ${recipientName},\n\nYour appointment status is "${status}".\nScheduled appointment: ${formattedAppointment}.\n\nThank you!`,
        };

        transporter.sendMail(mailOptions, (error, info) => {
            if (error) {
                reject(error);
            } else {
                resolve(info.response);
            }
        });
    });
};

module.exports = { sendEmail };
