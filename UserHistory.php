<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) { // Replace 'fullname' with your session variable for logged-in users
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}

// Retrieve the full name and idno from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User  '; // Default to 'User  ' if not set
$idno = isset($_SESSION['idno']) ? $_SESSION['idno'] : ''; // Assuming 'idno' is stored in session

// Fetch data from `history` table for the logged-in user
$sql = "SELECT h.bookid, h.booktitle, h.idno, h.fullname, h.borrowdate, h.duedate, h.status, b.bookimg
        FROM history h
			JOIN books b
			ON h.bookid=b.bookid
        WHERE idno = '$idno'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content-box" id="content2">
        <div class="container">

            <div id="Returned-d1" class="Returned-box">
                <!-- Search, Sort, Filter Section -->
                <div class="input">
                    <div class="search-bar-user">
                        <input type="text" placeholder="Search...">
                        <span class="search-icon-user">
                            <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                        </span>
                    </div>
                    <button class="sort-btn">
                        <img src="./Images/Sort.svg" alt="Sort Icon" width="20" height="20"> Sort By
                        <img src="./Images/vec.svg" alt="Sort Down Icon" width="18" height="18">
                    </button>
                    <button class="filter-btn">
                        <img src="./Images/Filter_alt_fill.svg" alt="Filter Icon" width="20" height="20"> Filter By
                        <img src="./Images/Expand_down.svg" alt="Filter Down Icon" width="18" height="18">
                    </button>
                </div>

                <!-- Table Section -->
                <div class="container-box">
                    <div class="historytitle">
                        <p>Book</p>
                        <p style="margin-left: 5%">Author</p>
                        <p style="margin-left: 0;">Status</p>
                        <p style="margin-left: 0;">Action</p>
                    </div>
                    <div class="historybody">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='book-row'>
                                    <p><img src='{$row['bookimg']}' alt='book' style='height='100px'; width='80px';'> {$row['booktitle']}</p>
                                    <p>{$row['fullname']}</p>
                                    <p>{$row['status']}</p>
                                    <p class='viewdetails'><a href='#' class='view-more' data-bookid='" . htmlspecialchars($row['bookid']) . "'>View more</a></p>
                                </div>";
                            }
                        } else {
                            echo "<div class='book-row'><p>No records found.</p></div>";
                        }
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
