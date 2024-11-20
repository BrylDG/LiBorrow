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

// Function to get all users with optional search and sorting
function getAllUsers($conn, $searchTerm = '', $sortBy = '') {
    $sql = "SELECT idno, fullname, email FROM users WHERE fullname LIKE ?";
    
    // Prepare the search term
    $searchTerm = '%' . $conn->real_escape_string($searchTerm) . '%';

    // Add sorting
    if ($sortBy) {
        $sql .= " ORDER BY " . $conn->real_escape_string($sortBy);
    }

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $searchTerm);
    
    $stmt->execute();
    return $stmt->get_result();
}

// Initialize variables for search and sort
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : '';

// Get users based on search and sort
$result = getAllUsers($conn, $searchTerm, $sortBy);

// Check if it's an AJAX request
if (isset($_GET['ajax'])) {
    $data = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'idno' => htmlspecialchars($row["idno"]),
                'fullname' => htmlspecialchars($row["fullname"]),
                'email' => htmlspecialchars($row["email"]),
            ];
        }
    }

    // Return JSON response
    echo json_encode(['table_body' => $data]);
    exit; // Stop further execution
}

// If it's not an AJAX request, display the full page
?>
<div class="content-box" id="readers-info-content">
    <div class="container">
        <div id="d1" class="Reader-box">
            <div class="input-area">
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search..." oninput="loadUsers()">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                    </span>
                </div>
                <select id="sort-dropdown" onchange="loadUsers()">
                    <option value="">Sort By</option>
                    <option value="fullname">Name</option>
                    <option value="email">Email</option>
                </select>
            </div>

            <!-- Scrollable Table Section -->
            <div style="max-height: 400px; overflow-y: auto; width: 95%;">
                <table class="reader-table" style="width: 95%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reader-table-body">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row["idno"]) . "</td>
                                        <td>" . htmlspecialchars($row["fullname"]) . "</td>
                                        <td>" . htmlspecialchars($row["email"]) . "</td>
                                        <td><a href='#' class='view-more'>View more</a></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Loading Indicator -->
            <div id="loading" style="display: none;">Loading more users...</div>
        </div>
    </div>
</div>

