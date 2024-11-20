<?php
session_start(); // Start the session
include('connection.php'); // Include your database connection file

// Redirect to login page if not logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

$user_data = null;
$user_id = $_SESSION['idno']; // Fetch user data using session's idno

// Fetch user data from the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // When the form is submitted (non-AJAX, via POST)
    $updatedFullname = $_POST['fullname'];
    $updatedUsername = $_POST['username'];
    $updatedEmail = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $oldPassword = $_POST['old_password'];

    // Check if old password matches the current password in the database
    $sql = "SELECT password FROM users WHERE idno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (password_verify($oldPassword, $user['password'])) {
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            // Update user data along with the password
            $sql = "UPDATE users SET fullname = ?, username = ?, email = ?, password = ? WHERE idno = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $updatedFullname, $updatedUsername, $updatedEmail, $hashedPassword, $user_id);
            if ($stmt->execute()) {
                $message = "User information and password updated successfully!";
            } else {
                $message = "Error updating user information: " . $stmt->error;
            }
        } else {
            $message = "New password and confirmation do not match!";
        }
    } else {
        $message = "Old password is incorrect!";
    }
    
    $stmt->close(); // Close the statement
}

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE idno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "No user data found.";
    exit();
}
$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Section</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="./UserSettings.css"> <!-- Linking the CSS file -->
</head>
<body>

<div class="Scontent-box" id="settings-content">
    <div class="container">
        <h3 id="sett-header">User Profile</h3>
        <hr>
        <div class="Settingsbox">
            <div class="Sbox global">
                <img src="./Images/Profile.svg" width="100" height="100" alt="Profile Picture" id="book1">
                <div class="prof-details">
                    <p id="Name"><?php echo isset($user_data) ? htmlspecialchars($user_data['fullname']) : ''; ?></p>
                    <p class="author">Reader</p>
                </div>
                <div class="settings-buttons">
                    <button class="change-btn">Change Profile</button>
                </div>
            </div>

            <div class="input-section" id="info-box">
                <!-- Display messages -->
                <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
                
                <form method="POST" action="settings.php">
                    <label for="fullname">Full Name</label>
                    <div class="input-icon">
                        <input type="text" id="fullname" name="fullname" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['fullname']) : ''; ?>" readonly>
                        <button class="pencil-btn" type="button" onclick="enableEditing('fullname')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i>
                        </button>
                    </div>

                    <label for="username">User Name</label>
                    <div class="input-icon">
                        <input type="text" id="username" name="username" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['username']) : ''; ?>" readonly>
                        <button class="pencil-btn" type="button" onclick="enableEditing('username')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i>
                        </button>
                    </div>

                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope" id="ic"></i>
                        <input type="email" id="email" name="email" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['email']) : ''; ?>" readonly>
                        <button class="pencil-btn" type="button" onclick="enableEditing('email')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i>
                        </button>
                    </div>

                    <!-- Password Section -->
                    <label for="old_password">Old Password</label>
                    <div class="input-icon">
                        <input type="password" id="old_password" name="old_password" required>
                    </div>

                    <label for="new_password">New Password</label>
                    <div class="input-icon">
                        <input type="password" id="new_password" name="new_password" required>
                    </div>

                    <label for="confirm_password">Confirm New Password</label>
                    <div class="input-icon">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <!-- Additional fields can be added here as needed -->

                    <button type="submit" id="save-btn" style="display:none;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Enable the input fields when the pencil icon is clicked
function enableEditing(fieldId) {
    const inputField = document.getElementById(fieldId);
    inputField.removeAttribute('readonly');
    document.getElementById('save-btn').style.display = 'block'; // Show Save button
}
</script>

</body>
</html>
