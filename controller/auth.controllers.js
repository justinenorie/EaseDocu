const userSchema = require('../models/userLogin');
const sendEmail = require('../utils/sendMail');
const bcrypt = require('bcryptjs');
const crypto = require('crypto');


exports.forgotPassword = async (req, res) => {
    const { email } = req.body;

    try {
        const user = await userSchema.findOne({ email });
        if (!user) {
            return res.status(404).json({ success: false, message: 'User not found' });
        }

        // Generate reset token
        const resetToken = crypto.randomBytes(32).toString('hex');
        user.resetPasswordToken = crypto.createHash('sha256').update(resetToken).digest('hex');
        user.resetPasswordExpire = Date.now() + 1 * 60 * 60 * 1000; // 1 hour

        await user.save();

        // Create reset URL
        const resetURL = `${req.protocol}://${req.get('host')}/api/auth/reset-password/${resetToken}`;
        const message = `<!DOCTYPE html>
                        <html lang="en">
                        <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Reset Your Password</title>
                        </head>
                        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                        <div style="background: linear-gradient(to right, #4CAF50, #45a049); padding: 20px; text-align: center;">
                            <h1 style="color: white; margin: 0;">Password Reset</h1>
                        </div>
                        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <p>Hello,</p>
                            <p>We received a request to reset your password. If you didn't make this request, please ignore this email.</p>
                            <p>To reset your password, click the button below:</p>
                            <div style="text-align: center; margin: 30px 0;">
                            <a href="${resetURL}" style="background-color: #4CAF50; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Reset Password</a>
                            </div>
                            <p>This link will expire in 1 hour for security reasons.</p>
                            <p>Best regards,<br>Your App Team</p>
                        </div>
                        <div style="text-align: center; margin-top: 20px; color: #888; font-size: 0.8em;">
                            <p>This is an automated message, please do not reply to this email.</p>
                        </div>
                        </body>
                        </html>
                        `;

        // Send email
        await sendEmail({
            email: user.email,
            subject: 'Password Reset Request',
            html: message,
        });

        res.status(200).json({ success: true, message: 'Reset email sent' });
    } catch (error) {
        console.error('Forgot Password Error:', error);
        res.status(500).json({ success: false, message: 'Internal Server Error' });
    }
};

exports.resetPassword = async (req, res) => {
    const { resetToken } = req.params;
    const { password, confirmPassword } = req.body;

    try {
        const hashedToken = crypto.createHash("sha256").update(resetToken).digest("hex");

        const user = await userSchema.findOne({
            resetPasswordToken: hashedToken,
            resetPasswordExpire: { $gt: Date.now() }, // Check token validity
        });

        if (!user) {
            return res.status(400).json({ success: false, message: "Invalid or expired token" });
        }

        if (password !== confirmPassword) {
            return res.status(400).json({ success: false, message: "Passwords do not match" });
        }

        user.password = await bcrypt.hash(password, 10);
        user.resetPasswordToken = undefined;
        user.resetPasswordExpire = undefined;
        await user.save();

        const message = `<!DOCTYPE html>
                        <html lang="en">
                        <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Password Reset Successful</title>
                        </head>
                        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                        <div style="background: linear-gradient(to right, #4CAF50, #45a049); padding: 20px; text-align: center;">
                            <h1 style="color: white; margin: 0;">Password Reset Successful</h1>
                        </div>
                        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <p>Hello,</p>
                            <p>We're writing to confirm that your password has been successfully reset.</p>
                            <div style="text-align: center; margin: 30px 0;">
                            <div style="background-color: #4CAF50; color: white; width: 50px; height: 50px; line-height: 50px; border-radius: 50%; display: inline-block; font-size: 30px;">
                                ✓
                            </div>
                            </div>
                            <p>Your password has been successfully reset. You can now log in to your account</p>
                            <p>If you did not initiate this password reset, please contact our support team immediately.</p>
                            <p>Thank you for helping us keep your account secure.</p>
                            <p>Best regards,<br>Your App Team</p>
                        </div>
                        <div style="text-align: center; margin-top: 20px; color: #888; font-size: 0.8em;">
                            <p>This is an automated message, please do not reply to this email.</p>
                        </div>
                        </body>
                        </html>
                        `;

        await sendEmail({
            email: user.email,
            subject: 'Password Reset Successful',
            html: message,
        });

        return res.status(200).json({ success: true, message: "Password reset successfully" });
    } catch (error) {
        console.error("Reset Password Error:", error);
        res.status(500).json({ success: false, message: "Internal Server Error" });
    }
};
