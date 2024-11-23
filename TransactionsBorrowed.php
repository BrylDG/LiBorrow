<?php
// Database configuration
include ('connection.php');

// Fetch data from borrows table
$sql = "SELECT bookimg, booktitle, author, COUNT(*) AS count FROM borrows GROUP BY bookimg, booktitle, author";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Borrowed Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content-box" id="content2">
        <div class="container">
            <div class="input">
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                    </span>
                </div>
                <button class="sort-btn">
                    <img src="./Images/Sort.svg" alt="Icon Before" width="20" height="20"> Sort By
                    <img src="./Images/vec.svg" alt="Icon After" width="18" height="18">
                </button>
                <button class="filter-btn">
                    <img src="./Images/Filter_alt_fill.svg" alt="Icon Before" width="20" height="20"> Filter By
                    <img src="./Images/Expand_down.svg" alt="Icon After" width="18" height="18">
                </button>
            </div>
            <div id="borrowContainer" class="Borrowbox">
                <?php
                if ($result->num_rows > 0) {
                    // Output data for each row
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
                                <a href="javascript:void(0);" onclick="toggleDropdown(event)">View Borrowers</a>
                            </div>
                        </div>';
                    }
                } else {
                    echo "<p>No records found</p>";
                }
                // Close connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            // Functionality for view borrowers
            // Placeholder function for dropdown interaction
            alert('View Borrowers clicked');
        }
    </script>
</body>
</html>