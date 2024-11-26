<?php
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    // Fetch reader information
    $sql = "SELECT idno, bookid, booktitle, fullname, duedate, DATEDIFF(duedate, CURDATE()) AS days FROM borrows WHERE duedate < CURDATE()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idno = $row['idno'];
		$booktite = $row['booktitle'];

        // Insert notification
        $notif_sql = "INSERT INTO notification (idno, details, booktitle, time)
                      VALUES ('$idno', 'Your borrowed book {$row['booktitle']} is now overdue. Kindly return it to the library.', '$booktite', CURRENT_TIMESTAMP())";

        if ($conn->query($notif_sql) === TRUE) {
            echo "<script>alert('Sending Reminder.')</script>";
            $conn->close();
            header("Location: BootDash.php?");
            exit();
        } else {
			echo "<script>alert('Failed to send.')</script>";
            $conn->close();
            header("Location: BootDash.php?");
            exit();
        }
    } else {
		echo "<script>alert('Reader not found for the specified book.')</script>";
        $conn->close();
        header("Location: BootDash.php?");
        exit();
    }
}
?>