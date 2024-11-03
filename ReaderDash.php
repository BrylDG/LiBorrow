<?php
include('connection.php');

// Function to get total records
function getTotalRecords($conn) {
    $total_records_query = "SELECT COUNT(*) FROM users";
    $total_records_result = $conn->query($total_records_query);
    return $total_records_result->fetch_row()[0];
}

// Function to get users for a specific page
function getUsersForPage($conn, $start_from, $results_per_page) {
    $sql = "SELECT idno, fullname, email FROM users LIMIT $start_from, $results_per_page";
    return $conn->query($sql);
}

$results_per_page = 5;
$total_records = getTotalRecords($conn);
$total_pages = ceil($total_records / $results_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages));
$start_from = ($current_page - 1) * $results_per_page;
$result = getUsersForPage($conn, $start_from, $results_per_page);

// If it's an AJAX request
if(isset($_GET['ajax'])) {
    $response = [
        'table_body' => '',
        'pagination' => ''
    ];

    // Generate table body HTML
    ob_start();
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
    $response['table_body'] = ob_get_clean();

    // Generate pagination HTML
    ob_start();
    if ($total_pages > 1) {
        if ($current_page > 1) {
            echo "<a href='#' class='prev' data-page='" . ($current_page - 1) . "'>Previous</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='#' class='page-number " . ($i === $current_page ? 'active' : '') . "' data-page='$i'>$i</a>";
        }

        if ($current_page < $total_pages) {
            echo "<a href='#' class='next' data-page='" . ($current_page + 1) . "'>Next</a>";
        }
    }
    $response['pagination'] = ob_get_clean();

    echo json_encode($response);
    exit;
}

// If it's not an AJAX request, display the full page
?>
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

            <!-- Pagination Section -->
            <div class="Reader-pagination" id="reader-pagination">
                <?php
                if ($total_pages > 1) {
					if ($current_page > 1) {
						echo "<a href='#' class='prev' data-page='" . ($current_page - 1) . "'>Previous</a>";
					}

					for ($i = 1; $i <= $total_pages; $i++) {
						echo "<a href='#' class='page-number " . ($i == $current_page ? 'active' : '') . "' data-page='$i'>$i</a>";
					}

					if ($current_page < $total_pages) {
						echo "<a href='#' class='next' data-page='" . ($current_page + 1) . "'>Next</a>";
					}
				}
                ?>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;

function loadReaderPage(page = 1) {
    currentPage = page;
    fetch(`ReaderDash.php?ajax=1&page=${page}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('reader-table-body').innerHTML = data.table_body;
            document.getElementById('reader-pagination').innerHTML = data.pagination;
            attachPaginationListeners();
        })
        .catch(error => console.error('Error:', error));
}

function attachPaginationListeners() {
    document.querySelectorAll('#reader-pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page'));
            loadReaderPage(page);
        });
    });
}

// Initial pagination listeners
attachPaginationListeners();

// If you want to maintain the current page when the user navigates back to the Readers List
document.getElementById("button1").addEventListener("click", function(event) {
    event.preventDefault();
    fetch('./ReaderDash.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById("body-content").innerHTML = data;
            document.title = "Readers List";
            document.getElementById("page-title").innerText = "Readers Lists";
            // Load the current page or default to page 1
            loadReaderPage(currentPage);
        })
        .catch(error => handleError('Error fetching ReaderDash:', error));
});
</script>