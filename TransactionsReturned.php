<?php
session_start(); // Start the session
include('connection.php'); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch data for the current page
$sql = "SELECT bookname, author, status, username, datereturned, image FROM returns";
$result = $conn->query($sql);


// Add search filter if provided
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " AND (bookname LIKE '%$search%' OR author LIKE '%$search%')";
}

// Add status filter if provided
if (!empty($_GET['filter'])) {
    $filter = $conn->real_escape_string($_GET['filter']);
    $sql .= " AND status = '$filter'";
}

// Add sorting if selected
if (!empty($_GET['sort'])) {
    $sort = $conn->real_escape_string($_GET['sort']);
    $sql .= " ORDER BY $sort";
} else {
    // Default sorting by bookname
    $sql .= " ORDER BY bookname";
}
?>
    <div class="content-box" id="content2">
        <div class="container">
            <div id="Returend-d1" class="Returned-box" overflow-x:auto; overflow-y: auto;>
                <div class="input">
                    <!-- Search, Sort, and Filter Section -->
                    <div class="search-bar">
                        <input type="text" placeholder=" Search...">
                        <span class="search-icon">
                            <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                        </span>
                    </div>
                    <button class="sort-btn">
                        <img src="./Images/Sort.svg" alt="Sort Icon" width="20" height="20"> Sort By
                        <img src="./Images/vec.svg" alt="Icon After" width="18" height="18">
                    </button>
                    <button class="filter-btn">
                        <img src="./Images/Filter_alt_fill.svg" alt="Filter Icon" width="20" height="20"> Filter By
                        <img src="./Images/Expand_down.svg" alt="Icon After" width="18" height="18">
                    </button>
                </div>
                
                <!-- Table Section -->
                <table class="Returned-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Returned By</th>
                            <th>Date Returned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						if ($result && $result->num_rows > 0) {
							// Output data of each row
							while ($row = $result->fetch_assoc()) {
								echo "<tr>";
								echo "<td>";
								// Display the image next to the book name
								if (!empty($row["image"])) {
									echo "<img src='" . htmlspecialchars($row["image"]) . "' alt='Book Image' width='50' height='70' style='margin-right:10px;'>";
								} else {
									// Default image if none provided
									echo "<img src='./Images/default-book.png' alt='Default Book Image' width='50' height='70' style='margin-right:10px;'>";
								}
								echo htmlspecialchars($row["bookname"]) . "</td>";
								echo "<td>" . htmlspecialchars($row["author"]) . "</td>";
								echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
								echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
								echo "<td>" . htmlspecialchars($row["datereturned"]) . "</td>";
								echo "</tr>";
							}
						} else {
							echo "<tr><td colspan='5'>No records found</td></tr>";
						}
						?>
                    </tbody>
                </table>
                
                
            </div>
        </div>

 
