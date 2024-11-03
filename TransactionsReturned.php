<?php
session_start(); // Start the session
include('connection.php'); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the total number of records from the returns table
$total_records_query = "SELECT COUNT(*) FROM returns";
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
$sql = "SELECT bookname, author, status, username, datereturned, image FROM returns LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);

// Initial SQL query with pagination
$sql = "SELECT bookname, author, status, username, datereturned, image FROM returns WHERE 1=1";

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

// Add pagination limits
$sql .= " LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
?>
    <div class="content-box" id="content2">
        <div class="container">
            <div id="Returend-d1" class="Returned-box">
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
                
                <!-- Pagination Section -->
                <div class="Returned-pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?>" class="prev">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-number <?php echo ($i === $current_page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?>" class="next">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.Returned-pagination a').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default anchor behavior

                    // Remove 'active' class from all links
                    document.querySelectorAll('.Returned-pagination a').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    
                    // Add 'active' class to the clicked link
                    this.classList.add('active');
                });
            });
        </script>
