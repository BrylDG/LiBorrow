<?php
include('connection.php'); // Include your database connection

// Get the total number of records from the 'users' table
$total_records_query = "SELECT COUNT(*) FROM users";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_row()[0];

// Set the number of results per page
$results_per_page = 5;

// Calculate total pages
$total_pages = ceil($total_records / $results_per_page);

// Get the current page number from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages));

// Calculate the starting record for the current page
$start_from = ($current_page - 1) * $results_per_page;

// Fetch data for the current page
$sql = "SELECT idno, fullname, email FROM users LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reader's Information</title>
</head>
<body>
<div class="content-box" id="readers-info-content">
    <div class="container">
        <div id="d1" class="Reader-box">
            <!-- Input text field and buttons centered on top -->
            <div class="input-area">
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                    </span>
                </div>
                <button class="sort-btn">
                    <img src="./Images/Sort.svg" alt="Sort Icon" width="20" height="20"> 
                    Sort By
                    <img src="./Images/vec.svg" alt="Sort Arrow" width="18" height="18">
                </button>
                <button class="filter-btn">
                    <img src="./Images/Filter_alt_fill.svg" alt="Filter Icon" width="20" height="20"> 
                    Filter By
                    <img src="./Images/Expand_down.svg" alt="Filter Arrow" width="18" height="18">
                </button>
            </div>

            <!-- Table Section -->
            <table class="reader-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row["idno"]. "</td><td>" . $row["fullname"]. "</td><td>" . $row["email"]. "</td><td><a href='#' class='view-more'>View more</a></td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination Section -->
            <div class="Reader-pagination">
            <?php if ($current_page > 1): ?>
             <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=<?php echo $current_page - 1; ?>" class="prev">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=<?php echo $i; ?>" class="page-number <?php echo ($i === $current_page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=<?php echo $current_page + 1; ?>" class="next">Next</a>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
