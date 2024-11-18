<?php
include('connection.php');
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // Check if the email exists in the database
    $query = "SELECT idno, fullname FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Generate a unique reset token and expiration time (1 hour)
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 3600; // Token expires in 1 hour

        // Store the token and its expiration in the database
        $query = "INSERT INTO password_resets (email, token, expires) VALUES ('$email', '$token', '$expires')";
        if (mysqli_query($conn, $query)) {
            // Send the reset link via email
            $resetLink = "http://yourdomain.com/reset_password.php?token=$token"; // Replace with your domain
            $subject = "Password Reset Request";
            $body = "
                <html>
                    <head><title>Password Reset</title></head>
                    <body>
                        <p>Hi, {$user['fullname']}</p>
                        <p>You requested a password reset. Click the link below to reset your password:</p>
                        <p><a href='$resetLink'>$resetLink</a></p>
                        <p>This link will expire in 1 hour.</p>
                    </body>
                </html>
            ";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'tadlipmarlou@gmail.com';
                $mail->Password = '03112004';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'Liborrow');
                $mail->addAddress($email, $user['fullname']);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;

                $mail->send();
                $message = "An email has been sent to reset your password.";
            } catch (Exception $e) {
                $message = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $message = "Error processing password reset request.";
        }
    } else {
        $message = "No account found with that email address.";
    }
}
?>

<!-- HTML for Forgot Password Form -->
<form action="forgot_password.php" method="POST">
    <input type="email" name="email" required>
    <button type="submit">Send Reset Link</button>
</form>
<p><?php echo $message; ?></p>