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
                <form method="POST" enctype="multipart/form-data" action="updateProfilePictureL.php" class="image-container">
                    <img src="<?php echo isset($user_data) ? htmlspecialchars($user_data['profile_picture']) : './Images/Profile.svg'; ?>" width="100" height="100" style="border-radius: 50%;"  alt="Profile Picture" id="book1">
                </form>
                <div class="prof-details">
                    <p id="Name"><?php echo isset($user_data) ? htmlspecialchars($user_data['fullname']) : ''; ?></p>
                    <p class="author">Reader</p>
                </div>
                <div class="settings-buttons">
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                        <button type="submit" class="update-btn">Change Profile Picture</button>
                </div>
            </div>
                                    
            <!-- Input section starts here -->
            <form method="POST" action="Update_Profile.php">
                <div class="input-section" id="info-box">
                    <label for="full-name">Full Name</label>
                    <div class="input-icon" id>
                        <input class="inputs" type="text" id="full-name" name="full-name" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['fullname']) : ''; ?>" readonly disabled>
                        <span class="pencil-icon" name="full-name" onclick="enableEditing('full-name')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                        </span>
                    </div>

                    <label for="username">User Name</label>
                    <div class="input-icon">
                        <input class="inputs" type="text" id="username" name="username" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['username']) : ''; ?>" readonly disabled>
                        <span class="pencil-icon" onclick="enableEditing('username')"> 
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                        </span>
                    </div>

                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope" id="ic"></i> <!-- Email icon -->
                        <input class="inputs" type="email" id="email" name="email" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['email']) : ''; ?>" readonly disabled>
                        <span class="pencil-icon" onclick="enableEditing('email')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                        </span>
                    </div>

                    <label for="phone-number">Phone Number</label>
                    <div class="input-icon">
                        <i class="fas fa-phone" id="ic"></i> <!-- Phone icon -->
                        <input class="inputs" type="tel" id="phone-number" name="phone-number" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['phoneno']) : ''; ?>" readonly disabled>
                        <span class="pencil-icon" onclick="enableEditing('phone-number')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                        </span>
                    </div>

                    <label for="address">Address</label>
                    <div class="input-icon">
                        <i class="fas fa-map-marker-alt" id="ic"></i> <!-- Address icon -->
                        <input class="inputs" type="text" id="address" name="address" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['address']) : ''; ?>" readonly disabled>
                        <span class="pencil-icon" onclick="enableEditing('address')">
                            <i class="fa fa-pencil" aria-hidden="true" id="pen-ic"></i> <!-- Pencil Icon -->
                        </span>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="password-section">
                    <label for="password">Current Password</label>
                    <div class="input-icon">
                        <input type="password" id="password" name="password" value="<?php echo isset($user_data) ? htmlspecialchars($user_data['password']) : ''; ?>" readonly>
                    </div>

                    <label for="new_password">New Password</label>
                    <div class="input-icon">
                        <input type="password" id="new-password" name="new-password">
                    </div>

                    <label for="confirm_password">Confirm New Password</label>
                    <div class="input-icon">
                        <input type="password" id="confirm-password" name="confirm-password">
                    </div>
                        
                    <div class="SaveCancel-btn">
                        <button class="usersave-btn" type="submit">Save</button>
                        <button class="userupdate-btn" onclick="disableEdit()" type="reset" id="cancelsetbtn">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
