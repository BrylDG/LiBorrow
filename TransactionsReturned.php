<?php
session_start(); // Start the session
include('connection.php'); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Initialize SQL query
$sql = "SELECT booktitle, author, status, fullname, datereturned, bookimg FROM returns WHERE 1=1";

// Add search filter if provided
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " AND (booktitle LIKE '%$search%' OR author LIKE '%$search%')";
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
    // Default sorting by booktitle
    $sql .= " ORDER BY booktitle";
}

// Fetch data for the current page
$result = $conn->query($sql);

// Prepare data for JSON response
$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// If the request is an AJAX request, return JSON
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode(['table_body' => $data]);
    exit();
}
?>

<div class="content-box" id="content2">
    <div class="container">
        <div id="Returend-d1" class="Returned-box" style="overflow-x:auto; overflow-y: auto;">
            <div class="input">
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search..." oninput="loadBooksR()">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                    </span>
                </div>
                <select id="sort-dropdown" onchange="loadBooksR()" style="font-size: 12px; padding: 8px 20px; border-radius: 30px; cursor: pointer; background-color: #ff6600; color: white;">
                    <option value="">Sort By</option>
                    <option value="booktitle">Title</option>
                    <option value="author">Author</option>
                </select>
            </div>
            
            <!-- Loading Indicator -->
            <div id="loading" style="display: none;">Loading...</div>

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
                <tbody id="returned-table-body">
                    <?php
                    if (!empty($data)) {
                        foreach ($data as $row) {
                            echo "<tr>";
                            echo "<td>";
                            if (!empty($row["bookimg"])) {
                                echo "<img src='" . htmlspecialchars($row["bookimg"]) . "' alt='Book Image' width='50' height='70' style='margin-right:10px;'>";
                            } else {
                                echo "<img src='./Images/default-book.png' alt='Default Book Image' width='50' height='70' style='margin-right:10px;'>";
                            }
                            echo htmlspecialchars($row["booktitle"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["author"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["fullname"]) . "</td>";
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
</div>