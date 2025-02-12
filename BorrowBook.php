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
    $action = $_POST['action'];  // New action field to check if the user is borrowing or returning the book

    if (!empty($bookid) && !empty($idno) && !empty($booktitle)) {
        if ($action === 'borrow') {
            // Check if the book is already in pendings
            $check_sql = "SELECT * FROM pendings WHERE bookid = ? AND idno = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ii", $bookid, $idno);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['message'] = 'already_in_pendings';
                $check_stmt->close();
                $conn->close();
                header("Location: UserNavTemplate.php");
                exit();
            }

            // Insert data into the pendings table (borrow request)
            $query = "INSERT INTO pendings (fullname, idno, requestdate, bookid, booktitle, author, bookimg) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $sendNotif = "INSERT INTO req_notif (idno, message, time) VALUES ('$idno', '{$fullname} just requested a book.', CURRENT_TIMESTAMP())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sisisss", $fullname, $idno, $request_date, $bookid, $booktitle, $author, $bookimg);

            if ($stmt->execute()) {
                $conn->query($sendNotif);
                $_SESSION['message'] = 'added_to_pendings';
                $stmt->close();
                $conn->close();
                header("Location: UserNavTemplate.php");
                exit();
            } else {
                $error_message = $stmt->error;
                $_SESSION['message'] = 'error';
                $_SESSION['error'] = $error_message;
                $stmt->close();
                $conn->close();
                header("Location: UserNavTemplate.php");
                exit();
            }
        } elseif ($action === 'return') {
            // Insert data into the returns table
            $return_query = "INSERT INTO returns (fullname, idno, datereturned, bookid, booktitle, author, bookimg, borrowdate, duedate) 
                             SELECT fullname, idno, NOW(), bookid, booktitle, author, bookimg, borrowdate, duedate 
                             FROM borrows 
                             WHERE bookid = ? AND idno = ?";
            $delete_query = "DELETE FROM borrows WHERE bookid = ? AND idno = ?"; // Remove book from borrows table

            // First, insert the return entry into returns table
            $return_stmt = $conn->prepare($return_query);
            $return_stmt->bind_param("ii", $bookid, $idno);

            if ($return_stmt->execute()) {
                // If insert is successful, delete the entry from borrows table
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param("ii", $bookid, $idno);

                if ($delete_stmt->execute()) {
                    $_SESSION['message'] = 'book_returned';
                    $return_stmt->close();
                    $delete_stmt->close();
                    $conn->close();
                    header("Location: UserNavTemplate.php");
                    exit();
                } else {
                    // Handle error on deleting from borrows
                    $_SESSION['message'] = 'error';
                    $_SESSION['error'] = $delete_stmt->error;
                    $delete_stmt->close();
                    $return_stmt->close();
                    $conn->close();
                    header("Location: UserNavTemplate.php");
                    exit();
                }
            } else {
                // Handle error on inserting into returns
                $_SESSION['message'] = 'error';
                $_SESSION['error'] = $return_stmt->error;
                $return_stmt->close();
                $conn->close();
                header("Location: UserNavTemplate.php");
                exit();
            }
        }
    }
}
header("Location: UserNavTemplate.php");
exit();
?>
