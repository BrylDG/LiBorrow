<?php
session_start();
include('connection.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user's idno and admin status from session
$loggedInIdno = $_SESSION['idno'];
$loggedInIsAdmin = $_SESSION['isAdmin'];

// Get the user data from the form
$idno = $_POST['idno'];
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$phoneno = $_POST['phoneno'];
$address = $_POST['address'];

// Update the user's information in the database
$sql_update = "UPDATE users SET username = ?, fullname = ?, email = ?, phoneno = ?, address = ? WHERE idno = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("sssssi", $username, $fullname, $email, $phoneno, $address, $idno);

if ($stmt_update->execute()) {
    $_SESSION['fullname'] = $fullname;  // Update session data

    // Redirect based on admin status
    $redirectPage = $loggedInIsAdmin == 1 ? 'BootDash.php' : 'UserNavTemplate.php';
    echo "<script>alert('Profile Information updated successfully.'); window.location.href='$redirectPage'; </script>";
} else {
    echo "Error updating user information.";
}

$stmt_update->close();
$conn->close();
?>