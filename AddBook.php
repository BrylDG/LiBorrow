<?php
// Include the database connection
include 'connection.php';

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

    // Validate input fields
    if (empty($bookId) || empty($author) || empty($bookTitle) || empty($pubDate) || empty($genre) || empty($quantity)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
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
            } else {
                echo json_encode(['success' => false, 'message' => 'Image upload failed.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid image or file too large.']);
            exit;
        }
    }

    // Insert data into the database using the connection from connection.php
    $query = "INSERT INTO books (bookid, author, booktitle, pubdate, genre, quantity, descrpt, bookimg) 
              VALUES ('$bookId', '$author', '$bookTitle', '$pubDate', '$genre', '$quantity', '$descrpt', '$imagePath')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Book added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add book. Error: ' . mysqli_error($conn)]);
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
        </form>
    </div>
</div>
