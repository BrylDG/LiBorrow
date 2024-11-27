<?php
// Include the database connection
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}

// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    error_log(print_r($_POST, true));
    error_log(print_r($_FILES, true));
    // Collect form data
    $bookId = $_POST['bookid'] ?? '';
    $author = $_POST['author'] ?? '';
    $bookTitle = mysqli_real_escape_string($conn, $_POST['booktitle']);
    $pubDate = $_POST['pubdate'] ?? '';
    $genres = $_POST['genre'] ?? []; // This will be an array
    $quantity = $_POST['quantity'] ?? '';
    $descrpt = $_POST['descrpt'] ?? '';

    // Validate input fields
    if (empty($bookId) || empty($author) || empty($bookTitle) || empty($pubDate) || empty($genres) || empty($quantity)) {
        echo 'Please fill in all fields.';
        exit;
    }

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $targetDir = 'Images/'; // Ensure this directory exists and is writable
        $targetFile = $targetDir . basename($_FILES['book_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (getimagesize($_FILES['book_image']['tmp_name']) && $_FILES['book_image']['size'] <= 5000000 && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['book_image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile; // Store the path of the uploaded image
            } else {
                echo 'Image upload failed.';
                exit;
            }
        } else {
            echo 'Invalid image or file too large.';
            exit;
        }
    }

    // Insert data into the books table
    $query = "INSERT INTO books (bookid, author, booktitle, pubdate, quantity, descrpt, bookimg) 
              VALUES ('$bookId', '$author', '$bookTitle', '$pubDate', '$quantity', '$descrpt', '$imagePath')";

    if (mysqli_query($conn, $query)) {
        // Get the last inserted book ID
        $lastBookId = mysqli_insert_id($conn);

        // Insert genres into the bookgenres table
        foreach ($genres as $genreId) {
            $genreQuery = "INSERT INTO bookgenres (bookid, genreid) VALUES ('$lastBookId', '$genreId')";
            if (!mysqli_query($conn, $genreQuery)) {
                error_log('Genre Insert Error: ' . mysqli_error($conn)); // Log any errors
            }
        }

        echo 'success'; // Return a simple success message
    } else {
        error_log('Database Insert Error: ' . mysqli_error($conn)); // Debugging database error
        echo 'error'; // Return a simple error message
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
</head>
<body>
    <div class="content-box" style="padding: 20px; border-radius: 10px;">
        <div class="container">
            <form id="addBookForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                <!-- Book ID -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <input 
                        type="text" 
                        name="bookid" 
                        placeholder="ISBN" 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;">
                </div>

                <!-- Author -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <input 
                        type="text" 
                        name="author" 
                        placeholder="Author" 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;">
                </div>

                <!-- Book Title -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <input 
                        type="text" 
                        name="booktitle" 
                        placeholder="Book Title" 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;">
                </div>

                <!-- Publication Date -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <input 
                        type="date" 
                        name="pubdate" 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;">
                </div>

                <!-- Genre -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <select 
                        id="genre-select" 
                        name="genre[]" 
                        multiple 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 18px; outline: none; background-color: white; transition: border-color 0.3s ease-in-out; height: 100px;">
                        <option value="">Select Genre</option>
                        <?php
                        // Fetch genres from the database
                        $genreQuery = "SELECT genreid, name FROM genres";
                        $genreResult = mysqli_query($conn, $genreQuery);
                        while ($row = mysqli_fetch_assoc($genreResult)) {
                            echo '<option value="' . $row['genreid'] . '">' . htmlspecialchars($row['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Quantity -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <input 
                        type="number" 
                        name="quantity" 
                        placeholder="Quantity" 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;">
                </div>

                <!-- Description -->
                <textarea 
                    name="descrpt" 
                    placeholder="Description..." 
                    style="width: 90%; height: 100px; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;"></textarea>

                <!-- Upload Book Image -->
                <div class="input-container" style="position: relative; width: 100%;">
                    <input 
                        type="file" 
                        name="book_image" 
                        accept="image/*" 
                        required 
                        style="width: 90%; padding: 15px; border: 2px solid #746767; color: #333; border-radius: 20px; font-size: 10px; outline: none; transition: border-color 0.3s ease-in-out;">
                </div>
                <div class="buttons" style="display: flex; gap: 10px;">
                <button 
                    type="submit" 
                    style="width: 200px; padding: 15px; background-color: #ff6600; color: white; border: none; border-radius: 20px; font-size: 10px; cursor: pointer; transition: background-color 0.3s ease-in-out;">Add Book</button>

                <!-- Cancel Button -->
                <button 
                    type="button" 
                    style="width: 200px; padding: 15px; background-color: #ccc; color: white; border: none; border-radius: 20px; font-size: 10px; cursor: pointer; transition: background-color 0.3s ease-in-out;">Cancel</button>
                </div>
            </form>
        </div>
		<div class="checkbox-container" id="checkbox-container">
            <!-- Checkboxes will be dynamically added here -->
        </div>
    </div>
</body>
</html>
