<?php
session_start();
include('connection.php');

if (!isset($_SESSION['idno'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fullname']) && isset($_POST['idno']) && isset($_POST['bookid'])) {
    $fullname = $_POST['fullname'];
    $idno = $_POST['idno'];
    $bookid = $_POST['bookid'];

    $sql = "DELETE FROM pendings WHERE fullname = ? AND idno = ? AND bookid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $fullname, $idno, $bookid);

    if ($stmt->execute()) {
        echo "<script>alert('Book removed from pending successfully!'); window.location.href='UserNavTemplate.php';</script>";
    } else {
        echo "<script>alert('Failed to remove book from pending. Please try again.'); window.location.href='UserNavTemplate.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='UserNavTemplate.php';</script>";
}

$conn->close(); 
?>
