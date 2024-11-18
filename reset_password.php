<?php
include('connection.php');
session_start();

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists and hasn't expired
    $query = "SELECT * FROM password_resets WHERE token='$token' AND expires >= " . date("U");
    $result = mysqli_query($conn, $query);
    $resetRequest = mysqli_fetch_assoc($result);

    if ($resetRequest) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword === $confirmPassword) {
                // Update the user's password
                $email = $resetRequest['email'];
                $query = "UPDATE users SET password='$newPassword' WHERE email='$email'";

                if (mysqli_query($conn, $query)) {
                    // Delete the token from the database after resetting the password
                    $query = "DELETE FROM password_resets WHERE token='$token'";
                    mysqli_query($conn, $query);

                    $message = "Your password has been successfully reset!";
                } else {
                    $message = "Error updating password.";
                }
            } else {
                $message = "Passwords do not match!";
            }
        }
    } else {
        $message = "Invalid or expired token.";
    }
}
?>

<!-- HTML for Reset Password Form -->
<form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
    <input type="password" name="new_password" required>
    <input type="password" name="confirm_password" required>
    <button type="submit">Reset Password</button>
</form>
<p><?php echo $message; ?></p>