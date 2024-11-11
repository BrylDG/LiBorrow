<?php
include('connection.php'); // Include your database connection

// Fetch data for the current page
$sql = "SELECT bookid, booktitle, author, genre, pubdate, quantity, descrpt FROM books";
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
                    <img src="./Images/Sort.svg" alt="Sort Icon" width="20" height="20">
                    Sort By
                    <img src="./Images/vec.svg" alt="Sort Arrow Icon" width="18" height="18">
                </button>
                <button class="filter-btn">
                    <img src="./Images/Filter_alt_fill.svg" alt="Filter Icon" width="20" height="20">
                    Filter By
                    <img src="./Images/Expand_down.svg" alt="Expand Icon" width="18" height="18">
                </button>
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

            function loadBooks() {
                if (isLoading) return; // Prevent multiple requests
                isLoading = true;
                $('#loading').show(); // Show loading indicator

                $.ajax({
                    url: `your_inventory_page.php?page=${currentPage + 1}`, // Update with your actual URL
                    method: 'GET',
                    success: function(data) {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        const newRows = doc.getElementById('inventory-table-body').innerHTML;
                        $('#inventory-table-body').append(newRows); // Append new rows
                        currentPage++; // Increment current page
                        isLoading = false; // Reset loading state
                        $('#loading').hide(); // Hide loading indicator
                    },
                    error: function(error) {
                        console.error('Error loading more books:', error);
                        isLoading = false; // Reset loading state
                        $('#loading').hide(); // Hide loading indicator
                    }
                });
            }

            // Load more books on scroll
            $('.Inventory-box div[style*="max-height"]').on('scroll', function() {
                if (this.scrollTop + this.clientHeight >= this.scrollHeight) {
                    loadBooks(); // Call loadBooks when scrolled to the bottom
                }
            });
        });
    </script>
</div>