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
        echo '<tr><td colspan="3">No borrowers found for this book.</td></tr>';
    }
    exit; // Exit after returning borrower data
}

// If no specific book title is provided, fetch all books
$query = "SELECT b.booktitle, b.author, COUNT(br.idno) AS count, b.bookimg 
          FROM books b 
          LEFT JOIN borrows br ON b.booktitle = br.booktitle 
          GROUP BY b.booktitle, b.author, b.bookimg";
$result = $conn->query($query);

// Start generating the main HTML for the books
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script src="script.js" defer></script> <!-- Link to your JavaScript file -->
</head>
<body>
    <h1>Borrowed Books</h1>
    <div id="borrowContainer" class="Borrowbox">
        <?php
        if ($result->num_rows > 0) {
            while ($book = $result->fetch_assoc()) {
                echo '
                <div class="Borrbox Bglobal">
                    <img src="' . htmlspecialchars($book['bookimg']) . '" alt="' . htmlspecialchars($book['booktitle']) . '" width="100" height="150">
                    <div class="book-details">
                        <p class="book-title">' . htmlspecialchars($book['booktitle']) . '</p>
                        <p class="author">' . htmlspecialchars($book['author']) . '</p>
                    </div>
                    <div class="Borrowed-total">
                        <p>Total Borrowed: ' . htmlspecialchars($book['count']) . '</p>
                    </div>
                    <div class="viewbtn">
                        <button class="view-borrowers" data-booktitle="' . htmlspecialchars($book['booktitle']) . '">View Borrowers</button>
                    </div>
                    <div class="borrowers-dropdown" style="display:none;">
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
</body>
</html>