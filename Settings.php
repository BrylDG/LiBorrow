<?php 
session_start(); // Start the session
include('connection.php'); // Include your connection file

if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$user_data = null;

if(isset($_SESSION['idno'])) {
    $user_id = $_SESSION['idno'];
    
    // Fetch user data based on idno from the database
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
} else {
    echo "Please log in to view your profile.";
    exit;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Section</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="./Settings.css"> <!-- Linking the CSS file -->
</head>
<body>

<div class="Scontent-box" id="content2">
    <div class="container">
        <h3 id="sett-header">User Profile</h3>
        <hr>
        <!-- Stacking 5 divs inside the Settingsbox class -->
        <div class="Settingsbox">
            <div class="Sbox global">
                <img src="./Images/Profile.svg" width="100" height="100" alt="Profile Picture" id="book1">
                <div class="prof-details">
                    <p id="Name"><?php echo isset($user_data) ? htmlspecialchars($user_data['fullname']) : ''; ?></p>
                    <p class="author">Librarian</p>
                </div>
                <div class="settings-buttons">
                    <button class="change-btn">Change Profile</button>
                </div>
            </div>
                                    
            <!-- Input section starts here -->
            <div class="input-section" id="info-box">
                <label for="fullname">Full Name</label>
                <div class="input-icon">
                    <input type="text" id="full-name" placeholder="<?php echo isset($user_data) ? htmlspecialchars($user_data['fullname']) : ''; ?>" disabled>
                    <span class="pencil-icon" name="full-name">
                        <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                    </span>
                </div>

                <label for="username">User Name</label>
                <div class="input-icon">
                    <input type="text" id="username" name="username" placeholder="<?php echo isset($user_data) ? htmlspecialchars($user_data['username']) : ''; ?>" disabled>
                    <span class="pencil-icon">
                        <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                    </span>
                </div>

                <label for="email">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope" id="ic"></i> <!-- Email icon -->
                    <input type="email" id="email" name="email" placeholder="<?php echo isset($user_data) ? htmlspecialchars($user_data['email']) : ''; ?>" disabled>
                    <span class="pencil-icon">
                        <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                    </span>
                </div>

                <label for="phone-number">Phone Number</label>
                <div class="input-icon">
                    <i class="fas fa-phone" id="ic"></i> <!-- Phone icon -->
                    <input type="tel" id="phone-number" name="phone-number" placeholder="<?php echo isset($user_data) ? htmlspecialchars($user_data['phoneno']) : ''; ?>" disabled>
                    <span class="pencil-icon">
                        <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                    </span>
                </div>

                <label for="address">Address</label>
                <div class="input-icon">
                    <i class="fas fa-map-marker-alt" id="ic"></i> <!-- Address icon -->
                    <input type="text" id="address" name="address" placeholder="<?php echo isset($user_data) ? htmlspecialchars($user_data['address']) : ''; ?>" disabled>
                                        <span class="pencil-icon">
                        <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                    </span>
                </div>

                <!-- Container for current and new password fields to be aligned horizontally -->
                <div class="password-container">
                    <div class="input-field">
                        <label for="password">Current Password</label>
                        <div class="input-icon">
                            <i class="fas fa-key" id="ic"></i> <!-- Lock icon -->
                            <input type="password" id="password" name="password" placeholder="*********" disabled>
                            <span class="pencil-icon">
                                <i class="fa fa-eye" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                            </span>
                        </div>
                    </div>

                    <div class="input-field">
                        <label for="new-password">New Password</label>
                        <div class="input-icon">
                            <i class="fas fa-key" id="ic"></i> <!-- Lock icon -->
                            <input type="password" id="new-password" name="new-password" placeholder="*****************" disabled>
                            <span class="pencil-icon">
                                <i class="fa fa-eye" aria-hidden="true" id="pass-ic"></i> <!-- Pencil Icon -->
                            </span>
                        </div>
                    </div>
                </div>

                <label for="confirm-password">Confirm Password</label>
                <div class="input-icon">
                    <i class="fas fa-key" id="ic"></i> <!-- Lock icon -->
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="*****************" disabled>
                    <span class="pencil-icon">
                        <i class="fa fa-eye" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                    </span>
                </div>
            </div>

            <div class="SaveCancel-btn">
                <button class="usersave-btn">
                    Save
                </button>
                <button class="userupdate-btn" id="cancelsetbtn">
                    Cancel
                </button>
            </div>  
        </div>
    </div>
</div>
 <script>


 </script>

</body>
</html>
