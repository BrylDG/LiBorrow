<?php
session_start();
include('connection.php');

if (!isset($_SESSION['idno'])) {
    header("Location: login.php");
    exit();
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookid = $_POST['bookid'];
    $idno = $_POST['idno'];
    $booktitle = $_POST['booktitle'];
    $author = $_POST['author'];
    $bookimg = $_POST['bookimg'];

    if (!empty($bookid) && !empty($idno) && !empty($booktitle)) {
        // Check if the book is already in favorites
        $check_sql = "SELECT * FROM favorites WHERE bookid = ? AND idno = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $bookid, $idno);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            header("Location: UserNavTemplate.php?message=already_in_favorites");
            exit();
        }

        // Add the book to the favorites table
        $insert_sql = "INSERT INTO favorites (bookid, idno, booktitle, author, bookimg) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iisss", $bookid, $idno, $booktitle, $author, $bookimg);

        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            $conn->close();
            header("Location: UserNavTemplate.php?message=added_successfully");
            exit();
        } else {
            $error_message = $insert_stmt->error;
            $insert_stmt->close();
            $conn->close();
            header("Location: UserNavTemplate.php?message=error&error=" . urlencode($error_message));
            exit();
        }
    }
}
header("Location: UserNavTemplate.php");
exit();
