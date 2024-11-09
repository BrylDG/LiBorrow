<?php
include('connection.php');

// Function to get all users
function getAllUsers($conn) {
    $sql = "SELECT idno, fullname, email FROM users";
    return $conn->query($sql);
}

$result = getAllUsers($conn);

// If it's an AJAX request
if(isset($_GET['ajax'])) {
    $response = [
        'table_body' => ''
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

    echo json_encode($response);
    exit;
}

// If it's not an AJAX request, display the full page
?>
<div class="content-box" id="readers-info-content">
    <div class="container">
        <div id="d1" class="Reader-box">
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

<script>
let currentPage = 1;
const resultsPerPage = 5; // Adjust this value based on how many records you want to load at a time
let isLoading = false;

function loadUsers() {
    if (isLoading) return; // Prevent multiple requests
    isLoading = true;
    document.getElementById('loading').style.display = 'block';

    fetch(`ReaderDash.php?ajax=1&page =${currentPage}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('reader-table-body').insertAdjacentHTML('beforeend', data.table_body);
            currentPage++;
            isLoading = false;
            document.getElementById('loading').style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            isLoading = false;
            document.getElementById('loading').style.display = 'none';
        });
}

window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        loadUsers();
    }
});

// Initial load
loadUsers();
</script>