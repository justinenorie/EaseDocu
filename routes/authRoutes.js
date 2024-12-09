const express = require("express");
const { forgotPassword, resetPassword } = require("../controller/auth.controllers");

const router = express.Router();

router.post('/forgot-password', forgotPassword);
router.post('/reset-password/:resetToken', resetPassword);

module.exports = router;