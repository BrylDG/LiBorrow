<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookid']);

    $stmt = $conn->prepare("DELETE FROM books WHERE bookid = ?");
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        echo "<script>alert('Book deleted successfully.'); window.location.href='BootDash.php'; </script>";
    } else {
        echo "Error deleting book: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
