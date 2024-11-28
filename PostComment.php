<?php
session_start();
include('connection.php');

// Get the comment and book ID from the POST request
$comment = isset($_POST['comment']) ? $_POST['comment'] : '';
$bookid = isset($_POST['bookid']) ? $_POST['bookid'] : 0;

if ($comment && $bookid) {
    // Sanitize input to prevent SQL injection
    $comment = htmlspecialchars($comment);
    $userId = $_SESSION['idno'];  // Get user ID from session

    // Insert comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (bookid, idno, comment, comment_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $bookid, $userId, $comment);
    $stmt->execute();

    // Check if the comment was inserted successfully
    if ($stmt->affected_rows > 0) {
        // Get the username of the commenter (assuming you have a users table)
        $stmt = $conn->prepare("SELECT username FROM users WHERE idno = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Return the response as JSON
        echo json_encode([
            'success' => true,
            'comment' => $comment,
            'username' => $user['username'],
            'comment_date' => date("F j, Y, g:i a")
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
