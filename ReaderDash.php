
<title>Reader's Information</title>
<div class="content-box" id="readers-info-content">
    <div class="container">
        <div id="d1" class="Reader-box">
            <!-- Input text field and buttons centered on top -->
            <div class="input-area">
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
                        include("connection.php");
                        
                        $query = "SELECT idno, fullname, email FROM users";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $row["idno"]. "</td><td>" . $row["fullname"]. "</td><td>" . $row["email"]. "</td><td><a href='#' class='view-more'>View more</a></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No data found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
            <div class="Reader-pagination">
                <span><img src="./Images/Expand_left.svg" alt="Previous Icon" width="18" height="18"></span>
                <a href="#" class="prev">Previous</a>
                <a href="#" class="page-number active" id="one">1</a>
                <a href="#" class="page-number" id="num">2</a>
                <a href="#" class="page-number" id="num">3</a>
                <a href="#" class="page-number" id="num">4</a>
                <a href="#" class="page-number" id="num">...</a>
                <a href="#" class="page-number" id="num">50</a>
                <a href="#" class="next">Next</a>
                <span><img src="./Images/Expand_right.svg" alt="Next Icon" width="18" height="18"></span>
            </div>            
        </div>
        
    </div>
</div>
