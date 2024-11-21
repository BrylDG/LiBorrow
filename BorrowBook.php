<?php
session_start();
include('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['idno'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $bookid = $_POST['bookid'];
    $fullname = $_POST['fullname'];
    $idno = $_SESSION['idno'];
    $booktitle = $_POST['booktitle'];
    $author = $_POST['author'];
    $bookimg = $_POST['bookimg'];
    $request_date = date('Y-m-d');

    if (!empty($bookid) && !empty($idno) && !empty($booktitle)) {
        // Check if the book is already in favorites
        $check_sql = "SELECT * FROM pendings WHERE bookid = ? AND idno = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $bookid, $idno);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            header("Location: UserNavTemplate.php?message=already_in_pendings");
            exit();
        }

        // Insert data into the borrowrequest table
        $query = "INSERT INTO pendings (fullname, idno, requestdate, bookid, booktitle, author, bookimg) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sisisss", $fullname, $idno, $request_date, $bookid, $booktitle, $author, $bookimg);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: UserNavTemplate.php?message=added_to_pendings");
            exit();
        } else {
            $error_message = $insert_stmt->error;
            $stmt->close();
            $conn->close();
            header("Location: UserNavTemplate.php?message=error&error=" . urlencode($error_message));
            exit();
        }
    }
}
header("Location: UserNavTemplate.php");
exit();
?>
