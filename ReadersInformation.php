<?php
session_start();
include('connection.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user's idno and isAdmin status from session
$loggedInIdno = $_SESSION['idno']; // Logged-in user's idno
$loggedInIsAdmin = $_SESSION['isAdmin']; // Logged-in user's admin status

// Retrieve the idno from the query string (URL)
if (isset($_GET['idno'])) {
    $user_id = $_GET['idno']; // Get the user ID from the URL
} else {
    handleMissingIdno(); // If no ID is provided, handle the error
}

// Fetch user data from the database based on the idno
$sql = "SELECT idno, fullname, username, email, phoneno, address, account_status,profile_picture FROM users WHERE idno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    // Fetch the user's borrowed books history
    $sql_history = "SELECT bookid, booktitle, borrowdate, duedate FROM history WHERE idno = ?";
    $stmt_history = $conn->prepare($sql_history);
    $stmt_history->bind_param("i", $user_id);
    $stmt_history->execute();
    $history_result = $stmt_history->get_result();
} else {
    handleUserNotFound(); // If the user is not found, handle the error
}

$stmt->close();
$stmt_history->close();
$conn->close();

// Function to handle missing ID
function handleMissingIdno() {
    echo "User ID is missing.";
    exit();
}

// Function to handle user not found
function handleUserNotFound() {
    echo "User not found.";
    exit();
}
?>

<!-- HTML for normal page view -->
<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Readerbox">
            <div class="Readbox Rglobal" id="PboxView">
                <div id="profile-view" class="Pview">
                    <?php
                    // Check if the profile picture exists and is not empty
                    $profile_picture = !empty($user_data['profile_picture']) ? htmlspecialchars($user_data['profile_picture']) : './Images/profilepic.png';
                    ?>
                    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" id="profilepic" style="border-radius:50%" height="250px" width="250px">
                    <h2><?php echo htmlspecialchars($user_data['username']); ?></h2>
                </div>
                <div id="profile-details" class="Pdets">
                    <form action="updateUserInfo.php" method="POST">
                        <table class="Readersinfo-table">
                            <tr>
                                <td style="font-weight: bold;">ID Number</td>
                                <td><input type="text" name="idno" value="<?php echo htmlspecialchars($user_data['idno']); ?>" readonly /></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">User Name:</td>
                                <td><input type="text" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" /></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Full Name:</td>
                                <td><input type="text" name="fullname" value="<?php echo htmlspecialchars($user_data['fullname']); ?>" /></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Email:</td>
                                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" /></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Phone Number:</td>
                                <td><input type="text" name="phoneno" value="<?php echo htmlspecialchars($user_data['phoneno']); ?>" /></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Address:</td>
                                <td><input type="text" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" /></td>
                            </tr>
                        </table>
                        <div class="upbtn" style="padding-bottom:120px">
                            <button type="submit" id="update-button" class="update-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="ReaderSusBan-btn">
                <!-- Form to update suspension or banning status -->
                <form method="POST" action="suspendBanUser.php?idno=<?php echo $user_data['idno']; ?>">
                    <?php if ($user_data['account_status'] == 'suspended'): ?>
                        <button type="submit" name="action" value="unsuspend" class="update-btn">Unsuspend</button>
                    <?php elseif ($user_data['account_status'] == 'banned'): ?>
                        <button type="submit" name="action" value="unban" class="update-btn">Unban</button>
                    <?php else: ?>
                        <button type="submit" name="action" value="suspend" class="update-btn">Suspend</button>
                        <button type="submit" name="action" value="ban" class="update-btn">Ban</button>
                    <?php endif; ?>
                </form>
            </div>

            <div class="Readbox Rglobal" id="ReaderPan">
                <table class="ReadersInfo-details">
                    <thead>
                        <tr>
                            <th>Book Id</th>
                            <th>Book Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($history_result->num_rows > 0) {
                            while ($row = $history_result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['bookid']) . "</td>
                                        <td>" . htmlspecialchars($row['booktitle']) . "</td>
                                        <td>" . htmlspecialchars($row['borrowdate']) . "</td>
                                        <td>" . htmlspecialchars($row['duedate']) . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No borrowing history available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
