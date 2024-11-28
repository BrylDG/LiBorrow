<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookid']);
    $author = $_POST['author'];
    $booktitle = $_POST['booktitle'];
    $publicationDate = $_POST['pubdate'];
    $genres = $_POST['genres']; // This is now an array of genre IDs
    $quantity = intval($_POST['quantity']);
    $descrpt = $_POST['descrpt'];

    // Update the book details in the 'books' table
    $stmt = $conn->prepare("UPDATE books SET author = ?, booktitle = ?, pubdate = ?, quantity = ?, descrpt = ? WHERE bookid = ?");
    $stmt->bind_param("sssisi", $author, $booktitle, $publicationDate, $quantity, $descrpt, $bookId);

    if ($stmt->execute()) {
        // Clear existing genres from the 'bookgenre' table
        $deleteStmt = $conn->prepare("DELETE FROM bookgenres WHERE bookid = ?");
        $deleteStmt->bind_param("i", $bookId);
        $deleteStmt->execute();
        $deleteStmt->close();

        // Insert new genres into the 'bookgenre' table
        $insertStmt = $conn->prepare("INSERT INTO bookgenres (bookid, genreid) VALUES (?, ?)");
        foreach ($genres as $genreId) {
            $insertStmt->bind_param("ii", $bookId, $genreId);
            $insertStmt->execute();
        }
        $insertStmt->close();

        echo "<script>alert('Book Information updated successfully.'); window.location.href='BootDash.php'; </script>";
    } else {
        echo "Error updating book: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
