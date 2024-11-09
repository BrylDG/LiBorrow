<?php
include('connection.php'); // Include your database connection

// Set the number of results per page
$results_per_page = 5;

// Get the current page number from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page);

// Calculate the starting record for the current page
$start_from = ($current_page - 1) * $results_per_page;

// Fetch data for the current page
$sql = "SELECT bookid, booktitle, author, genre, pubdate, quantity, descrpt FROM books LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
?>
<div class="content-box" id="content2">
    <div class="container">
        <div id="d1" class="Inventory-box">
            <div class="input">
                <div class="search-bar">
                    <input type="text" placeholder=" Search...">
                    <span class="search-icon">
                        <img src="./Images/Search.svg" alt="Search Icon" width="20" height="20">
                    </span>
                </div>
                <button class="sort-btn">
                    <img src="./Images/Sort.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                    Sort By
                    <img src="./Images/vec.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
                </button>
                <button class="filter-btn">
                    <img src="./Images/Filter_alt_fill.svg" alt="Icon Before" width="20" height="20"> <!-- Icon before text -->
                    Filter By
                    <img src="./Images/Expand_down.svg" alt="Icon After" width="18" height="18"> <!-- Icon after text -->
                </button>
            </div>
            <div>
                <div class="addbtn">
                    <img src="./Images/Add_square_fill.svg" alt="add icon">
                    Add Book
                </div>
            </div>
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
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row["bookid"]) . "</td>
                                        <td>" . htmlspecialchars($row["booktitle"]) . "</td>
                                        <td>" . htmlspecialchars($row["author"]) . "</td>
                                        <td>" . htmlspecialchars($row["genre"]) . "</td>
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

    <script>
        let currentPage = 1;
        const resultsPerPage = 5; // Adjust this value based on how many records you want to load at a time
        let isLoading = false;

        function loadBooks() {
            if (isLoading) return; // Prevent multiple requests
            isLoading = true;
            document.getElementById('loading').style.display = 'block';

            fetch(`your_inventory_page.php?page=${currentPage + 1}`) // Adjust the URL to your actual page
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newRows = doc.get ElementById('inventory-table-body').innerHTML;
                    document.getElementById('inventory-table-body').insertAdjacentHTML('beforeend', newRows);
                    currentPage++;
                    isLoading = false;
                    document.getElementById('loading').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error loading more books:', error);
                    isLoading = false;
                    document.getElementById('loading').style.display = 'none';
                });
        }

        document.querySelector('.Inventory-box div[style*="max-height"]').addEventListener('scroll', function() {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight) {
                loadBooks();
            }
        });
    </script>
</div>