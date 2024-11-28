<?php
session_start(); // Start the session
include('connection.php'); // Include your database connection file

// Redirect to login page if not logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['idno']; // Fetch user data using session's idno

// Check if a file is uploaded
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $file_name = $_FILES['profile_picture']['name'];
    $file_tmp_name = $_FILES['profile_picture']['tmp_name'];
    $file_size = $_FILES['profile_picture']['size'];
    $file_type = $_FILES['profile_picture']['type'];
    
    // Define allowed file types (image files)
    $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    
    // Check if the file type is allowed
    if (in_array($file_type, $allowed_types)) {
        // Generate a unique file name to avoid overwriting
        $new_file_name = 'profile_' . $user_id . '_' . time() . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
        
        // Define the upload directory
        $upload_dir = 'uploads/profile_pictures/';
        
        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file_tmp_name, $upload_dir . $new_file_name)) {
            // Update the database with the full file path (including the directory)
            $profile_picture_path = $upload_dir . $new_file_name;
            $sql = "UPDATE users SET profile_picture = ? WHERE idno = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $profile_picture_path, $user_id);
            
            if ($stmt->execute()) {
                // Redirect to the settings page after a successful update
                echo "<script>alert('Profile Picture updated successfully.'); window.location.href='BootDash.php';</script>";
            } else {
                echo "Error updating profile picture.";
            }
            $stmt->close();
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "Invalid file type. Please upload an image file (JPEG, PNG, GIF).";
    }
} else {
    echo "No file uploaded or an error occurred during the file upload.";
}

$conn->close(); // Close the database connection
?>
