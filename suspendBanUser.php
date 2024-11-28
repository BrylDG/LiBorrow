<?php
session_start();
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['idno'])) {
    $user_id = $_GET['idno']; // Get the user ID from the URL
} else {
    echo "User ID is missing.";
    exit();
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'suspend') {
        $status = 'suspended';
    } elseif ($action == 'ban') {
        $status = 'banned';
    } elseif ($action == 'unsuspend') {
        $status = 'active';
    } elseif ($action == 'unban') {
        $status = 'active';
    }

    $sql_update = "UPDATE users SET account_status = ? WHERE idno = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $status, $user_id);

    if ($stmt_update->execute()) {
        echo "<script>alert('User status updated successfully.'); window.location.href='BootDash.php';</script>";
    } else {
        echo "Error updating user status.";
    }

    $stmt_update->close();
}

$conn->close();
?>
