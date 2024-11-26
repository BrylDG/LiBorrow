<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Make sure to exit after the redirect
}

// Retrieve the full name from the session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User  '; // Default to 'User  ' if not set

// Initialize variables for search, sort, and genre filter
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : '';
$genreFilter = isset($_GET['genre']) ? $_GET['genre'] : '';
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // Number of records per page
$offset = ($currentPage - 1) * $limit;

// Fetch data for the current page with search, sort, and filter
$sql = "SELECT b.bookid, b.booktitle, b.author, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres, b.pubdate, b.quantity, b.descrpt 
        FROM books b
        LEFT JOIN bookgenres bg ON b.bookid = bg.bookid
        LEFT JOIN genres g ON bg.genreid = g.genreid
        WHERE 1=1";

if ($searchTerm) {
    $sql .= " AND (b.booktitle LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR b.author LIKE '%" . $conn->real_escape_string($searchTerm) . "%')";
}

if ($genreFilter) {
    $sql .= " AND g.name = '" . $conn->real_escape_string($genreFilter) . "'";
}

if ($sortBy) {
    $sql .= " ORDER BY " . $conn->real_escape_string($sortBy);
}

$sql .= " GROUP BY b.bookid LIMIT $limit OFFSET $offset"; // Add pagination and group by bookid

$result = $conn->query($sql);
?>

<div class="content-box" id="content2">
    <div class="container">
        <div id="d1" class="Inventory-box">
            <div class="input" style="padding-left: 50px; padding-top: 20px;">
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search..." oninput="loadBooks()">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                    </span>
                </div>
                <select id="sort-dropdown" onchange="loadBooks()" style="    font-size: 12px;
    padding: 8px 20px;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s ease;     background-color: #ff6600;
    color: white;">
                    <option value="">Sort By</option>
                    <option value="booktitle">Title</option>
                    <option value="author">Author</option>
                    <option value="pubdate">Publication Date</option>
                </select>
                <select id="genre-filter" onchange="loadBooks()" style="    font-size: 12px;
    padding: 8px 20px;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s ease;    border: 1px solid #ff6600;
    background-color: white;
    color: #ff6600;
    ">
                    <option value="">Filter by Genre</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Science">Science</option>
                    <option value="History">History</option>
                </select>
            </div>
            <a href="#" class="addbtn" id="addBookButton" style="cursor: pointer; text-decoration: none;">
                <img src="./Images/Add_square_fill.svg" alt="Add Icon">
                Add Book
            </a>
            <!-- Scrollable Table Section -->
            <div style="max-height: 400px; overflow-y: auto; width: 100%;">
                <table class="Inventory-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Book Id</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-table-body">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row["bookid"]) . "</td>
                                        <td>" . htmlspecialchars($row["booktitle"]) . "</td>
                                        <td>" . htmlspecialchars($row["author"]) . "</td>
                                        <td>" . htmlspecialchars($row["genres"]) . "</td>
                                        <td><a href='#' class='view-more'>View more</a></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div id="loading" style="display: none;">Loading more books...</div>
            </div>
        </div>
    </div>
</div>