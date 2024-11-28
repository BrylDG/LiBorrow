<?php
session_start(); // Start the session
include('connection.php'); // Include your connection file

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit(); // Stop further script execution after redirect
}

// Retrieve the full name from the session
$fullname = $_SESSION['fullname'] ?? 'User  ';

// Initialize variables for search, sort, and genre filter
$searchTerm = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? '';
$genreFilter = $_GET['genre'] ?? '';
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // Number of records per page
$offset = ($currentPage - 1) * $limit;

// Base SQL query
// Base SQL query
$sql = "SELECT b.bookid, b.booktitle, b.author, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres, b.pubdate
        FROM books b
        LEFT JOIN bookgenres bg ON b.bookid = bg.bookid
        LEFT JOIN genres g ON bg.genreid = g.genreid
        WHERE 1=1";

// Add search filter
if (!empty($searchTerm)) {
    $sql .= " AND (b.booktitle LIKE ? OR b.author LIKE ?)";
}

// Add genre filter
if (!empty($genreFilter)) {
    $sql .= " AND g.name = ?";
}

// Group by bookid, booktitle, author, pubdate
$sql .= " GROUP BY b.bookid, b.booktitle, b.author, b.pubdate";

// Add sorting
$allowedSortColumns = ['booktitle', 'author', 'pubdate'];
if (in_array($sortBy, $allowedSortColumns)) {
    $sql .= " ORDER BY $sortBy";
}

// Now, directly append LIMIT and OFFSET with variables
$sql .= " LIMIT ? OFFSET ?"; // Use placeholders for limit and offset

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters for search and genre filter
$bindParams = [];
if (!empty($searchTerm)) {
    $searchLike = "%$searchTerm%";
    $bindParams[] = &$searchLike; // For booktitle
    $bindParams[] = &$searchLike; // For author
}
if (!empty($genreFilter)) {
    $bindParams[] = &$genreFilter; // For genre
}

// Bind limit and offset
$bindParams[] = &$limit; // For limit
$bindParams[] = &$offset; // For offset

// Bind the parameters
if (!empty($bindParams)) {
    $bindTypes = str_repeat('s', count($bindParams) - 2) . 'ii'; // 's' for string params, 'i' for integer params
    call_user_func_array([$stmt, 'bind_param'], array_merge([$bindTypes], $bindParams));
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $response = ['table_body' => []];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['table_body'][] = [
                'bookid' => $row['bookid'],
                'booktitle' => $row['booktitle'],
                'author' => $row['author'],
                'genres' => $row['genres']
            ];
        }
    }
    header('Content-Type: application/json'); // Set the content type to JSON
    echo json_encode($response);
    exit();
}
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
                <select id="sort-dropdown" onchange="loadBooks()" style="font-size: 12px; padding: 8px 20px; border-radius: 30px; cursor: pointer; background-color: #ff6600; color: white;">
                    <option value="">Sort By</option>
                    <option value="booktitle">Title</option>
                    <option value="author">Author</option>
                    <option value="pubdate">Publication Date</option>
                </select>
                <select id="genre-filter" onchange="loadBooks()" style="font-size: 12px; padding: 8px 20px; border-radius: 30px; cursor: pointer; border: 1px solid #ff6600; background-color: white; color: #ff6600;">
                    <option value="">Filter by Genre</option>
                    <option value="Romance">Romance</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Science Fiction">Science Fiction</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Self-Help">Self-Help</option>
                    <option value="Drama">Drama</option>
                    <option value="Biography">Biography</option>
                    <option value="History">History</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Poetry">Poetry</option>
                    <option value="Cooking">Cooking</option>
                    <option value="Graphic Novel">Graphic Novel</option>
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
										<td>" . htmlspecialchars($row["bookid"] ?? '') . "</td>
										<td>" . htmlspecialchars($row["booktitle"] ?? '') . "</td>
										<td>" . htmlspecialchars($row["author"] ?? '') . "</td>
										<td>" . htmlspecialchars($row["genres"] ?? '') . "</td>
										<td><a href='#' class='view-more' data-idno='" . htmlspecialchars($row['bookid'] ?? '') . "'>View more</a></td>
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