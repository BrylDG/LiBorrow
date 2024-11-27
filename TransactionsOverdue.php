<?php
include('connection.php');

// Fetch overdue books from the database
$sql = "SELECT bookid, booktitle, fullname, duedate, DATEDIFF(CURDATE(), duedate) AS days FROM borrows WHERE duedate < CURDATE()";

// Check if it's an AJAX request
if (isset($_GET['ajax'])) {
    // Add search filter if provided
    if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql .= " AND (booktitle LIKE '%$search%' OR fullname LIKE '%$search%')";
    }

    // Add sorting if selected
    if (!empty($_GET['sort'])) {
        $sort = $conn->real_escape_string($_GET['sort']);
        $sql .= " ORDER BY $sort";
    } else {
        // Default sorting by booktitle
        $sql .= " ORDER BY booktitle";
    }

    $result = $conn->query($sql);

    // Prepare data for JSON response
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['table_body' => $data]);
    exit();
}
?>

<div class="content-box" id="content2">
    <div class="container">
        <div class="input" style="padding-left: 400px; padding-top: 20px; padding-bottom:50px;">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search..." oninput="loadBooksO()">
                <span class="search-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <select id="sort-dropdown" onchange="loadBooksO()" style="font-size: 12px; padding: 8px 20px; border-radius: 30px; cursor: pointer; background-color: #ff6600; color: white;">
                <option value="">Sort By</option>
                <option value="booktitle">Title</option>
                <option value="fullname">User  Name</option>
                <option value="days">Days Overdue</option>
            </select>
        </div>
        <div id="d1" class="Overdue-box" style="overflow-x:auto;">
            <table class="Overdue-table">
                <thead>
                    <tr>
                        <th>Book Id</th>
                        <th>Title</th>
                        <th>User Name</th>
                        <th>Due Date</th>
                        <th>Days Overdue</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="overdue-table-body">
                    <!-- Dynamic content will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="loading" style="display: none;">Loading...</div>