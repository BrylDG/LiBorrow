<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) { // Replace 'user_id' with your session variable for logged-in users
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}
// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User '; // Default to 'User ' if not set

// Fetch data from `history` table
$sql = "SELECT bookid, title, idno AS userid, username, borrowdate, duedate, status FROM history";
$result = $conn->query($sql);
?>
<div class="content-box" id="content2">
    <div class="container">
        <div class="input">
            <!-- Search and Buttons -->
            <div class="search-bar">
                <input type="text" placeholder=" Search...">
                <span class="search-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <button class="sort-btn">
                <img src="./Images/Sort.svg" alt="Icon Before" width="20" height="20">
                Sort By
                <img src="./Images/vec.svg" alt="Icon After" width="18" height="18">
            </button>
            <button class="filter-btn">
                <img src="./Images/Filter_alt_fill.svg" alt="Icon Before" width="20" height="20">
                Filter By
                <img src="./Images/Expand_down.svg" alt="Icon After" width="18" height="18">
            </button>
        </div>
        <div id="d1" class="History-box">
            <div style="max-height: 400px; overflow-y: auto; width: 100%;">
                <table class="History-table">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['bookid']}</td>
                                    <td>{$row['title']}</td>
                                    <td>{$row['userid']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['borrowdate']}</td>
                                    <td>{$row['duedate']}</td>
                                    <td class='act'>{$row['status']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>