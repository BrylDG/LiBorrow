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

// Retrieve the idno from the query string (URL)
if (isset($_GET['idno'])) {
    $user_id = $_GET['idno']; // Get the user ID from the URL
} else {
    handleMissingIdno(); // If no ID is provided, handle the error
}

// Fetch user data from the database based on the idno
$sql = "SELECT idno, fullname, username, email, phoneno, address FROM users WHERE idno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    // Check if it's an AJAX request
    if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
        echo json_encode([
            'success' => true,
            'data' => $user_data
        ]);
        exit();
    }
} else {
    handleUserNotFound(); // If the user is not found, handle the error
}

$stmt->close();
$conn->close();

// Function to handle missing ID
function handleMissingIdno() {
    if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
        echo json_encode([
            'success' => false,
            'message' => 'User ID is missing.'
        ]);
    } else {
        echo "User ID is missing.";
    }
    exit();
}

// Function to handle user not found
function handleUserNotFound() {
    if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
        echo json_encode([
            'success' => false,
            'message' => 'User not found.'
        ]);
    } else {
        echo "User not found.";
    }
    exit();
}
?>

<!-- HTML for normal page view -->
<div class="content-box" id="content2">
    <div class="container">
        <div id="Readd1" class="Readerbox">
            <div class="Readbox Rglobal" id="PboxView">
                <div id="profile-view" class="Pview">
                    <img src="./Images/profilepic.png" alt="Profile Picture" id="profilepic">
                    <h2><?php echo htmlspecialchars($user_data['username']); ?></h2>
                </div>
                <div id="profile-details" class="Pdets">
                    <table class="Readersinfo-table">
                        <tr>
                            <td style="font-weight: bold;">ID Number</td>
                            <td><?php echo htmlspecialchars($user_data['idno']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">User Name:</td>
                            <td><?php echo htmlspecialchars($user_data['username']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Full Name:</td>
                            <td><?php echo htmlspecialchars($user_data['fullname']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Email:</td>
                            <td><?php echo htmlspecialchars($user_data['email']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Phone Number:</td>
                            <td><?php echo htmlspecialchars($user_data['phoneno']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Address:</td>
                            <td><?php echo htmlspecialchars($user_data['address']); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="upbtn">
                    <script>
                        function linktoUpdateUser () {
                            window.location.href = 'UpdateUser.php?idno=<?php echo $user_data['idno']; ?>';
                        }
                    </script>
                    <button id="update-button" class="update-btn" onclick="linktoUpdateUser()">
                        Update
                    </button>
                </div>
            </div>
            <div class="ReaderSusBan-btn">
                <button class="update-btn">Suspend</button>
                <button class="update-btn" id="banbtn">Ban</button>
            </div>
            <div class="Readbox Rglobal" id="ReaderPan">
                <table class="ReadersInfo-details">
                    <thead>
                        <tr>
                            <th>Book Id</th>
                            <th>Book Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>0001</td>
                            <td>Alice in Wonderland</td>
                            <td>Lewis Carroll</td>
                            <td>Fantasy/Adventure</td> 
                        </tr>
                        <tr>
                            <td>0002</td>
                            <td>Treasure Land</td>
                            <td>Lewis Carroll</td>
                            <td>Fantasy/Adventure</td> 
                        </tr>
                        <tr>
                            <td>0003</td>
                            <td>Treasure Land</td>
                            <td>Lewis Carroll</td>
                            <td>Fantasy/Adventure</td> 
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
