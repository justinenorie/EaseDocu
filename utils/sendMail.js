const nodemailer = require('nodemailer');

const sendEmail = async (options) => {
    try {
        const transporter = nodemailer.createTransport({
            service: 'gmail',
            auth: {
                user: process.env.EMAIL_USER,
                pass: process.env.EMAIL_PASSWORD,
            },
            tls: {
                rejectUnauthorized: false,
            },
        });

        const mailOptions = {
            from: process.env.EMAIL_USER,
            to: options.email,
            subject: options.subject,
            html: options.html || undefined,
        };

        const info = await transporter.sendMail(mailOptions);
        console.log(`Email sent successfully: ${info.response}`);
    } catch (error) {
        console.error('Email Sending Error:', error.message);
        throw new Error(`Email could not be sent: ${error.message}`);
    }
};

module.exports = sendEmail;
