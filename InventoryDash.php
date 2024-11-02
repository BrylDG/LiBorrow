<?php

include('connection.php'); // Include your database connection

// Get the total number of records from the returns table
$total_records_query = "SELECT COUNT(*) FROM books";
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
$sql = "SELECT bookid, booktitle, author, genre, pubdate, quantity,descrpt FROM books LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);

// Initial SQL query with pagination
$sql = "SELECT bookid, booktitle, author, genre, pubdate, quantity,descrpt FROM books WHERE 1=1";

// Add pagination limits
$sql .= " LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
?>
<div class="content-box" id="content2">
    <div class="container">

        <div id="d1" class="Inventory-box">
            <div class="input" >
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
            <!-- Table Section -->
            <table class="Inventory-table">
                <thead>
                    <tr>
                        <th>Book Id</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include("connection.php");
                        
                        $query = "SELECT bookid, booktitle, author, genre FROM books";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $row["bookid"]. "</td><td>" . $row["booktitle"]. "</td><td>" . $row["author"]. "</td><td>" . $row["genre"]. "</td><td><a href='#' class='view-more'>View more</a></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No data found</td></tr>";
                        }
                    ?>

                    <!-- <tr>
                        <td>0001</td>
                        <td>Alice in Wonderland</td>
                        <td>Lewis Carroll</td>
                        <td>Fantasy/Adventure</td> 
                        <td class="act"><a href="#">View More</a></td>
                    </tr>
                    <tr>
                        <td>0002</td>
                        <td>Treasure Land</td>
                        <td>Lewis Carroll</td>
                        <td>Fantasy/Adventure</td> 
                        <td class="act"><a href="#">View More</a></td>
                    </tr>
                    <tr>
                        <td>0003</td>
                        <td>Treasure Land</td>
                        <td>Lewis Carroll</td>
                        <td>Fantasy/Adventure</td> 
                        <td class="act"><a href="#">View More</a></td>
                    </tr>
                    <tr>
                        <td>0004</td>
                        <td>Treasure Land</td>
                        <td>Lewis Carroll</td>
                        <td>Fantasy/Adventure</td> 
                        <td class="act"><a href="#">View More</a></td>
                    </tr>
                    <tr>
                        <td>0005</td>
                        <td>Treasure Land</td>
                        <td>Lewis Carroll</td>
                        <td>Fantasy/Adventure</td> 
                        <td class="act"><a href="#">View More</a></td>
                    </tr> -->
                    
                </tbody>
            </table>
            <div class="Inventory-pagination">
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
        document.querySelectorAll('button-group a').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior
    
                // Remove 'active' class from all links
                document.querySelectorAll('button-group a').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add 'active' class to the clicked link
                this.classList.add('active');
            });
        });
    </script>    
</div>
