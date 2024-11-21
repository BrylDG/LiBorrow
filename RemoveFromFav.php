<?php
session_start();
include('connection.php'); 

if (!isset($_SESSION['idno'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookid']) && isset($_POST['idno'])) {
    $bookid = $_POST['bookid'];
    $idno = $_POST['idno'];

    $sql = "DELETE FROM favorites WHERE bookid = ? AND idno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookid, $idno);

    if ($stmt->execute()) {
        echo "<script>alert('Book removed from favorites successfully!'); window.location.href='UserNavTemplate.php';</script>";
    } else {
        echo "<script>alert('Failed to remove book from favorites. Please try again.'); window.location.href='UserNavTemplate.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='UserNavTemplate.php';</script>";
}

$conn->close();
?>
