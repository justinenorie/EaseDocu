const express = require("express");
const nodemailer = require("nodemailer");

const app = express();
app.use(express.json());

// Create transporter
const transporter = nodemailer.createTransport({
    service: "gmail", // Replace with your email service
    auth: {
        user: "your-email@gmail.com",
        pass: "your-email-password",
    },
});

const sendEmail = async (req, res) => {
    const { email, subject, text } = req.body;

    try {
        await transporter.sendMail({
            from: "your-email@gmail.com",
            to: email,
            subject: subject,
            text: text,
        });

        res.status(200).send({ message: "Email sent successfully!" });
    } catch (error) {
        console.error("Error sending email:", error);
        res.status(500).send({ message: "Failed to send email", error });
    }
};

module.exports = { sendEmail };
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});

export default sendEmail;