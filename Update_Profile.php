<?php
session_start();
include('connection.php'); // Include the database connection file

if (!isset($_SESSION['idno'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

//para ma fetch ang username
$idno = $_SESSION['idno'];
$sql = "SELECT fullname, username, email, phoneno, address, password, isAdmin FROM users WHERE idno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $old_username = $row['username'];
    $old_fullname = $row['fullname'];
    $old_email = $row['email'];
    $old_phone = $row['phoneno'];
    $old_address = $row['address'];
    $old_pass = $row['password'];
    $isAdmin = $row['isAdmin'];
} else {
    die('User not found.');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idno = $_SESSION['idno']; // Assuming user ID is stored in session
    $fullname = isset($_POST['full-name']) && $_POST['full-name'] !== '' ? trim($_POST['full-name']) : $old_fullname;
    $username = isset($_POST['username']) && $_POST['username'] !== '' ? trim($_POST['username']) : $old_username;
    $email = isset($_POST['email']) && $_POST['email'] !== '' ? trim($_POST['email']) : $old_email;
    $phoneno = isset($_POST['phone-number']) && $_POST['phone-number'] !== '' ? trim($_POST['phone-number']) : $old_phone;
    $address = isset($_POST['address']) && $_POST['address'] !== '' ? trim($_POST['address']) : $old_address;
    $newPass = isset($_POST['new-password']) && $_POST['new-password'] !== '' ? trim($_POST['new-password']) : $old_pass;
    $confPass = isset($_POST['confirm-password']) && $_POST['confirm-password'] !== '' ? trim($_POST['confirm-password']) : $old_pass;

    //Password Confirmation
    if ($newPass !== $confPass) {
        $redirectPage = $isAdmin == 1 ? 'BootDash.php' : 'UserNavTemplate.php';
        echo "<script>alert('New passwords do not match.'); window.location.href='$redirectPage'; </script>";
        exit();
    }

    if (
        $fullname === $old_fullname &&
        $username === $old_username &&
        $email === $old_email &&
        $phoneno === $old_phone &&
        $address === $old_address &&
        $newPass === $old_pass &&
        $confPass === $old_pass
    ) {
        // No updates
        $redirectPage = $isAdmin == 1 ? 'BootDash.php' : 'UserNavTemplate.php';
        echo "<script>alert('No updates.'); window.location.href='$redirectPage'; </script>";
        exit();
    }

    // Update query
    $sql = "UPDATE users SET fullname = ?, email = ?, phoneno = ?, address = ?, username = ?, password = ? WHERE idno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssi", $fullname, $email, $phoneno, $address, $username, $confPass, $idno);

    if ($stmt->execute()) {
        $_SESSION['fullname'] = $fullname;
        $_SESSION['username'] = $username;
        $redirectPage = $isAdmin == 1 ? 'BootDash.php' : 'UserNavTemplate.php';
        echo "<script>alert('Profile Information updated successfully.'); window.location.href='$redirectPage'; </script>";
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
