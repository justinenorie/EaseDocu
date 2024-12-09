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
            paid: `<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Payment Received</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                color: #333;
                            }
                            .container {
                                background-color: #f4f4f4;
                                padding: 20px;
                                border-radius: 5px;
                            }
                            .header {
                                background-color: #4CAF50;
                                color: white;
                                text-align: center;
                                padding: 10px;
                                border-radius: 5px 5px 0 0;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Payment Received</h1>
                            </div>
                            <p>Hello ${recipientName},</p>
                            <p>We have received your payment. Please wait to process your request.</p>
                            <p>Thank you!</p>
                        </div>
                    </body>
                    </html>`,
            process: `<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Request Processing</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                color: #333;
                            }
                            .container {
                                background-color: #f4f4f4;
                                padding: 20px;
                                border-radius: 5px;
                            }
                            .header {
                                background-color: #2196F3;
                                color: white;
                                text-align: center;
                                padding: 10px;
                                border-radius: 5px 5px 0 0;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Request Processing</h1>
                            </div>
                            <p>Hello ${recipientName},</p>
                            <p>Your request is now being processed. Please wait for further updates.</p>
                        </div>
                    </body>
                    </html>`,
            ready: `<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Document Ready for Pickup</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                color: #333;
                            }
                            .container {
                                background-color: #f4f4f4;
                                padding: 20px;
                                border-radius: 5px;
                            }
                            .header {
                                background-color: #FF9800;
                                color: white;
                                text-align: center;
                                padding: 10px;
                                border-radius: 5px 5px 0 0;
                            }
                            .highlight {
                                background-color: #FFF3E0;
                                padding: 10px;
                                border-radius: 5px;
                                margin-top: 15px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Document Ready for Pickup</h1>
                            </div>
                            <p>Hello ${recipientName},</p>
                            <p>Your requested document is ready for pickup. Please visit our CDM Registrar office to get your requested document.</p>
                            <div class="highlight">
                                <p><strong>Please bring your payment receipt.</strong></p>
                                <p><strong>Scheduled appointment:</strong> ${formattedAppointment}</p>
                            </div>
                            <p>Thank you!</p>
                        </div>
                    </body>
                    </html>`,
        };

        const mailOptions = {
            from: process.env.EMAIL_USER,
            to: recipientEmail,
            subject: subjectMap[status] || "Status Update",
            html: textMap[status],
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
