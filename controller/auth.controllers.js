const userSchema = require('../models/userLogin');
const sendEmail = require('../utils/sendMail');
const validator = require('validator');
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
        const resetTokenExpire = Date.now() + 1 * 60 * 60 * 1000; // 1 hour

        user.resetPasswordToken = resetToken;
        user.resetPasswordExpire = resetTokenExpire;

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
    try {
        const { resetToken } = req.params;
        const { password } = req.body;

        // Validate password length
        if (!password || !validator.isLength(password, { min: 8 })) {
            return res
                .status(400)
                .json({ success: false, message: 'Password must be at least 8 characters long.' });
        }

        // Validate password complexity
        const passwordComplexity = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordComplexity.test(password)) {
            return res.status(400).json({
                success: false,
                message: 'Password must include at least one uppercase letter, one number, and one special character.',
            });
        }

        // Check if reset token is valid and not expired
        const user = await userSchema.findOne({
            resetPasswordToken: resetToken,
            resetPasswordExpire: { $gt: Date.now() },
        });

        if (!user) {
            return res.status(404).json({ success: false, message: 'Invalid or expired token.' });
        }

        // Check if the new password is the same as the old one
        const isOldPasswordSame = await user.comparePassword(password);
        if (isOldPasswordSame) {
            return res.status(400).json({ success: false, message: 'Please enter a new password.' });
        }

        // Update password and reset token fields
        user.password = password;
        user.resetPasswordToken = undefined;
        user.resetPasswordExpire = undefined;

        await user.save();

        // Send confirmation email
        const message = `
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Password Reset Successful</title>
            </head>
            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                <h1 style="text-align: center; color: #4CAF50;">Password Reset Successful</h1>
                <p>Hello,</p>
                <p>Your password has been successfully reset. You can now log in to your account.</p>
                <div style="text-align: center; margin: 30px 0;">
                   <div style="background-color: #4CAF50; color: white; width: 50px; height: 50px; line-height: 50px; border-radius: 50%; display: inline-block; font-size: 30px;">
                      ✓
                   </div>
                </div>
                <p>If you did not initiate this password reset, please contact our support team immediately.</p>
                <p>Thank you for keeping your account secure.</p>
                <p>Best regards,</p>
                <p>Your App Team</p>
            </body>
            </html>
        `;

        await sendEmail({
            email: user.email,
            subject: 'Password Reset Successful',
            html: message,
        });

        return res.status(200).json({ success: true, message: 'Password reset successfully.' });

    } catch (error) {
        console.error('Reset Password Error:', error);
        return res.status(500).json({ success: false, message: 'Internal Server Error.' });
    }
};
