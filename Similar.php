<?php
session_start();
include('connection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the bookid from the GET request
$bookid = isset($_GET['bookid']) ? intval($_GET['bookid']) : 0;

// Prepare the query to fetch similar books
$query = "SELECT b.bookid, b.booktitle, b.author, b.bookimg, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
          FROM books b
          JOIN bookgenres bg ON b.bookid = bg.bookid
          JOIN genres g ON bg.genreid = g.genreid
          WHERE b.bookid = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bookid);
$stmt->execute();
$result = $stmt->get_result();
$similarBooks = $result->fetch_all(MYSQLI_ASSOC);

// Return data as JSON
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    echo json_encode(['similarBooks' => $similarBooks]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Similar Books</title>
    <link rel="stylesheet" href="./Browse.css">
</head>
<body>

<div class="browse-box" >
    <div class="container">
        <h3>Similar Books</h3>
        <div class="pendbox global" id="User-books" style="height: 500px;">
            <div class="userbooks-container" style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php foreach ($similarBooks as $book): ?>
                    <div class="book-container">
                        <!-- Display book details here -->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>