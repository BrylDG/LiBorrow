<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/'; // Directory to save uploaded images
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Check file size (optional)
        if ($_FILES['image']['size'] > 2000000) { // Limit to 2MB
            echo "Error: File size is too large.";
            exit;
        }

        // Allow certain file formats
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($fileType, $allowedTypes)) {
            echo "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            echo "The file " . htmlspecialchars($fileName) . " has been uploaded.";
        } else {
            echo "Error: There was an error uploading your file.";
        }
    } else {
        echo "Error: " . $_FILES['image']['error'];
    }
} else {
    echo "Invalid request.";
}
?>