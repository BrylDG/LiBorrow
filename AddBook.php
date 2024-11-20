<?php
// Include the database connection
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) { // Replace 'user_id' with your session variable for logged-in users
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}
// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $bookId = $_POST['bookid'] ?? '';
    $author = $_POST['author'] ?? '';
    $bookTitle = $_POST['booktitle'] ?? '';
    $pubDate = $_POST['pubdate'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $descrpt = $_POST['descrpt'] ?? '';

    // Debugging: Check if fields are being received correctly
    error_log('bookId: ' . $bookId);
    error_log('author: ' . $author);
    error_log('bookTitle: ' . $bookTitle);
    error_log('pubDate: ' . $pubDate);
    error_log('genre: ' . $genre);
    error_log('quantity: ' . $quantity);
    error_log('descrpt: ' . $descrpt);

    // Validate input fields
    if (empty($bookId) || empty($author) || empty($bookTitle) || empty($pubDate) || empty($genre) || empty($quantity)) {
        echo 'Please fill in all fields.';
        exit;
    }

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $targetDir = '/Images/';
        $targetFile = basename($_FILES['book_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (getimagesize($_FILES['book_image']['tmp_name']) && $_FILES['book_image']['size'] <= 5000000 && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['book_image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
                error_log('Image Path: ' . $imagePath); // Debugging image upload
            } else {
                echo 'Image upload failed.';
                exit;
            }
        } else {
            echo 'Invalid image or file too large.';
            exit;
        }
    }

    // Insert data into the database using the connection from connection.php
    $query = "INSERT INTO books (bookid, author, booktitle, pubdate, genre, quantity, descrpt, bookimg) 
              VALUES ('$bookId', '$author', '$bookTitle', '$pubDate', '$genre', '$quantity', '$descrpt', '$imagePath')";

    if (mysqli_query($conn, $query)) {
        echo 'success'; // Return a simple success message
    } else {
        error_log('Database Insert Error: ' . mysqli_error($conn)); // Debugging database error
        echo 'error'; // Return a simple error message
    }
}
?>

<!-- HTML Form -->
<div class="content-box">
    <div class="container">
        <form id="addBookForm" method="POST" enctype="multipart/form-data">
            <div class="input-container">
                <input type="text" name="bookid" placeholder="Book ID" required>
                <label>Book ID</label>
            </div>
            <div class="input-container">
                <input type="text" name="author" placeholder="Author" required>
                <label>Author</label>
            </div>
            <div class="input-container">
                <input type="text" name="booktitle" placeholder="Book Title" required>
                <label>Title</label>
            </div>
            <div class="input-container">
                <input type="date" name="pubdate" required>
                <label>Publication Date</label>
            </div>
            <div class="input-container">
                <select name="genre" required>
                    <option value="">Select Genre</option>
                    <option value="fiction">Fiction</option>
                    <option value="non-fiction">Non-Fiction</option>
                    <option value="mystery">Mystery</option>
                    <option value="sci-fi">Sci-Fi</option>
                    <option value="fantasy">Fantasy</option>
                </select>
                <label>Genre</label>
            </div>
            <div class="input-container">
                <input type="number" name="quantity" placeholder="Quantity" required>
                <label>Quantity</label>
            </div>
            <textarea name="descrpt" placeholder="Description..."></textarea>
            <div class="input-container">
                <input type="file" name="book_image" accept="image/*" required>
                <label>Upload Book Image</label>
            </div>
            <button type="submit">Add Book</button>
			<!-- Cancel Button -->
            <button type="button">Cancel</button>
        </form>
    </div>
</div>
