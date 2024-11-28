<?php
// Include your database connection file
include('connection.php');

// Check if a book title has been provided
if (isset($_GET['booktitle'])) {
    $booktitle = $conn->real_escape_string($_GET['booktitle']);

    // Query to fetch borrowers for the selected book
    $query = "SELECT idno, fullname, duedate FROM borrows WHERE booktitle = '$booktitle'";
    $result = $conn->query($query);

    // Generate HTML for the borrower table
    if ($result->num_rows > 0) {
        while ($borrower = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . htmlspecialchars($borrower['idno']) . '</td>
                    <td>' . htmlspecialchars($borrower['fullname']) . '</td>
                    <td>' . htmlspecialchars($borrower['duedate']) . '</td>
                    <td><button class="return-button" data-idno="' . htmlspecialchars($borrower['idno']) . '" data-booktitle="' . htmlspecialchars($booktitle) . '">Return</button></td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="4">No borrowers found for this book.</td></tr>';
    }
    exit; // Exit after returning borrower data
}

// Check if search or sort parameters have been provided
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : '';

// Base query to fetch books with optional filtering and sorting
$query = "SELECT b.booktitle, b.author, COUNT(br.idno) AS count, b.bookimg 
          FROM books b 
          LEFT JOIN borrows br ON b.booktitle = br.booktitle 
          WHERE b.booktitle LIKE '%$searchTerm%' OR b.author LIKE '%$searchTerm%'
          GROUP BY b.booktitle, b.author, b.bookimg";

// Add sorting if specified
if ($sortBy) {
    $query .= " ORDER BY " . $sortBy;
}

$result = $conn->query($query);

// Start generating the main HTML for the books
?>
<div class="content-box" id="content2">
    <div class="container">
        <div class="input" style="padding-top: 20px; padding-bottom: 50px;">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search..." oninput="loadBooksB()">
                <span class="search-icon">
                    <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                </span>
            </div>
            <select id="sort-dropdown" onchange="loadBooksB()">
                <option value="">Sort By</option>
                <option value="booktitle">Title</option>
                <option value="author">Author</option>
            </select>
        </div>
        <div id="borrowContainer" class="Borrowbox">
            <div id="books-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($book = $result->fetch_assoc()) {
                        echo '
                        <div class="Borrbox Bglobal">
                            <div style="width: 30%;">
                                <img src="' . htmlspecialchars($book['bookimg']) . '" alt="' . htmlspecialchars($book['booktitle']) . '" width="100" height="150">
                            </div>
                            <div class="book-details" style="width: 40%">
                                <p class="book-title">' . htmlspecialchars($book['booktitle']) . '</p>
                                <p class="author">' . htmlspecialchars($book['author']) . '</p>
                            </div>
                            <div class="Borrowed-total" style="width: 20%">
                                <p>Total Borrowed: ' . htmlspecialchars($book['count']) . '</p>
                            </div>
                            <div class="viewbtn" style="width: 20%; justify-content: center; display: flex;">
                                <button class="view-borrowers" data-booktitle="' . htmlspecialchars($book['booktitle']) . '">View Borrowers</button>
                            </div>
                            <div class="borrowers-dropdown" style="display: none; margin-top: 10px;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID No</th>
                                            <th>Full Name</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Borrower data will be populated here via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>';
                    }
                } else {
                    echo "<p>No records found</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
