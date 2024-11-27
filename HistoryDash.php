<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}

// Fetch data from `history` table if not an AJAX request
if (!isset($_GET['ajax'])) {
    // Retrieve the full name from the session
    $fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User      '; // Default to 'User      ' if not set

    // Fetch initial data
    $sql = "SELECT bookid, booktitle, idno, fullname, borrowdate, duedate, status FROM history";
    $result = $conn->query($sql);
}
if (isset($_GET['ajax'])) {
    $searchTerm = isset($_GET ['search']) ? $_GET['search'] : '';
    $sortBy = isset($_GET['sort']) ? $_GET['sort'] : '';
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

    // Prepare the SQL query
    $sql = "SELECT bookid, booktitle, idno, fullname, borrowdate, duedate, status FROM history WHERE 1=1";

    // Add search condition
    if (!empty($searchTerm)) {
        $sql .= " AND (booktitle LIKE '%$searchTerm%' OR fullname LIKE '%$searchTerm%')";
    }

    // Add status filter
    if (!empty($statusFilter)) {
        $sql .= " AND status = '$statusFilter'";
    }

    // Add sorting
    if (!empty($sortBy)) {
        $sql .= " ORDER BY $sortBy"; // Be careful with SQL injection here; consider using prepared statements
    }

    $result = $conn->query($sql);
    $books = [];

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
    } else {
        // Log SQL error for debugging
        error_log("SQL Error: " . $conn->error);
    }

    // Return JSON response
    echo json_encode(['table_body' => $books]);
    exit; // Stop further execution
	                            $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book History</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="content-box" id="content2">
        <div class="container">
            <div class="input">
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search..." onkeyup="loadBooksHistory()">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20" onclick="loadBooksHistory()">
                    </span>
                </div>
                <select id="sort-dropdown" onchange="loadBooksHistory()">
                    <option value="">Sort By</option>
                    <option value="booktitle">Title</option>
                    <option value="borrowdate">Borrow Date</option>
                    <option value="duedate">Due Date</option>
                </select>
                <select id="status-filter" onchange="loadBooksHistory()">
                    <option value="">Filter By Status</option>
                    <option value="Borrowed">Borrowed</option>
                    <option value="Returned">Returned</option>
                    <option value="Overdue">Overdue</option>
                </select>
                <div id="loading" style="display:none;">Loading...</div> <!-- Loading indicator -->
            </div>
            <div id="d1" class="History-box">
                <div style="max-height: 400px; overflow-y: auto; width: 100%;">
                    <table class="History-table">
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Book Title</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($result) && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['bookid']}</td>
                                        <td>{$row['booktitle']}</td>
                                        <td>{$row['idno']}</td>
                                        <td>{$row['fullname']}</td>
                                        <td>{$row['borrowdate']}</td>
                                        <td>{$row['duedate']}</td>
                                        <td class='act'>{$row['status']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>