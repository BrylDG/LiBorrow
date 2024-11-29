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
        // Success: Book removed from favorites
        $_SESSION['message'] = 'removed_from_favorites';
        $stmt->close();
        header("Location: UserNavTemplate.php");
        exit();
    } else {
        // Error: Failed to remove book from favorites
        $_SESSION['message'] = 'error';
        $_SESSION['error'] = 'Failed to remove book from favorites. Please try again.';
        $stmt->close();
        header("Location: UserNavTemplate.php");
        exit();
    }
} else {
    $_SESSION['message'] = 'error';
    $_SESSION['error'] = 'Invalid request.';
    header("Location: UserNavTemplate.php");
    exit();
}

$conn->close();
