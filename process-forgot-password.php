<?php
session_start();
include 'includes/db.php'; // Your DB connection file

// Load PHPMailer classes (adjust path if needed)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['forgot_error'] = 'Please enter a valid email address.';
        header('Location: forgot-password.php');
        exit;
    }

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Generate reset token and expiry time
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token and expiry to the database
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $token, $expires, $email);
        $updateStmt->execute();

        // Prepare reset link
        $resetLink = "http://localhost/reset-password.php?token=$token"; // Make sure to replace yourdomain.com

        // Send reset email using PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Use Gmail SMTP or any other SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yash.767170@gmail.com'; // Your Gmail address
            $mail->Password   = 'izxh tgbc rgae lczn'; // Gmail App Password (Not your Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('yash.767170@gmail.com', 'Medi-O'); // Your email
            $mail->addAddress($email); // Recipient's email address

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password - Medi-O';
            $mail->Body    = "
                <p>Click the link below to reset your password:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>This link will expire in 1 hour.</p>
            ";

            // Send email
            $mail->send();
            $_SESSION['forgot_success'] = 'Password reset link has been sent to your email.';
        } catch (Exception $e) {
            $_SESSION['forgot_error'] = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['forgot_error'] = 'Email not found.';
    }

    // Redirect back to the forgot-password page with message
    header('Location: forgot-password.php');
    exit;
}
?>
